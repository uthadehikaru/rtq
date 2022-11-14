<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'member_id',
        'batch_id',
        'period_id',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}
