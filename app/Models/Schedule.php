<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'scheduled_at',
        'batch_id',
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function presents()
    {
        return $this->hasMany(Present::class);
    }
}
