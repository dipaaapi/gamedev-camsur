<?php

namespace App\Http\Controllers;

use App\Models\DirectMessage;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectMessageController extends Controller
{
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
     * Send a message to a friend.
     */
    public function send(Request $request, User $friend)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        // Verify friendship exists and is accepted
        if (!$user->isFriendsWith($friend)) {
            return back()->withErrors(['message' => 'You can only message users on your friends list.']);
        }

        $request->validate([
            'message' => 'required|string|max:5000',
            'attachment' => 'nullable|file|max:15360', // max 15MB
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

        $dm = DirectMessage::create([
            'sender_id' => $user->id,
            'receiver_id' => $friend->id,
            'message' => $messageText,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'is_flagged' => $isFlagged,
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'action' => 'Send Direct Message',
            'description' => "Sent an internal message to {$friend->name} ({$friend->username})." . ($isFlagged ? " [FLAGGED FOR PROFANITY]" : ""),
            'ip_address' => $request->ip(),
        ]);

        if ($isFlagged) {
            return redirect()->route('profile.edit', ['chat_with' => $friend->id, 'tab' => 'messages'])
                ->with('warning', 'Your message was sent but flagged for containing inappropriate language.');
        }

        return redirect()->route('profile.edit', ['chat_with' => $friend->id, 'tab' => 'messages'])
            ->with('success', 'Message sent successfully.');
    }
}
