<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function details()
    {
        return $this->hasMany(PaymentDetail::class);
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => 'Rp. '.number_format($value, 0, ',', '.'),
        );
    }
}
