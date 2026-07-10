<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\ContactMessageReply;
use App\Models\TeacherDeployment;
use App\Models\User;
use App\Models\ActivityLog;
use App\Notifications\ContactMessageReplied;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of the contact inquiries.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user || !in_array($user->users_level, [0, 1, 2])) {
            abort(403, 'Unauthorized access.');
        }

        // Initialize inquiry query
        $query = ContactMessage::query();

        // If the user is a Teacher, they can only view messages matching their deployed purposes
        if ($user->isTeacher()) {
            $deployedPurposes = TeacherDeployment::where('user_id', $user->id)->pluck('purpose')->toArray();
            if (empty($deployedPurposes)) {
                $query->whereRaw('1 = 0'); // No messages if not deployed
            } else {
                $query->whereIn('purpose', $deployedPurposes);
            }
        }

        // Apply filters if provided
        if ($request->filled('purpose')) {
            $query->where('purpose', $request->purpose);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', '!=', 'Archived');
        }

        $inquiries = $query->orderBy('created_at', 'desc')->paginate(15);

        // Fetch teachers and current deployments for Admin/Developer
        $teachers = [];
        $deploymentsByPurpose = [];
        $validPurposes = ['Inquiry', 'Complaint', 'Suggestion', 'Purchase', 'Priority', 'Other'];

        if ($user->isDeveloper() || $user->isSchoolAdmin()) {
            $teachers = User::where('users_level', 2)->orderBy('name')->get();
            
            // Get all deployments grouped by purpose
            $allDeployments = TeacherDeployment::with('user')->get();
            foreach ($validPurposes as $p) {
                $deploymentsByPurpose[$p] = $allDeployments->where('purpose', $p);
            }
        }

        return view('contact-messages.index', compact('inquiries', 'teachers', 'deploymentsByPurpose', 'validPurposes'));
    }

    /**
     * Display the specified contact inquiry.
     */
    public function show(ContactMessage $contactMessage)
    {
        $user = Auth::user();
        if (!$contactMessage->canBeManagedBy($user)) {
            abort(403, 'Unauthorized to view this inquiry.');
        }

        $contactMessage->load('replies.user');

        return view('contact-messages.show', compact('contactMessage'));
    }

    /**
     * Helper to detect foul language.
     */
    protected function hasProfanity(string $text): bool
    {
        $foulWords = [
            'fuck', 'shit', 'asshole', 'bitch', 'bastard', 'cunt', 'dick', 'pussy',
            'putangina', 'tangina', 'gago', 'tarantado', 'puke', 'tite', 'puta', 'kupal', 'ulol', 'siraulo'
        ];

        $lowerText = strtolower($text);
        foreach ($foulWords as $word) {
            if (preg_match('/\b' . preg_quote($word, '/') . '\b/i', $lowerText)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Post a reply to a contact inquiry.
     */
    public function reply(Request $request, ContactMessage $contactMessage)
    {
        $user = Auth::user();
        if (!$contactMessage->canBeManagedBy($user)) {
            abort(403, 'Unauthorized to reply to this inquiry.');
        }

        $request->validate([
            'message' => 'required|string|max:5000',
            'attachment' => 'nullable|file|max:15360', // max 15MB
            'parent_reply_id' => 'nullable|exists:contact_message_replies,id',
            'reply_to_original' => 'nullable|boolean',
            'reply_mode' => 'required|string|in:email,sms,both',
        ]);

        $messageText = $request->message;
        $isFlagged = $this->hasProfanity($messageText);

        $attachmentPath = null;
        $attachmentName = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();
            $attachmentPath = $file->store('attachments', 'public');
        }

        $reply = ContactMessageReply::create([
            'contact_message_id' => $contactMessage->id,
            'user_id' => $user->id,
            'message' => $messageText,
            'is_flagged' => $isFlagged,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'parent_reply_id' => $request->parent_reply_id,
            'reply_to_original' => $request->has('reply_to_original') ? (bool) $request->reply_to_original : false,
            'reply_mode' => $request->reply_mode,
        ]);

        // Update inquiry status and flag if reply is flagged
        $contactMessage->status = 'Replied';
        if ($isFlagged) {
            $contactMessage->is_flagged = true;
        }
        $contactMessage->save();

        // Send email and SMS notification
        $contactMessage->notify(new ContactMessageReplied($contactMessage, $reply));

        // Log the activity
        ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'action' => 'Reply to Contact Inquiry',
            'description' => "Replied to inquiry #{$contactMessage->id} from {$contactMessage->name} (Purpose: {$contactMessage->purpose}).",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Reply posted successfully.');
    }

    /**
     * Mark an inquiry as resolved.
     */
    public function resolve(ContactMessage $contactMessage, Request $request)
    {
        $user = Auth::user();
        if (!$contactMessage->canBeManagedBy($user)) {
            abort(403, 'Unauthorized.');
        }

        $contactMessage->status = 'Resolved';
        $contactMessage->save();

        ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'action' => 'Resolve Contact Inquiry',
            'description' => "Marked inquiry #{$contactMessage->id} as Resolved.",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Inquiry marked as resolved.');
    }

    /**
     * Deploy a teacher to an inquiry purpose.
     */
    public function deployTeacher(Request $request)
    {
        $user = Auth::user();
        if (!$user || !in_array($user->users_level, [0, 1])) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'purpose' => 'required|string|in:Inquiry,Complaint,Suggestion,Purchase,Priority,Other',
        ]);

        // Ensure target user is a teacher
        $teacher = User::findOrFail($request->user_id);
        if (!$teacher->isTeacher()) {
            return back()->withErrors(['user_id' => 'Selected user is not a teacher.']);
        }

        // Avoid duplicate deployments
        TeacherDeployment::firstOrCreate([
            'user_id' => $teacher->id,
            'purpose' => $request->purpose,
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'action' => 'Deploy Teacher',
            'description' => "Deployed Teacher {$teacher->name} to handle '{$request->purpose}' inquiries.",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Teacher {$teacher->name} deployed to {$request->purpose} successfully.");
    }

    /**
     * Revoke a teacher's deployment.
     */
    public function revokeTeacher(TeacherDeployment $teacherDeployment, Request $request)
    {
        $user = Auth::user();
        if (!$user || !in_array($user->users_level, [0, 1])) {
            abort(403, 'Unauthorized.');
        }

        $teacher = $teacherDeployment->user;
        $purpose = $teacherDeployment->purpose;

        $teacherDeployment->delete();

        ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'action' => 'Revoke Teacher Deployment',
            'description' => "Revoked Teacher {$teacher->name} deployment for '{$purpose}' inquiries.",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Revoked deployment of {$teacher->name} for {$purpose}.");
    }

    /**
     * Archive the specified contact inquiry.
     */
    public function archive(ContactMessage $contactMessage, Request $request)
    {
        $user = Auth::user();
        if (!$contactMessage->canBeManagedBy($user)) {
            abort(403, 'Unauthorized.');
        }

        $contactMessage->status = 'Archived';
        $contactMessage->save();

        ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'action' => 'Archive Contact Inquiry',
            'description' => "Archived inquiry #{$contactMessage->id} from {$contactMessage->name}.",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Inquiry archived successfully.');
    }

    /**
     * Restore the specified contact inquiry from archive.
     */
    public function restore(ContactMessage $contactMessage, Request $request)
    {
        $user = Auth::user();
        if (!$contactMessage->canBeManagedBy($user)) {
            abort(403, 'Unauthorized.');
        }

        $contactMessage->status = 'Pending';
        $contactMessage->save();

        ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'action' => 'Restore Contact Inquiry',
            'description' => "Restored archived inquiry #{$contactMessage->id} from {$contactMessage->name}.",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Inquiry restored successfully.');
    }

    /**
     * Permanently delete the specified contact inquiry.
     */
    public function destroy(ContactMessage $contactMessage, Request $request)
    {
        $user = Auth::user();
        if (!$contactMessage->canBeManagedBy($user)) {
            abort(403, 'Unauthorized.');
        }

        $id = $contactMessage->id;
        $name = $contactMessage->name;
        $purpose = $contactMessage->purpose;

        $contactMessage->delete();

        ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'action' => 'Delete Contact Inquiry',
            'description' => "Permanently deleted inquiry #{$id} from {$name} (Purpose: {$purpose}).",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('contact-messages.index')->with('success', 'Inquiry permanently deleted.');
    }

    /**
     * Look up a contact message thread as a visitor.
     */
    public function lookupThread(Request $request)
    {
        $request->validate([
            'lookup_query' => 'required|string|max:255',
        ]);

        $query = $request->lookup_query;

        // Search for message matching name, email, or mobile number
        $message = ContactMessage::where('name', $query)
            ->orWhere('email', $query)
            ->orWhere('mobile_number', $query)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$message) {
            return back()->withErrors(['lookup_query' => 'No message thread found with the provided name, email, or mobile number.']);
        }

        if ($message->status === 'Resolved' || $message->status === 'Archived') {
            return back()->withErrors(['lookup_query' => 'This inquiry has been marked as resolved/done and the thread is no longer available.']);
        }

        return redirect()->route('contact-messages.guest-show', $message);
    }

    /**
     * Display the specified contact inquiry thread to the public/guest.
     */
    public function guestShow(ContactMessage $contactMessage)
    {
        if ($contactMessage->status === 'Resolved' || $contactMessage->status === 'Archived') {
            return view('contact-messages.guest-show', [
                'contactMessage' => $contactMessage,
                'error' => 'This thread has been resolved/done and is no longer available.'
            ]);
        }

        $contactMessage->load('replies.user');

        return view('contact-messages.guest-show', compact('contactMessage'));
    }

    /**
     * Post a reply to a contact inquiry as a guest.
     */
    public function guestReply(Request $request, ContactMessage $contactMessage)
    {
        if ($contactMessage->status === 'Resolved' || $contactMessage->status === 'Archived') {
            return abort(403, 'This thread is resolved/done and no longer accepting replies.');
        }

        $request->validate([
            'message' => 'required|string|max:5000',
            'attachment' => 'nullable|file|max:15360', // max 15MB
            'parent_reply_id' => 'nullable|exists:contact_message_replies,id',
            'reply_to_original' => 'nullable|boolean',
        ]);

        $messageText = $request->message;
        $isFlagged = $this->hasProfanity($messageText);

        $attachmentPath = null;
        $attachmentName = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();
            $attachmentPath = $file->store('attachments', 'public');
        }

        ContactMessageReply::create([
            'contact_message_id' => $contactMessage->id,
            'user_id' => null, // null denotes visitor/customer reply
            'message' => $messageText,
            'is_flagged' => $isFlagged,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'parent_reply_id' => $request->parent_reply_id,
            'reply_to_original' => $request->has('reply_to_original') ? (bool) $request->reply_to_original : false,
        ]);

        // Reopen thread to pending and update flagged status if message was foul
        $contactMessage->status = 'Pending';
        if ($isFlagged) {
            $contactMessage->is_flagged = true;
        }
        $contactMessage->save();

        return back()->with('success', 'Reply posted successfully.');
    }

    /**
     * Resolve/close thread as a guest and leave feedback.
     */
    public function guestResolve(Request $request, ContactMessage $contactMessage)
    {
        if ($contactMessage->status === 'Resolved' || $contactMessage->status === 'Archived') {
            return abort(403, 'This thread is already closed.');
        }

        $request->validate([
            'rating' => 'required|integer|min:0|max:5',
            'resolution_comment' => 'nullable|string|max:5000',
        ]);

        $contactMessage->status = 'Resolved';
        $contactMessage->rating = $request->rating;
        $contactMessage->resolution_comment = $request->resolution_comment;
        $contactMessage->save();

        return redirect()->route('contact-messages.guest-show', $contactMessage)->with('success', 'Thank you for your feedback! The thread has been resolved/closed.');
    }
}

