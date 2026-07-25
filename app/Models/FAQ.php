<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FAQ extends Model
{
    use HasFactory;

    protected $table = 'faqs';

    protected $fillable = [
        'faq_category_id',
        'question',
        'answer',
        'status',
        'serial',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

public function category()
{
    return $this->belongsTo(FAQCategory::class, 'faq_category_id', 'id');
}
}