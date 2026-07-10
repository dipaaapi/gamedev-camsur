<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_number',
        'full_name',
        'email',
        'phone',
        'course',
        'score',
        'requirements',
        'status',
    ];

    protected $casts = [
        'requirements' => 'array',
        'score' => 'integer',
    ];
}
