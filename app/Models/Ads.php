<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    protected $table = 'ads';

    protected $fillable = [
        'title',
        'image',
        'link',
        'position',
        'views',
        'clicks',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
