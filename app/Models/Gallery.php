<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'tour_id',
        'image',
        'type',
    ];

    // =========================

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
}