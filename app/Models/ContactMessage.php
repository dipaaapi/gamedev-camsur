<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ContactMessage extends Model
{
    use Notifiable;

    protected $fillable = ['name', 'pronouns', 'mobile_number', 'email', 'purpose', 'message', 'status', 'rating', 'resolution_comment', 'is_flagged'];

    public function replies()
    {
        return $this->hasMany(ContactMessageReply::class, 'contact_message_id')->orderBy('created_at', 'asc');
    }

    public function canBeManagedBy($user): bool
    {
        if (!$user) return false;
        if ($user->users_level === 0 || $user->users_level === 1) return true;
        if ($user->users_level === 2) {
            return TeacherDeployment::where('user_id', $user->id)
                ->where('purpose', $this->purpose)
                ->exists();
        }
        return false;
    }
}
