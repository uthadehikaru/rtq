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
        'school',
        'class',
        'level',
    ];

    public function batches()
    {
        return $this->belongsToMany(Batch::class);
    }

    public function batch()
    {
        return $this->batches->first();
    }

    public function batchName()
    {
        $batch = $this->batch();

        if ($batch) {
            return $batch->course->name.' '.__('Batch').' '.$batch->name;
        }
    }
}
