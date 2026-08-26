<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;

class PaymentMethodsApiController extends Controller
{
    /**
     * Get all active admin payment methods
     */
    public function index()
    {
        $methods = PaymentMethod::where('status', 1)
            ->latest()
            ->get([
                'id',
                'name',
                'type',
                'account_number',
                'description',
            ]);

        return response()->json([
            'success' => true,
            'data' => $methods,
        ]);
    }
}