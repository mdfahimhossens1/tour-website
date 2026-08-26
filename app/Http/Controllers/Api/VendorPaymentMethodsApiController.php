<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VendorPaymentMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VendorPaymentMethodsApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $vendorId = $request->integer('vendor_id');

        $methods = VendorPaymentMethod::query()
            ->active()
            ->when(
                $vendorId,
                function ($query) use ($vendorId) {
                    $query->where(
                        'vendor_id',
                        $vendorId
                    );
                }
            )
            ->orderBy('id')
            ->get([
                'id',
                'vendor_id',
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