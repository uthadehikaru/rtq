<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Present extends Model
{
    use HasFactory;

    public const STATUSES = ['present', 'absent', 'sick', 'permit'];

    public const STATUS_PRESENT = 'present';

    public const STATUS_ABSENT = 'absent';

    public const STATUS_SICK = 'sick';

    public const STATUS_permit = 'permit';

    protected $fillable = [
        'schedule_id',
        'type',
        'user_id',
        'attended_at',
        'leave_at',
        'status',
        'description',
        'salary_id',
        'is_badal',
        'photo',
        'photo_out',
        'is_transfer',
    ];

    protected $casts = [
        'attended_at' => 'datetime',
        'leave_at' => 'datetime',
    ];

    public function scopeMember($query)
    {
        return $query->where('type', 'member');
    }

    public function scopeTeacher($query)
    {
        return $query->where('type', 'teacher');
    }

    public function scopePresent($query)
    {
        return $query->where('status', self::STATUS_PRESENT);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function name()
    {
        return $this->user?->name;
    }
}
