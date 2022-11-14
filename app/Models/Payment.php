<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'paid_at',
        'attachment',
        'status',
    ];

    public function details()
    {
        return $this->hasMany(PaymentDetail::class);
    }
}
