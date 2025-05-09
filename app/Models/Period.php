<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date:d M Y',
        'end_date' => 'date:d M Y',
    ];

    public function paymentDetails()
    {
        return $this->hasMany(PaymentDetail::class);
    }
}
