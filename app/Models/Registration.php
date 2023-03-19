<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    use HasFactory;

    public const OPTIONS = [
        'weekday' => 'Hari Biasa (Senin-Jum\'at)',
        'weekend' => 'Hari Libur (Sabtu-Ahad)',
    ];

    protected $fillable = [
        'registration_no',
        'nik',
        'full_name',
        'short_name',
        'birth_place',
        'birth_date',
        'birth_place',
        'gender',
        'address',
        'phone',
        'email',
        'type',
        'reference',
        'schedule_option',
        'activity',
        'school_start_time',
        'school_end_time',
        'school_level',
        'class',
        'school_name',
        'start_school',
        'father_name',
        'mother_name',
        'reference_schedule',
        'user_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'school_start_time' => 'datetime',
        'school_end_time' => 'datetime',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function scheduleOption(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? self::OPTIONS[$value] : '',
        );
    }
}
