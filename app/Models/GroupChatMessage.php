<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupChatMessage extends Model
{
    use HasFactory;

    protected $fillable = ['group_chat_id', 'sender_id', 'message', 'attachment_path', 'attachment_name', 'is_flagged'];

    public function groupChat()
    {
        return $this->belongsTo(GroupChat::class, 'group_chat_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
