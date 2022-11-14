<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Present extends Model
{
    use HasFactory;

    public const STATUSES = ['present', 'absent', 'sick', 'permit'];

    protected $fillable = [
        'schedule_id',
        'type',
        'user_id',
        'attended_at',
        'status',
        'description',
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
