<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Friendship;
use App\Models\DirectMessage;

#[Fillable(['name', 'username', 'email', 'mobile_number', 'password', 'users_level', 'can_delete_users', 'avatar', 'can_post_calendar', 'can_post_announcement', 'can_post_student_board', 'can_manage_admissions'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function groupChats()
    {
        return $this->belongsToMany(GroupChat::class, 'group_chat_members', 'user_id', 'group_chat_id');
    }

    /**
     * Get the user's accepted friends.
     */
    public function friends()
    {
        $sent = Friendship::where('user_id', $this->id)->where('status', 'accepted')->pluck('friend_id')->toArray();
        $received = Friendship::where('friend_id', $this->id)->where('status', 'accepted')->pluck('user_id')->toArray();
        return User::whereIn('id', array_merge($sent, $received))->get();
    }

    /**
     * Get pending friendship requests sent by this user.
     */
    public function pendingOutgoingRequests()
    {
        return Friendship::where('user_id', $this->id)->where('status', 'pending')->with('friend')->get();
    }

    /**
     * Get pending friendship requests received by this user.
     */
    public function pendingIncomingRequests()
    {
        return Friendship::where('friend_id', $this->id)->where('status', 'pending')->with('user')->get();
    }

    /**
     * Check if this user is friends with another user.
     */
    public function isFriendsWith(User $other): bool
    {
        return Friendship::where(function ($query) use ($other) {
            $query->where('user_id', $this->id)->where('friend_id', $other->id);
        })->orWhere(function ($query) use ($other) {
            $query->where('user_id', $other->id)->where('friend_id', $this->id);
        })->where('status', 'accepted')->exists();
    }


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'users_level' => 'integer',
            'can_delete_users' => 'boolean',
            'can_post_calendar' => 'boolean',
            'can_post_announcement' => 'boolean',
            'can_post_student_board' => 'boolean',
            'can_manage_admissions' => 'boolean',
        ];
    }

    public function isDeveloper(): bool
    {
        return $this->users_level === 0;
    }

    public function isSchoolAdmin(): bool
    {
        return $this->users_level === 1;
    }

    public function isTeacher(): bool
    {
        return $this->users_level === 2;
    }

    public function isStudent(): bool
    {
        return $this->users_level === 3;
    }

    public function isParentGuardian(): bool
    {
        return $this->users_level === 4;
    }

    public function canDeleteAccount(): bool
    {
        return in_array($this->users_level, [0, 1]);
    }

    public function canDeleteOtherUsers(): bool
    {
        if (in_array($this->users_level, [0, 1])) {
            return true;
        }
        return $this->users_level === 2 && $this->can_delete_users;
    }

    public function getRoleNameAttribute(): string
    {
        return match ($this->users_level) {
            0 => 'Developer',
            1 => 'School Admin',
            2 => 'Teacher',
            3 => 'Student',
            4 => 'Parent/Guardian',
            default => 'Unknown',
        };
    }
}
