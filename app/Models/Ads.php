<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    protected $table = 'ads';

    /**
     * Fields that can be mass assigned.
     */
    protected $fillable = [
        'title',
        'image',
        'link',
        'position',
        'views',
        'clicks',
        'start_date',
        'end_date',
        'status',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'boolean',
        'views' => 'integer',
        'clicks' => 'integer',
    ];
}