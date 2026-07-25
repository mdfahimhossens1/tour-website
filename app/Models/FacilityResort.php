<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityResort extends Model
{
    use HasFactory;

    protected $table = 'facility_resort';

    protected $fillable = [
        'facility_id',
        'resort_id',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function resort()
    {
        return $this->belongsTo(Resort::class);
    }
}