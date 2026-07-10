<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\Announcement;
use App\Models\StudentBoardPost;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicInfoController extends Controller
{
    /**
     * Helper to verify if the user can manage calendar events.
     */
    protected function authorizePostCalendar()
    {
        $user = Auth::user();
        if (!$user || (!in_array($user->users_level, [0, 1]) && !$user->can_post_calendar)) {
            abort(403, 'Unauthorized. You do not have permission to manage calendar events.');
        }
    }

    /**
     * Helper to verify if the user can manage announcements.
     */
    protected function authorizePostAnnouncement()
    {
        $user = Auth::user();
        if (!$user || (!in_array($user->users_level, [0, 1]) && !$user->can_post_announcement)) {
            abort(403, 'Unauthorized. You do not have permission to manage announcements.');
        }
    }

    /**
     * Helper to verify if the user can post to the student board.
     */
    protected function authorizePostStudentBoard()
    {
        $user = Auth::user();
        if (!$user || (!in_array($user->users_level, [0, 1]) && !$user->can_post_student_board)) {
            abort(403, 'Unauthorized. You do not have permission to post to the student board.');
        }
    }

    /**
     * Helper to verify if the user can edit/delete a student board post.
     */
    protected function authorizeManagePost(StudentBoardPost $post)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }
        if (in_array($user->users_level, [0, 1])) {
            return;
        }
        if ($user->can_post_student_board && $post->user_id === $user->id) {
            return;
        }
        abort(403, 'Unauthorized. You can only edit or delete your own posts.');
    }

    /**
     * Helper to verify if the user can access management at all.
     */
    protected function authorizeManagementAccess()
    {
        $user = Auth::user();
        if (!$user || (!in_array($user->users_level, [0, 1]) && !$user->can_post_calendar && !$user->can_post_announcement && !$user->can_post_student_board)) {
            abort(403, 'Unauthorized. You do not have permissions to access public info management.');
        }
    }

    /**
     * Process media inputs (file uploads and URLs) into a structured JSON array.
     */
    protected function processMedia(Request $request)
    {
        $media = [];
        $types = $request->input('media_type', []);
        $urls = $request->input('media_url', []);
        $names = $request->input('media_name', []);
        $extensions = $request->input('media_extension', []);
        $sizes = $request->input('media_size', []);
        $files = $request->file('media_file', []);

        foreach ($types as $index => $type) {
            if ($type === 'image') {
                if (isset($files[$index]) && $files[$index]->isValid()) {
                    $path = $files[$index]->store('media', 'public');
                    $media[] = [
                        'type' => 'image',
                        'url' => '/storage/' . $path,
                        'name' => $files[$index]->getClientOriginalName(),
                    ];
                } elseif (!empty($urls[$index])) {
                    $media[] = [
                        'type' => 'image',
                        'url' => $urls[$index],
                        'name' => isset($names[$index]) ? $names[$index] : null,
                    ];
                }
            } elseif ($type === 'video' && !empty($urls[$index])) {
                $media[] = [
                    'type' => 'video',
                    'url' => $urls[$index],
                    'name' => isset($names[$index]) ? $names[$index] : null,
                ];
            } elseif ($type === 'attachment' && !empty($urls[$index])) {
                $media[] = [
                    'type' => 'attachment',
                    'url' => $urls[$index],
                    'name' => isset($names[$index]) ? $names[$index] : null,
                    'extension' => isset($extensions[$index]) ? $extensions[$index] : null,
                    'size' => isset($sizes[$index]) ? $sizes[$index] : null,
                ];
            }
        }
        return count($media) > 0 ? $media : null;
    }

    public function index()
    {
        $tab = request()->query('tab', 'calendar');
        if (!in_array($tab, ['calendar', 'announcements', 'student-board'])) {
            $tab = 'calendar';
        }

        $schedules = CalendarEvent::where('is_archived', false)->orderBy('date', 'asc')->get();
        $announcements = Announcement::where('is_archived', false)->orderBy('created_at', 'desc')->get();
        $studentBoard = StudentBoardPost::where('is_archived', false)->orderBy('is_spotlight', 'desc')->orderBy('created_at', 'desc')->get();

        return view("dashboard.public-info.{$tab}.index", compact('schedules', 'announcements', 'studentBoard', 'tab'));
    }

    public function management()
    {
        $this->authorizeManagementAccess();

        $schedules = CalendarEvent::orderBy('date', 'asc')->get();
        $announcements = Announcement::orderBy('created_at', 'desc')->get();
        $studentBoard = StudentBoardPost::orderBy('is_spotlight', 'desc')->orderBy('created_at', 'desc')->get();

        $users = [];
        if (Auth::user() && in_array(Auth::user()->users_level, [0, 1])) {
            $users = \App\Models\User::orderBy('name', 'asc')->get();
        }

        return view('public-info-management', compact('schedules', 'announcements', 'studentBoard', 'users'));
    }

    // --- CALENDAR EVENTS ---

    public function storeEvent(Request $request)
    {
        $this->authorizePostCalendar();

        $validated = $request->validate([
            'date' => 'required|date',
            'type' => 'required|string|in:event,announcement',
            'time' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'media_type' => 'nullable|array',
            'media_type.*' => 'string|in:image,video',
            'media_url' => 'nullable|array',
            'media_url.*' => 'nullable|string',
            'media_file' => 'nullable|array',
            'media_file.*' => 'nullable|file|max:10240',
        ]);

        $validated['media'] = $this->processMedia($request);
        $event = CalendarEvent::create($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'action' => 'Create Calendar Event',
            'description' => "Created calendar event '{$event->title}' for {$event->date}.",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Calendar event added successfully.');
    }

    public function updateEvent(Request $request, CalendarEvent $event)
    {
        $this->authorizePostCalendar();

        $validated = $request->validate([
            'date' => 'required|date',
            'type' => 'required|string|in:event,announcement',
            'time' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'media_type' => 'nullable|array',
            'media_type.*' => 'string|in:image,video',
            'media_url' => 'nullable|array',
            'media_url.*' => 'nullable|string',
            'media_file' => 'nullable|array',
            'media_file.*' => 'nullable|file|max:10240',
        ]);

        $validated['media'] = $this->processMedia($request) ?: $event->media;
        $event->update($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'action' => 'Update Calendar Event',
            'description' => "Updated calendar event '{$event->title}' for {$event->date}.",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Calendar event updated successfully.');
    }

    public function destroyEvent(Request $request, CalendarEvent $event)
    {
        $this->authorizePostCalendar();

        $title = $event->title;
        $event->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'action' => 'Delete Calendar Event',
            'description' => "Deleted calendar event '{$title}'.",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Calendar event deleted successfully.');
    }

    // --- ANNOUNCEMENTS ---

    public function storeAnnouncement(Request $request)
    {
        $this->authorizePostAnnouncement();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'poll_question' => 'nullable|string|max:255',
            'poll_options' => 'nullable|array',
            'poll_options.*' => 'required_with:poll_question|string|max:255',
            'media_type' => 'nullable|array',
            'media_type.*' => 'string|in:image,video',
            'media_url' => 'nullable|array',
            'media_url.*' => 'nullable|string',
            'media_file' => 'nullable|array',
            'media_file.*' => 'nullable|file|max:10240',
        ]);

        if ($request->filled('poll_question')) {
            $options = array_values(array_filter($request->input('poll_options', []), function($val) {
                return !is_null($val) && trim($val) !== '';
            }));
            if (count($options) < 2) {
                return back()->withErrors(['poll_options' => 'At least two options are required when creating a poll.'])->withInput();
            }
            $validated['poll_options'] = $options;
            $validated['poll_votes'] = [];
        } else {
            $validated['poll_question'] = null;
            $validated['poll_options'] = null;
            $validated['poll_votes'] = null;
        }

        // Formats date nicely like "June 22, 2026"
        $validated['date'] = now()->format('F d, Y');
        $validated['author'] = Auth::user()->name;
        $validated['media'] = $this->processMedia($request);

        $announcement = Announcement::create($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'action' => 'Create Announcement',
            'description' => "Created announcement '{$announcement->title}'.",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Announcement added successfully.');
    }

    public function updateAnnouncement(Request $request, Announcement $announcement)
    {
        $this->authorizePostAnnouncement();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'poll_question' => 'nullable|string|max:255',
            'poll_options' => 'nullable|array',
            'poll_options.*' => 'required_with:poll_question|string|max:255',
            'media_type' => 'nullable|array',
            'media_type.*' => 'string|in:image,video',
            'media_url' => 'nullable|array',
            'media_url.*' => 'nullable|string',
            'media_file' => 'nullable|array',
            'media_file.*' => 'nullable|file|max:10240',
        ]);

        if ($request->filled('poll_question')) {
            $options = array_values(array_filter($request->input('poll_options', []), function($val) {
                return !is_null($val) && trim($val) !== '';
            }));
            if (count($options) < 2) {
                return back()->withErrors(['poll_options' => 'At least two options are required when creating a poll.'])->withInput();
            }
            $validated['poll_options'] = $options;
            
            $oldOptions = $announcement->poll_options ?? [];
            if ($oldOptions !== $options) {
                $validated['poll_votes'] = [];
            }
        } else {
            $validated['poll_question'] = null;
            $validated['poll_options'] = null;
            $validated['poll_votes'] = null;
        }

        $validated['media'] = $this->processMedia($request) ?: $announcement->media;
        $announcement->update($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'action' => 'Update Announcement',
            'description' => "Updated announcement '{$announcement->title}'.",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Announcement updated successfully.');
    }

    public function destroyAnnouncement(Request $request, Announcement $announcement)
    {
        $this->authorizePostAnnouncement();

        $title = $announcement->title;
        $announcement->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'action' => 'Delete Announcement',
            'description' => "Deleted announcement '{$title}'.",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Announcement deleted successfully.');
    }

    // --- STUDENT BOARD POSTS ---

    public function storePost(Request $request)
    {
        $this->authorizePostStudentBoard();

        $validated = $request->validate([
            'category' => 'required|string|in:achievement,happening',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'poll_question' => 'nullable|string|max:255',
            'poll_options' => 'nullable|array',
            'poll_options.*' => 'required_with:poll_question|string|max:255',
            'is_spotlight' => 'nullable|boolean',
            'media_type' => 'nullable|array',
            'media_type.*' => 'string|in:image,video,attachment',
            'media_url' => 'nullable|array',
            'media_url.*' => 'nullable|string',
            'media_file' => 'nullable|array',
            'media_file.*' => 'nullable|file|max:10240',
            'post_images' => 'nullable|array',
            'post_images.*' => 'file|image|max:10240',
            'post_attachments' => 'nullable|array',
            'post_attachments.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:20480',
        ]);

        if ($request->filled('poll_question')) {
            $options = array_values(array_filter($request->input('poll_options', []), function($val) {
                return !is_null($val) && trim($val) !== '';
            }));
            if (count($options) < 2) {
                return back()->withErrors(['poll_options' => 'At least two options are required when creating a poll.'])->withInput();
            }
            $validated['poll_options'] = $options;
            $validated['poll_votes'] = [];
        } else {
            $validated['poll_question'] = null;
            $validated['poll_options'] = null;
            $validated['poll_votes'] = null;
        }

        $isSpotlight = $request->boolean('is_spotlight');
        if ($isSpotlight) {
            StudentBoardPost::where('is_spotlight', true)->update(['is_spotlight' => false]);
        }
        $validated['is_spotlight'] = $isSpotlight;

        $media = $this->processMedia($request) ?: [];

        if ($request->hasFile('post_images')) {
            foreach ($request->file('post_images') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('media', 'public');
                    $media[] = [
                        'type' => 'image',
                        'url' => '/storage/' . $path,
                        'name' => $file->getClientOriginalName(),
                    ];
                }
            }
        }

        if ($request->hasFile('post_attachments')) {
            foreach ($request->file('post_attachments') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('attachments', 'public');
                    $media[] = [
                        'type' => 'attachment',
                        'url' => '/storage/' . $path,
                        'name' => $file->getClientOriginalName(),
                        'extension' => $file->getClientOriginalExtension(),
                        'size' => $file->getSize(),
                    ];
                }
            }
        }
        $validated['media'] = count($media) > 0 ? $media : null;

        $user = Auth::user();
        $validated['date'] = now()->format('F d, Y');
        $validated['author'] = $user->name;
        
        // Generate initials
        $words = explode(' ', $user->name);
        $initials = '';
        foreach ($words as $w) {
            $initials .= strtoupper(substr($w, 0, 1));
        }
        $validated['author_initials'] = substr($initials, 0, 3) ?: 'ST';
        $validated['user_id'] = $user->id;

        $post = StudentBoardPost::create($validated);

        ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'action' => 'Create Student Board Post',
            'description' => "Created student board post '{$post->title}' under '{$post->category}' (Spotlight: " . ($isSpotlight ? 'Yes' : 'No') . ").",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Student board post created successfully.');
    }

    public function updatePost(Request $request, StudentBoardPost $post)
    {
        $this->authorizeManagePost($post);

        $validated = $request->validate([
            'category' => 'required|string|in:achievement,happening',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'poll_question' => 'nullable|string|max:255',
            'poll_options' => 'nullable|array',
            'poll_options.*' => 'required_with:poll_question|string|max:255',
            'is_spotlight' => 'nullable|boolean',
            'media_type' => 'nullable|array',
            'media_type.*' => 'string|in:image,video,attachment',
            'media_url' => 'nullable|array',
            'media_url.*' => 'nullable|string',
            'media_file' => 'nullable|array',
            'media_file.*' => 'nullable|file|max:10240',
            'post_images' => 'nullable|array',
            'post_images.*' => 'file|image|max:10240',
            'post_attachments' => 'nullable|array',
            'post_attachments.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:20480',
        ]);

        if ($request->filled('poll_question')) {
            $options = array_values(array_filter($request->input('poll_options', []), function($val) {
                return !is_null($val) && trim($val) !== '';
            }));
            if (count($options) < 2) {
                return back()->withErrors(['poll_options' => 'At least two options are required when creating a poll.'])->withInput();
            }
            $validated['poll_options'] = $options;
            
            $oldOptions = $post->poll_options ?? [];
            if ($oldOptions !== $options) {
                $validated['poll_votes'] = [];
            }
        } else {
            $validated['poll_question'] = null;
            $validated['poll_options'] = null;
            $validated['poll_votes'] = null;
        }

        $isSpotlight = $request->boolean('is_spotlight');
        if ($isSpotlight) {
            StudentBoardPost::where('is_spotlight', true)->update(['is_spotlight' => false]);
        }
        $validated['is_spotlight'] = $isSpotlight;

        $media = $this->processMedia($request) ?: [];

        if ($request->hasFile('post_images')) {
            foreach ($request->file('post_images') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('media', 'public');
                    $media[] = [
                        'type' => 'image',
                        'url' => '/storage/' . $path,
                        'name' => $file->getClientOriginalName(),
                    ];
                }
            }
        }

        if ($request->hasFile('post_attachments')) {
            foreach ($request->file('post_attachments') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('attachments', 'public');
                    $media[] = [
                        'type' => 'attachment',
                        'url' => '/storage/' . $path,
                        'name' => $file->getClientOriginalName(),
                        'extension' => $file->getClientOriginalExtension(),
                        'size' => $file->getSize(),
                    ];
                }
            }
        }
        $validated['media'] = count($media) > 0 ? $media : null;

        $post->update($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'action' => 'Update Student Board Post',
            'description' => "Updated student board post '{$post->title}' (Spotlight: " . ($isSpotlight ? 'Yes' : 'No') . ").",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Student board post updated successfully.');
    }

    public function destroyPost(Request $request, StudentBoardPost $post)
    {
        $this->authorizeManagePost($post);

        $title = $post->title;
        $post->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'action' => 'Delete Student Board Post',
            'description' => "Deleted student board post '{$title}'.",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Student board post deleted successfully.');
    }
    public function voteAnnouncement(Request $request, Announcement $announcement)
    {
        $request->validate([
            'option' => 'required|integer',
        ]);

        if (!$announcement->poll_question || !$announcement->poll_options) {
            abort(400, 'This announcement does not have an active poll.');
        }

        $optionIndex = (int) $request->input('option');
        if (!array_key_exists($optionIndex, $announcement->poll_options)) {
            abort(400, 'Invalid option selected.');
        }

        $votes = $announcement->poll_votes ?? [];
        $userId = Auth::id();

        // Update or cast the vote
        $votes[$userId] = $optionIndex;
        $announcement->poll_votes = $votes;
        $announcement->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'action' => 'Cast Vote on Announcement Poll',
            'description' => "Voted for option '{$announcement->poll_options[$optionIndex]}' on announcement '{$announcement->title}' poll.",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Your vote has been cast successfully.');
    }

    public function votePost(Request $request, StudentBoardPost $post)
    {
        $request->validate([
            'option' => 'required|integer',
        ]);

        if (!$post->poll_question || !$post->poll_options) {
            abort(400, 'This student board post does not have an active poll.');
        }

        $optionIndex = (int) $request->input('option');
        if (!array_key_exists($optionIndex, $post->poll_options)) {
            abort(400, 'Invalid option selected.');
        }

        $votes = $post->poll_votes ?? [];
        $userId = Auth::id();

        // Update or cast the vote
        $votes[$userId] = $optionIndex;
        $post->poll_votes = $votes;
        $post->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'action' => 'Cast Vote on Student Board Post Poll',
            'description' => "Voted for option '{$post->poll_options[$optionIndex]}' on student board post '{$post->title}' poll.",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Your vote has been cast successfully.');
    }

    // --- ARCHIVE & RESTORE METHODS ---

    public function archiveEvent(Request $request, CalendarEvent $event)
    {
        $this->authorizePostCalendar();
        $event->update(['is_archived' => true]);
        
        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'action' => 'Archive Calendar Event',
            'description' => "Archived calendar event '{$event->title}'.",
            'ip_address' => $request->ip(),
        ]);
        return back()->with('success', 'Calendar event archived successfully.');
    }

    public function restoreEvent(Request $request, CalendarEvent $event)
    {
        $this->authorizePostCalendar();
        $event->update(['is_archived' => false]);
        
        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'action' => 'Restore Calendar Event',
            'description' => "Restored calendar event '{$event->title}'.",
            'ip_address' => $request->ip(),
        ]);
        return back()->with('success', 'Calendar event restored successfully.');
    }

    public function archiveAnnouncement(Request $request, Announcement $announcement)
    {
        $this->authorizePostAnnouncement();
        $announcement->update(['is_archived' => true]);
        
        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'action' => 'Archive Announcement',
            'description' => "Archived announcement '{$announcement->title}'.",
            'ip_address' => $request->ip(),
        ]);
        return back()->with('success', 'Announcement archived successfully.');
    }

    public function restoreAnnouncement(Request $request, Announcement $announcement)
    {
        $this->authorizePostAnnouncement();
        $announcement->update(['is_archived' => false]);
        
        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'action' => 'Restore Announcement',
            'description' => "Restored announcement '{$announcement->title}'.",
            'ip_address' => $request->ip(),
        ]);
        return back()->with('success', 'Announcement restored successfully.');
    }

    public function archivePost(Request $request, StudentBoardPost $post)
    {
        $this->authorizeManagePost($post);
        $post->update(['is_archived' => true]);
        
        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'action' => 'Archive Student Board Post',
            'description' => "Archived student board post '{$post->title}'.",
            'ip_address' => $request->ip(),
        ]);
        return back()->with('success', 'Student board post archived successfully.');
    }

    public function restorePost(Request $request, StudentBoardPost $post)
    {
        $this->authorizeManagePost($post);
        $post->update(['is_archived' => false]);
        
        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'action' => 'Restore Student Board Post',
            'description' => "Restored student board post '{$post->title}'.",
            'ip_address' => $request->ip(),
        ]);
        return back()->with('success', 'Student board post restored successfully.');
    }

    // --- CAPABILITIES SYSTEM ---

    public function updateCapabilities(Request $request, \App\Models\User $user)
    {
        // Only Developers (0) and Admins (1) can assign or revoke capabilities
        $currentUser = Auth::user();
        if (!$currentUser || !in_array($currentUser->users_level, [0, 1])) {
            abort(403, 'Unauthorized.');
        }

        $user->update([
            'can_post_calendar' => $request->has('can_post_calendar'),
            'can_post_announcement' => $request->has('can_post_announcement'),
            'can_post_student_board' => $request->has('can_post_student_board'),
            'can_manage_admissions' => $request->has('can_manage_admissions'),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'action' => 'Update User Capabilities',
            'description' => "Updated posting capabilities for user '{$user->name}' (Calendar: " . ($user->can_post_calendar ? 'Yes' : 'No') . ", Announcement: " . ($user->can_post_announcement ? 'Yes' : 'No') . ", Student Board: " . ($user->can_post_student_board ? 'Yes' : 'No') . ").",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Capabilities updated successfully for {$user->name}.");
    }
}
