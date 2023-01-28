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
        'status',
        'description',
        'salary_id',
        'is_badal',
        'photo',
        'is_transfer',
    ];

    protected $casts = [
        'attended_at' => 'datetime',
    ];

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
