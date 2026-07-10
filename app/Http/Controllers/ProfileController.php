<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $friends = $user->friends();
        $pendingOutgoing = $user->pendingOutgoingRequests();
        $pendingIncoming = $user->pendingIncomingRequests();

        $chatWithUser = null;
        $chatMessages = collect();

        if ($request->filled('chat_with')) {
            $friendId = $request->chat_with;
            $friend = \App\Models\User::find($friendId);
            if ($friend && $user->isFriendsWith($friend)) {
                $chatWithUser = $friend;
                
                // Mark received messages from this friend as read
                \App\Models\DirectMessage::where('sender_id', $friend->id)
                    ->where('receiver_id', $user->id)
                    ->where('is_read', false)
                    ->update(['is_read' => true]);

                $chatMessages = \App\Models\DirectMessage::where(function ($q) use ($user, $friend) {
                    $q->where('sender_id', $user->id)->where('receiver_id', $friend->id);
                })->orWhere(function ($q) use ($user, $friend) {
                    $q->where('sender_id', $friend->id)->where('receiver_id', $user->id);
                })->orderBy('created_at', 'asc')->get();
            }
        }

        $groupChats = $user->groupChats()->get();
        $activeGroupChat = null;
        $groupChatMessages = collect();

        if ($request->filled('group_chat_id')) {
            $gId = $request->group_chat_id;
            $gChat = \App\Models\GroupChat::find($gId);
            if ($gChat && $gChat->members()->where('users.id', $user->id)->exists()) {
                $activeGroupChat = $gChat;
                $groupChatMessages = $gChat->messages()->with('sender')->orderBy('created_at', 'asc')->get();
            }
        }

        return view('profile.edit', compact(
            'user',
            'friends',
            'pendingOutgoing',
            'pendingIncoming',
            'chatWithUser',
            'chatMessages',
            'groupChats',
            'activeGroupChat',
            'groupChatMessages'
        ));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        // Handle Avatar Upload/Capture
        if ($request->filled('avatar_base64')) {
            $data = $request->avatar_base64;
            if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                $data = substr($data, strpos($data, ',') + 1);
                $type = strtolower($type[1]); // png, jpg, jpeg, gif
                if (in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $data = base64_decode($data);
                    if ($data !== false) {
                        $filename = 'avatar_' . uniqid() . '.' . $type;
                        \Illuminate\Support\Facades\Storage::disk('public')->put('avatars/' . $filename, $data);
                        
                        // Delete old avatar
                        if ($user->avatar) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
                        }
                        $user->avatar = 'avatars/' . $filename;
                    }
                }
            }
        } elseif ($request->hasFile('avatar_file')) {
            $file = $request->file('avatar_file');
            $filename = 'avatar_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('avatars', $filename, 'public');
            
            // Delete old avatar
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $path;
        }

        $isDirty = $user->isDirty();
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($isDirty) {
            \App\Models\ActivityLog::create([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'action' => 'Profile Updated',
                'description' => "User profile information updated.",
                'ip_address' => $request->ip(),
            ]);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        abort(403, 'Account self-deletion is disabled. Account deletion must be processed through the User Management dashboard by an administrator.');
    }
}
