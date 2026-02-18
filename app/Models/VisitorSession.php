<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorSession extends Model
{
    protected $table = 'visitor_sessions';

    protected $fillable = [
        'user_id','ip_address','country_code','country_name','city',
        'last_url','last_seen_at',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
    ];
}
