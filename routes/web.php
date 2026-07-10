<?php

use App\Http\Controllers\ProfileController;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\Career;

Route::get('/', function () {
    $careers = Career::inRandomOrder()->take(5)->get();
    return view('welcome', compact('careers'));
});

Route::get('/all-careers', function () {
    $careers = Career::orderBy('id', 'asc')->get();
    return view('careers.all', compact('careers'));
})->name('careers.all');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/public-info', [\App\Http\Controllers\PublicInfoController::class, 'index'])->name('public-info');

Route::get('/courses', function () {
    return redirect('/#courses');
})->name('courses');

Route::get('/courses/basic', function () {
    return view('courses.basic');
})->name('courses.basic');

Route::get('/courses/advanced', function () {
    return view('courses.advanced');
})->name('courses.advanced');

// Course Admission & Assessment Routes
Route::get('/admission/selection', [\App\Http\Controllers\AdmissionController::class, 'selection'])->name('admission.selection');
Route::get('/admission/assessment', [\App\Http\Controllers\AdmissionController::class, 'assessment'])->name('admission.assessment');
Route::post('/admission/assessment', [\App\Http\Controllers\AdmissionController::class, 'submitAssessment'])->name('admission.assessment.submit');
Route::get('/admission/form', [\App\Http\Controllers\AdmissionController::class, 'showForm'])->name('admission.form');
Route::post('/admission/submit', [\App\Http\Controllers\AdmissionController::class, 'submitAdmission'])->name('admission.submit');
Route::get('/admission/confirmation/{admission}', [\App\Http\Controllers\AdmissionController::class, 'confirmation'])->name('admission.confirmation');

Route::post('/contact', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'pronouns' => 'nullable|string|max:255',
        'mobile_number' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'purpose' => 'required|string|max:255',
        'other_purpose' => 'nullable|string|max:255',
        'message' => 'required|string',
    ]);
    
    if ($validated['purpose'] === 'Other' && !empty($validated['other_purpose'])) {
        $validated['purpose'] = 'Other: ' . $validated['other_purpose'];
    }
    unset($validated['other_purpose']);
    
    // Check profanity on original message
    $foulWords = ['fuck', 'shit', 'asshole', 'bitch', 'bastard', 'cunt', 'dick', 'pussy', 'putangina', 'tangina', 'gago', 'tarantado', 'puke', 'tite', 'puta', 'kupal', 'ulol', 'siraulo'];
    $isFlagged = false;
    $lowerText = strtolower($validated['message']);
    foreach ($foulWords as $word) {
        if (preg_match('/\b' . preg_quote($word, '/') . '\b/i', $lowerText)) {
            $isFlagged = true;
            break;
        }
    }
    $validated['is_flagged'] = $isFlagged;

    // Search for existing message matching name, email, or mobile number to continue the thread
    $existing = ContactMessage::where(function ($q) use ($validated) {
        if (!empty($validated['email'])) {
            $q->where('email', $validated['email']);
        }
        $q->orWhere('mobile_number', $validated['mobile_number'])
          ->orWhere('name', $validated['name']);
    })
    ->orderBy('created_at', 'desc')
    ->first();

    if ($existing) {
        \App\Models\ContactMessageReply::create([
            'contact_message_id' => $existing->id,
            'user_id' => null, // null denotes visitor/customer reply
            'message' => $validated['message'],
            'is_flagged' => $isFlagged,
        ]);

        // Reopen thread to pending and update flagged status if message was foul
        $existing->status = 'Pending';
        if ($isFlagged) {
            $existing->is_flagged = true;
        }
        $existing->save();
    } else {
        ContactMessage::create($validated);
    }
    
    return back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
})->name('contact.submit');

Route::post('/contact/thread/lookup', [\App\Http\Controllers\ContactMessageController::class, 'lookupThread'])->name('contact-messages.lookup');
Route::get('/contact/thread/{contact_message}', [\App\Http\Controllers\ContactMessageController::class, 'guestShow'])->name('contact-messages.guest-show');
Route::post('/contact/thread/{contact_message}/reply', [\App\Http\Controllers\ContactMessageController::class, 'guestReply'])->name('contact-messages.guest-reply');
Route::post('/contact/thread/{contact_message}/resolve', [\App\Http\Controllers\ContactMessageController::class, 'guestResolve'])->name('contact-messages.guest-resolve');

Route::get('/dashboard', function () {
    $schedules = \App\Models\CalendarEvent::orderBy('date', 'asc')->get();
    $announcements = \App\Models\Announcement::orderBy('created_at', 'desc')->get();
    $studentBoard = \App\Models\StudentBoardPost::orderBy('created_at', 'desc')->get();
    
    $user = auth()->user();
    $isStudentOrParent = $user && ($user->isStudent() || $user->isParentGuardian());
    
    $activityLogQuery = \App\Models\ActivityLog::query();
    if ($isStudentOrParent) {
        $activityLogQuery->where('user_id', $user->id);
    }
    
    $stats = [
        'users' => [
            'total' => \App\Models\User::count(),
            'active' => \App\Models\User::count(),
            'archived' => 0,
        ],
        'careers' => [
            'total' => \App\Models\Career::count(),
            'active' => \App\Models\Career::count(),
            'archived' => 0,
        ],
        'id_cards' => [
            'total' => \App\Models\IdCard::count(),
            'active' => \App\Models\IdCard::where('status', '!=', 'archived')->count(),
            'archived' => \App\Models\IdCard::where('status', 'archived')->count(),
        ],
        'contact_messages' => [
            'total' => \App\Models\ContactMessage::count(),
            'pending' => \App\Models\ContactMessage::whereIn('status', ['Pending', 'Replied'])->count(),
            'resolved' => \App\Models\ContactMessage::where('status', 'Resolved')->count(),
            'archived' => \App\Models\ContactMessage::where('status', 'Archived')->count(),
        ],
        'announcements' => [
            'total' => \App\Models\Announcement::count(),
            'active' => \App\Models\Announcement::count(),
            'archived' => 0,
        ],
        'calendar_events' => [
            'total' => \App\Models\CalendarEvent::count(),
            'active' => \App\Models\CalendarEvent::count(),
            'archived' => 0,
        ],
        'student_board_posts' => [
            'total' => \App\Models\StudentBoardPost::count(),
            'active' => \App\Models\StudentBoardPost::count(),
            'archived' => 0,
        ],
        'activity_logs' => [
            'total' => $activityLogQuery->count(),
            'active' => $activityLogQuery->count(),
            'archived' => 0,
        ],
        'admissions' => [
            'total' => \App\Models\Admission::count(),
            'pending' => \App\Models\Admission::where('status', 'pending')->count(),
            'approved' => \App\Models\Admission::where('status', 'approved')->count(),
            'rejected' => \App\Models\Admission::where('status', 'rejected')->count(),
        ],
    ];

    return view('dashboard.index', compact('schedules', 'announcements', 'studentBoard', 'stats'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Friendship Routes
    Route::post('/friendships', [\App\Http\Controllers\FriendshipController::class, 'store'])->name('friendships.store');
    Route::post('/friendships/{friendship}/accept', [\App\Http\Controllers\FriendshipController::class, 'accept'])->name('friendships.accept');
    Route::delete('/friendships/{friendship}', [\App\Http\Controllers\FriendshipController::class, 'destroy'])->name('friendships.destroy');

    // Direct Message Routes
    Route::post('/direct-messages/{friend}', [\App\Http\Controllers\DirectMessageController::class, 'send'])->name('direct-messages.send');

    // Group Chat Routes
    Route::post('/group-chats', [\App\Http\Controllers\GroupChatController::class, 'store'])->name('group-chats.store');
    Route::post('/group-chats/{groupChat}/send', [\App\Http\Controllers\GroupChatController::class, 'send'])->name('group-chats.send');

    Route::get('/activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/print', [\App\Http\Controllers\ActivityLogController::class, 'print'])->name('activity-logs.print');

    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');

    Route::resource('id-cards', \App\Http\Controllers\IdCardController::class);

    // Dynamic Public Info Modifications
    Route::get('/public-info-management', [\App\Http\Controllers\PublicInfoController::class, 'management'])->name('public-info-management');
    Route::post('/public-info/events', [\App\Http\Controllers\PublicInfoController::class, 'storeEvent'])->name('public-info.events.store');
    Route::put('/public-info/events/{event}', [\App\Http\Controllers\PublicInfoController::class, 'updateEvent'])->name('public-info.events.update');
    Route::delete('/public-info/events/{event}', [\App\Http\Controllers\PublicInfoController::class, 'destroyEvent'])->name('public-info.events.destroy');

    Route::post('/public-info/announcements', [\App\Http\Controllers\PublicInfoController::class, 'storeAnnouncement'])->name('public-info.announcements.store');
    Route::put('/public-info/announcements/{announcement}', [\App\Http\Controllers\PublicInfoController::class, 'updateAnnouncement'])->name('public-info.announcements.update');
    Route::delete('/public-info/announcements/{announcement}', [\App\Http\Controllers\PublicInfoController::class, 'destroyAnnouncement'])->name('public-info.announcements.destroy');
    Route::post('/public-info/announcements/{announcement}/vote', [\App\Http\Controllers\PublicInfoController::class, 'voteAnnouncement'])->name('public-info.announcements.vote');

    Route::post('/public-info/posts', [\App\Http\Controllers\PublicInfoController::class, 'storePost'])->name('public-info.posts.store');
    Route::put('/public-info/posts/{post}', [\App\Http\Controllers\PublicInfoController::class, 'updatePost'])->name('public-info.posts.update');
    Route::delete('/public-info/posts/{post}', [\App\Http\Controllers\PublicInfoController::class, 'destroyPost'])->name('public-info.posts.destroy');
    Route::post('/public-info/posts/{post}/vote', [\App\Http\Controllers\PublicInfoController::class, 'votePost'])->name('public-info.posts.vote');

    Route::post('/public-info/events/{event}/archive', [\App\Http\Controllers\PublicInfoController::class, 'archiveEvent'])->name('public-info.events.archive');
    Route::post('/public-info/events/{event}/restore', [\App\Http\Controllers\PublicInfoController::class, 'restoreEvent'])->name('public-info.events.restore');
    Route::post('/public-info/announcements/{announcement}/archive', [\App\Http\Controllers\PublicInfoController::class, 'archiveAnnouncement'])->name('public-info.announcements.archive');
    Route::post('/public-info/announcements/{announcement}/restore', [\App\Http\Controllers\PublicInfoController::class, 'restoreAnnouncement'])->name('public-info.announcements.restore');
    Route::post('/public-info/posts/{post}/archive', [\App\Http\Controllers\PublicInfoController::class, 'archivePost'])->name('public-info.posts.archive');
    Route::post('/public-info/posts/{post}/restore', [\App\Http\Controllers\PublicInfoController::class, 'restorePost'])->name('public-info.posts.restore');
    Route::post('/public-info/users/{user}/capabilities', [\App\Http\Controllers\PublicInfoController::class, 'updateCapabilities'])->name('public-info.users.capabilities');

    // Contact Inquiries & Replies
    Route::get('/contact-messages', [\App\Http\Controllers\ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('/contact-messages/{contact_message}', [\App\Http\Controllers\ContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::post('/contact-messages/{contact_message}/reply', [\App\Http\Controllers\ContactMessageController::class, 'reply'])->name('contact-messages.reply');
    Route::post('/contact-messages/{contact_message}/resolve', [\App\Http\Controllers\ContactMessageController::class, 'resolve'])->name('contact-messages.resolve');
    Route::post('/contact-messages/{contact_message}/archive', [\App\Http\Controllers\ContactMessageController::class, 'archive'])->name('contact-messages.archive');
    Route::post('/contact-messages/{contact_message}/restore', [\App\Http\Controllers\ContactMessageController::class, 'restore'])->name('contact-messages.restore');
    Route::delete('/contact-messages/{contact_message}', [\App\Http\Controllers\ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');

    // Teacher Deployment
    Route::post('/contact-messages/deploy', [\App\Http\Controllers\ContactMessageController::class, 'deployTeacher'])->name('contact-messages.deploy');
    Route::delete('/contact-messages/deploy/{teacherDeployment}', [\App\Http\Controllers\ContactMessageController::class, 'revokeTeacher'])->name('contact-messages.revoke');

    // Admissions Management
    Route::get('/dashboard/admissions', [\App\Http\Controllers\AdmissionController::class, 'adminIndex'])->name('admission.admin.index');
    Route::patch('/dashboard/admissions/{admission}/status/{status}', [\App\Http\Controllers\AdmissionController::class, 'adminUpdate'])->name('admission.admin.update');
    Route::patch('/dashboard/admissions/{admission}/requirements', [\App\Http\Controllers\AdmissionController::class, 'adminUpdateRequirements'])->name('admission.admin.update-requirements');
    Route::patch('/dashboard/admissions/{admission}/update-all', [\App\Http\Controllers\AdmissionController::class, 'adminUpdateAll'])->name('admission.admin.update-all');
});

require __DIR__.'/auth.php';
