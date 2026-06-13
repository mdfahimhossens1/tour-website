<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'vendor_id',
        'amount',
        'method',
        'account_details',
        'status'
    ];
}
