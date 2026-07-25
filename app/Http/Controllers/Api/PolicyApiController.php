<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Policy;

class PolicyApiController extends Controller
{
    public function index()
    {
        $policies = Policy::where('status', 1)
            ->select(
                'type',
                'title_en',
                'title_bn',
                'content_en',
                'content_bn'
            )
            ->orderBy('id')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $policies
        ]);
    }
}