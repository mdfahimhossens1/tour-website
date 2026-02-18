<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
      protected $fillable = [
    'category_id','name','slug','sku','short_description','description',
    'price','sale_price','stock','low_stock_threshold','featured_image','is_active', 'image'
  ];

  public function category(){
    return $this->belongsTo(Category::class, 'category_id');
  }
}
