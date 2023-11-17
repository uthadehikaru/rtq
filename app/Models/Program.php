<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use QCod\ImageUp\HasImageUploads;

class Program extends Model
{
    use HasFactory, HasImageUploads;

    protected $fillable = [
        'title',
        'slug',
        'thumbnail',
        'amount',
        'qty',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static $imageFields = [
        'thumbnail' => [
            'width' => 600,
            'height' => 600,
            'crop' => false,
            'path' => 'programs',
            'placeholder' => '/assets/images/donasi.jpg',
            'rules' => 'image|max:2000',
        ],
    ];
}
