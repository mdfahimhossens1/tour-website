<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
      protected $fillable = [

        'name',

        'designation_en',
        'designation_bn',

        'image',

        'email',
        'phone',

        'facebook',
        'linkedin',

        'bio_en',
        'bio_bn',

        'status',

        'sort_order',

    ];

    protected $casts = [

        'status' => 'boolean',

    ];
}
