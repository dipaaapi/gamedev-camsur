<?php

namespace App\Http\Controllers;

use App\Models\GroupChat;
use App\Models\GroupChatMessage;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupChatController extends Controller
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
     * Create a new Group Chat.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        $request->validate([
            'group_name' => 'required|string|max:255',
            'members' => 'required|array|min:1',
            'members.*' => 'exists:users,id'
        ]);

        $groupChat = GroupChat::create([
            'name' => $request->group_name,
            'created_by' => $user->id,
        ]);

        // Attach creator + all selected friends
        $members = array_unique(array_merge([$user->id], $request->members));
        $groupChat->members()->attach($members);

        ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'action' => 'Create Group Chat',
            'description' => "Created group chat '{$groupChat->name}' with " . count($members) . " members.",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('profile.edit', ['group_chat_id' => $groupChat->id, 'tab' => 'messages'])
            ->with('success', "Group chat '{$groupChat->name}' created successfully!");
    }

    /**
     * Send a message inside a Group Chat.
     */
    public function send(Request $request, GroupChat $groupChat)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        // Verify membership
        if (!$groupChat->members()->where('users.id', $user->id)->exists()) {
            abort(403);
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

        GroupChatMessage::create([
            'group_chat_id' => $groupChat->id,
            'sender_id' => $user->id,
            'message' => $messageText,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'is_flagged' => $isFlagged,
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'action' => 'Send Group Chat Message',
            'description' => "Sent a message in group '{$groupChat->name}'." . ($isFlagged ? " [FLAGGED FOR PROFANITY]" : ""),
            'ip_address' => $request->ip(),
        ]);

        if ($isFlagged) {
            return redirect()->route('profile.edit', ['group_chat_id' => $groupChat->id, 'tab' => 'messages'])
                ->with('warning', 'Your message was sent but flagged for inappropriate language.');
        }

        return redirect()->route('profile.edit', ['group_chat_id' => $groupChat->id, 'tab' => 'messages'])
            ->with('success', 'Message sent successfully.');
    }
}
