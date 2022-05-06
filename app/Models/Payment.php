<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_id',
        'batch_id',
        'member_id',
        'amount',
        'paid_at',
        'attachment',
        'status',
    ];
    
    public function period()
    {
        return $this->belongsTo(Period::class);
    }
    
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
    
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
