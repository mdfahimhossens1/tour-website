<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'image',
        'short_description',
        'description',
        'meta_title',
        'meta_description',
        'status',
        'blog_category_id'
    ];

    public function category(){
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }
}