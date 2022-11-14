<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
    ];

    public function batches()
    {
        return $this->belongsToMany(Batch::class, 'batch_teacher');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
