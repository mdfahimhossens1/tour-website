<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryLog extends Model
{
   use HasFactory;

    protected $fillable = [
        'product_id', 'type', 'quantity', 'note'
    ];

    
    protected $casts = [
        'quantity' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
