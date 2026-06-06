<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'type',
        'account_number',
        'api_key',
        'secret_key',
        'status',
        'description',
    ];
}
