<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FAQCategory extends Model
{
    use HasFactory;

    protected $table = 'faq_categories';

    protected $fillable = [
        'name',
        'slug',
        'status',
        'serial',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

public function faqs()
{
    return $this->hasMany(FAQ::class, 'faq_category_id', 'id');
}
}