<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Violation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'violated_date',
        'description',
        'amount',
        'paid_at',
        'type',
    ];

    protected $casts = [
        'violated_date' => 'date',
        'paid_at' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
