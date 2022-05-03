<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'short_name',
        'email',
        'phone',
        'gender',
        'address',
        'postcode',
    ];
    
    public function batches()
    {
        return $this->belongsToMany(Batch::class);
    }
}
