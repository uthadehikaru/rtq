<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'approved_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function details()
    {
        return $this->hasMany(SalaryDetail::class);
    }

    public function period()
    {
        return $this->start_date->format('d M Y').' - '.$this->end_date->format('d M Y');
    }
}
