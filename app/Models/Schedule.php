<?php

namespace App\Models;

use App\Services\BatchService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'scheduled_at',
        'batch_id',
        'start_at',
        'end_at',
        'place',
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

    public function teachers(): Collection
    {
        $teachers = $this->presents->filter(function ($present, $key) {
            return $present->type == 'teacher';
        });

        $users = $teachers->pluck('user_id');

        return User::whereIn('id', $users)->get();
    }

    public function getSizeTypeAttribute()
    {
        return (new BatchService)->getSizeType($this->batch->course->type, $this->presents()->member()->count());
    }
}
