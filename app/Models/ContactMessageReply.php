<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactMessageReply extends Model
{
    protected $table = 'contact_message_replies';

    protected $fillable = ['contact_message_id', 'user_id', 'message', 'is_flagged', 'attachment_path', 'attachment_name', 'parent_reply_id', 'reply_to_original', 'reply_mode'];

    public function contactMessage(): BelongsTo
    {
        return $this->belongsTo(ContactMessage::class, 'contact_message_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parentReply(): BelongsTo
    {
        return $this->belongsTo(ContactMessageReply::class, 'parent_reply_id');
    }

    public function childReplies(): HasMany
    {
        return $this->hasMany(ContactMessageReply::class, 'parent_reply_id');
    }
}
