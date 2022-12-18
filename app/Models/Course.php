<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    public const TYPES = [
        'Tahsin Anak',
        'Tahsin Dewasa',
        'Tahsin Balita',
    ];

    protected $fillable = [
        'name',
        'type',
        'fee',
    ];

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}
