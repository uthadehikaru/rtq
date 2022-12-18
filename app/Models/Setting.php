<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'name',
        'payload',
    ];

    protected $casts = [
        'payload' => 'json',
    ];

    public function scopeGroup($query, $group)
    {
        return $query->where('group',$group);
    }
}
