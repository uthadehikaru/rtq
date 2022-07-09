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
        return $this->hasMany(Batch::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
