<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Present extends Model
{
    use HasFactory;

    public const STATUSES = ['present', 'absent','sick','permit'];

    public const STATUS_PRESENT = 'present';
    public const STATUS_ABSENT = 'absent';
    public const STATUS_SICK = 'sick';
    public const STATUS_permit = 'permit';

    protected $fillable = [
        'schedule_id',
        'teacher_id',
        'member_id',
        'attended_at',
        'status',
        'description',
        'salary_id',
    ];

    protected $casts = [
        'attended_at' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function name()
    {
        $name = '';
        if ($this->member_id) {
            $name = $this->member->full_name;
        } elseif ($this->teacher_id) {
            $name = $this->teacher->name;
        }

        return $name;
    }
}
