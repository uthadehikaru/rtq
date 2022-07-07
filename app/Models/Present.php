<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Present extends Model
{
    use HasFactory;

    public const STATUSES = ['present','absent','sick','permit'];

    protected $fillable = [
        'schedule_id',
        'teacher_id',
        'member_id',
        'attended_at',
        'status',
        'description',
    ];
    
    protected $casts = [
        'scheduled_at' => 'datetime',
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
}
