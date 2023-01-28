<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'salary_id',
        'user_id',
        'amount',
        'summary',
    ];

    protected $casts = [
        'summary' => 'array',
    ];

    public function salary()
    {
        return $this->belongsTo(Salary::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
