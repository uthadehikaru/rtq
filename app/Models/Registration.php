<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_no',
        'full_name',
        'short_name',
        'birth_place',
        'birth_date',
        'birth_place',
        'gender',
        'address',
        'phone',
        'email',
        'type',
        'reference',
        'schedule_option',
        'activity',
        'school_start_time',
        'school_end_time',
        'school_level',
        'class',
        'school_name',
        'start_school',
        'father_name',
        'mother_name',
        'reference_schedule',
        'user_id',
    ];

    protected $casts = [
        'birth_date'=>'date',
        'school_start_time'=>'datetime',
        'school_end_time'=>'datetime',
    ];
}
