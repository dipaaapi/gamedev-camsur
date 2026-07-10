<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'type',
        'time',
        'title',
        'description',
        'media',
        'is_archived',
    ];

    protected $casts = [
        'date' => 'date',
        'media' => 'array',
        'is_archived' => 'boolean',
    ];
}
