<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendshipController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        $request->validate([
            'search_query' => 'required|string|max:255',
        ]);

        $query = $request->search_query;

        // Search for user by username, email, mobile_number, or name
        $friend = User::where('username', $query)
            ->orWhere('email', $query)
            ->orWhere('mobile_number', $query)
            ->orWhere('name', 'like', "%{$query}%")
            ->first();

        if (!$friend) {
            return back()->withErrors(['search_query' => 'User not found. Check the username or email address.']);
        }

        if ($friend->id === $user->id) {
            return back()->withErrors(['search_query' => 'You cannot add yourself as a friend.']);
        }

        // Check if there is an existing friendship
        $existing = Friendship::where(function ($q) use ($user, $friend) {
            $q->where('user_id', $user->id)->where('friend_id', $friend->id);
        })->orWhere(function ($q) use ($user, $friend) {
            $q->where('user_id', $friend->id)->where('friend_id', $user->id);
        })->first();

        if ($existing) {
            if ($existing->status === 'accepted') {
                return back()->withErrors(['search_query' => 'You are already friends with this user.']);
            }

            // If the incoming request is pending, accept it
            if ($existing->friend_id === $user->id && $existing->status === 'pending') {
                $existing->status = 'accepted';
                $existing->save();

                ActivityLog::create([
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'action' => 'Accept Friend Request',
                    'description' => "Accepted friend request from {$friend->name} ({$friend->username}).",
                    'ip_address' => $request->ip(),
                ]);

                return back()->with('success', "Friend request accepted! You are now friends with {$friend->name}.");
            }

            // Outgoing request pending
            return back()->withErrors(['search_query' => 'A pending friend request already exists.']);
        }

        // Create a new pending friendship request
        Friendship::create([
            'user_id' => $user->id,
            'friend_id' => $friend->id,
            'status' => 'pending',
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'action' => 'Send Friend Request',
            'description' => "Sent a friend request to {$friend->name} ({$friend->username}).",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Friend request sent to {$friend->name}!");
    }

    public function accept(Friendship $friendship, Request $request)
    {
        $user = Auth::user();
        if (!$user || $friendship->friend_id !== $user->id) {
            abort(403);
        }

        $friendship->status = 'accepted';
        $friendship->save();

        $sender = $friendship->user;

        ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'action' => 'Accept Friend Request',
            'description' => "Accepted friend request from {$sender->name} ({$sender->username}).",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "You are now friends with {$sender->name}!");
    }

    public function destroy(Friendship $friendship, Request $request)
    {
        $user = Auth::user();
        if (!$user || ($friendship->user_id !== $user->id && $friendship->friend_id !== $user->id)) {
            abort(403);
        }

        $otherUser = $friendship->user_id === $user->id ? $friendship->friend : $friendship->user;
        $friendship->delete();

        ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'action' => 'Remove Friendship',
            'description' => "Cancelled or removed friendship with {$otherUser->name} ({$otherUser->username}).",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Friendship request or connection removed.');
    }
}
