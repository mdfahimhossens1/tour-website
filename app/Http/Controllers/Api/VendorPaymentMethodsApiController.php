<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransportBooking;
use App\Models\VendorPaymentMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VendorPaymentMethodsApiController extends Controller
{
    /**
     * Get Payment Methods for a Transport Booking
     */
    public function index(Request $request): JsonResponse
    {
        /*
        |------------------------------------------------------------------
        | Validate Request
        |------------------------------------------------------------------
        */

        $request->validate([
            'booking_id' => [
                'required',
                'integer',
                'exists:transport_bookings,id',
            ],
        ]);


        /*
        |------------------------------------------------------------------
        | Find Booking
        |------------------------------------------------------------------
        */

        $booking = TransportBooking::findOrFail(
            $request->integer('booking_id')
        );


        /*
        |------------------------------------------------------------------
        | Get Vendor Payment Methods
        |------------------------------------------------------------------
        |
        | Only:
        | - Current booking vendor
        | - Active methods
        | - Transport or All services
        |
        */

        $methods = VendorPaymentMethod::query()
            ->where('vendor_id', $booking->vendor_id)
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


        /*
        |------------------------------------------------------------------
        | Response
        |------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,
            'data' => $methods,
        ]);
    }
}