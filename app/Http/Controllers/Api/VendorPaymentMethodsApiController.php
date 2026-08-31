<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Vehicle;
use App\Models\VendorPaymentMethod;
use Illuminate\Http\JsonResponse;

class VendorPaymentMethodsApiController extends Controller
{
    /**
     * ============================================================
     * Transport / Vehicle Vendor Payment Methods
     * ============================================================
     */
    public function transport(Vehicle $vehicle): JsonResponse
    {
        $vendorId = $vehicle->vendor_id;

        if (!$vendorId) {
            return response()->json([
                'success' => true,
                'message' => 'No vendor payment methods available.',
                'data' => [],
            ]);
        }

        $methods = VendorPaymentMethod::query()
            ->where('vendor_id', $vendorId)
            ->active()
            ->forService('transport')
            ->orderBy('id')
            ->get([
                'id',
                'vendor_id',
                'name',
                'type',
                'service_type',
                'account_number',
                'description',
            ]);

        return response()->json([
            'success' => true,
            'data' => $methods,
        ]);
    }


    /**
     * ============================================================
     * Room Vendor Payment Methods
     * ============================================================
     */
    public function room(Room $room): JsonResponse
    {
        /*
        |----------------------------------------------------------------------
        | Load Resort
        |----------------------------------------------------------------------
        */

        $room->load('resort');


        /*
        |----------------------------------------------------------------------
        | Check Resort
        |----------------------------------------------------------------------
        */

        if (!$room->resort) {
            return response()->json([
                'success' => false,
                'message' => 'This room is not associated with a resort.',
                'data' => [],
            ], 422);
        }


        /*
        |----------------------------------------------------------------------
        | Get Vendor
        |----------------------------------------------------------------------
        */

        $vendorId = $room->resort->vendor_id;


        if (!$vendorId) {
            return response()->json([
                'success' => true,
                'message' => 'No vendor payment methods available.',
                'data' => [],
            ]);
        }


        /*
        |----------------------------------------------------------------------
        | Get Room Payment Methods
        |----------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | Room booking-এর জন্য service_type হবে:
        |
        | room
        |
        | অথবা:
        |
        | all
        |
        */

        $methods = VendorPaymentMethod::query()
            ->where('vendor_id', $vendorId)
            ->active()
            ->forService('room')
            ->orderBy('id')
            ->get([
                'id',
                'vendor_id',
                'name',
                'type',
                'service_type',
                'account_number',
                'description',
            ]);


        /*
        |----------------------------------------------------------------------
        | Response
        |----------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,
            'data' => $methods,
        ]);
    }
}