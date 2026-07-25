<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FAQ;

class FAQApiController extends Controller
{
   public function index()
{
    $faqs = FAQ::with('category')
        ->where('status', 1)
        ->orderBy('serial')
        ->get();

    return response()->json([
        'data' => $faqs->map(function ($faq) {
            return [
                'id' => $faq->id,
                'question' => $faq->question,
                'answer' => $faq->answer,
                'status' => $faq->status,
                'serial' => $faq->serial,
                'created_at' => $faq->created_at,
                'updated_at' => $faq->updated_at,
                'category' => $faq->category ? [
                    'id' => $faq->category->id,
                    'name' => $faq->category->name,
                    'slug' => $faq->category->slug,
                ] : null,
            ];
        }),
    ]);
}
}