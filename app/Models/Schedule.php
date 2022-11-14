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
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
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
