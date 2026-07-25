<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResortImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'resort_id',
        'image',
        'is_cover',
        'sort_order',
    ];

    public function resort()
    {
        return $this->belongsTo(Resort::class);
    }
}