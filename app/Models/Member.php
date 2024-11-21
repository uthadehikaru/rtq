<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'short_name',
        'email',
        'phone',
        'gender',
        'address',
        'postcode',
        'school',
        'class',
        'level',
        'user_id',
        'registration_date',
        'status',
        'nik',
        'birth_date',
        'profile_picture',
        'member_no',
        'leave_at',
        'is_acceleration',
    ];

    protected $casts = [
        'registration_date' => 'date',
        'birth_date' => 'date',
        'leave_at' => 'date',
    ];

    public function batches()
    {
        return $this->belongsToMany(Batch::class);
    }

    public function paymentDetails()
    {
        return $this->hasMany(PaymentDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function batch()
    {
        return $this->batches->first();
    }

    public function batchName()
    {
        $batch = $this->batch();

        if ($batch) {
            return $batch->course->name.' '.__('Batch').' '.$batch->name;
        }
    }

    public function getCourseAttribute()
    {
        $batches = $this->batches->filter(function ($batch, $key) {
            return $batch->course->type != 'Talaqqi Jamai';
        });

        return $batches->first()?->course;
    }
}
