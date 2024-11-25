<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'course_id',
        'start_time',
        'place',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime',
    ];

    public function scopeActive($query)
    {
        $query->where('is_active', true);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function members()
    {
        return $this->belongsToMany(Member::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class)->withPivot('is_member');
    }
}
