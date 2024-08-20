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
        'payment_method',
        'description',
    ];

    protected $casts = [
        'paid_at' => 'date:Y-m-d',
    ];

    public function details()
    {
        return $this->hasMany(PaymentDetail::class);
    }

    public function formattedAmount()
    {
        return 'Rp. '.number_format($this->amount, 0, ',', '.');
    }
}
