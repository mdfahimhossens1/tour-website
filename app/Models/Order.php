<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
    'user_id','coupon_id','order_number','status',
    'subtotal','discount','shipping','tax','grand_total',
    'payment_method','payment_status',
    'customer_name','customer_phone','customer_email','shipping_address'
  ];

  public function items(){
    return $this->hasMany(OrderItem::class, 'order_id');
  }

  public function user(){
    return $this->belongsTo(User::class);
  }

  public function coupon(){
    return $this->belongsTo(Coupon::class);
  }
}
