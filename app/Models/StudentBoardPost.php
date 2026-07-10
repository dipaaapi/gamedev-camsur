<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentBoardPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'date',
        'title',
        'content',
        'author',
        'author_initials',
        'user_id',
        'poll_question',
        'poll_options',
        'poll_votes',
        'is_spotlight',
        'media',
        'is_archived',
    ];

    protected $casts = [
        'poll_options' => 'array',
        'poll_votes' => 'array',
        'is_spotlight' => 'boolean',
        'media' => 'array',
        'is_archived' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
