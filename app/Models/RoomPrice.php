<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomPrice extends Model
{
    protected $fillable = [
        'room_id',
        'from_date',
        'to_date',
        'price',
        'discount_type',
        'discount_value',
        'type',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'price' => 'decimal:2',
        'discount_value' => 'decimal:2',
    ];

    protected $appends = [
        'final_price',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Calculate final price after discount.
     */
    public function getFinalPriceAttribute()
    {
        $price = (float) $this->price;
        $discount = (float) ($this->discount_value ?? 0);

        if (!$this->discount_type || $discount <= 0) {
            return $price;
        }

        if ($this->discount_type === 'percentage') {
            return max(0, $price - ($price * $discount / 100));
        }

        if ($this->discount_type === 'amount') {
            return max(0, $price - $discount);
        }

        return $price;
    }
}