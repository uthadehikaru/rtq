<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_date',
        'description',
        'debit',
        'credit',
    ];

    protected $casts = [
        'transaction_date'=>'date:d M Y',
    ];

    public function nominal()
    {
        return $this->debit>0?$this->debit:$this->credit;
    }
}
