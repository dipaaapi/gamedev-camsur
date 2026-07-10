<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_number',
        'first_name',
        'middle_name',
        'last_name',
        'dob',
        'permanent_address',
        'current_address',
        'gender',
        'age',
        'id_picture',
        'validity_date',
        'status',
        'class',
        'batch_number',
        'student_phone',
        'emergency_contact_name',
        'emergency_contact_phone',
    ];

    protected $casts = [
        'dob' => 'date',
        'validity_date' => 'date',
        'age' => 'integer',
    ];

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        $middle = '';
        if ($this->middle_name) {
            $initial = strtoupper(substr(trim($this->middle_name), 0, 1));
            if ($initial) {
                $middle = ' ' . $initial . '.';
            }
        }
        return $this->first_name . $middle . ' ' . $this->last_name;
    }

    /**
     * Boot function to automatically generate ID Number.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_number)) {
                $year = $model->validity_date ? $model->validity_date->format('Y') : date('Y');
                
                // Fetch the latest generated ID number for the same year to compute next sequence
                $latest = static::where('id_number', 'like', "GDC-{$year}-%")
                    ->orderBy('id_number', 'desc')
                    ->first();

                if ($latest) {
                    $parts = explode('-', $latest->id_number);
                    $seq = intval(end($parts)) + 1;
                } else {
                    $seq = 1;
                }

                $model->id_number = 'GDC-' . $year . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
