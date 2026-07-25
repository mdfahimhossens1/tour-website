<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResortWishlist extends Model
{
  protected $fillable=[

'user_id',

'resort_id'

];

public function user()
{
    return $this->belongsTo(User::class);
}

public function resort()
{
    return $this->belongsTo(Resort::class);
}
}
