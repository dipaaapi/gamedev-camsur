<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupChat extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'created_by'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'group_chat_members', 'group_chat_id', 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(GroupChatMessage::class, 'group_chat_id');
    }
}
