<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'title',
        'excerpt',
        'content',
        'author',
        'poll_question',
        'poll_options',
        'poll_votes',
        'media',
        'is_archived',
    ];

    protected $casts = [
        'poll_options' => 'array',
        'poll_votes' => 'array',
        'media' => 'array',
        'is_archived' => 'boolean',
    ];
}
