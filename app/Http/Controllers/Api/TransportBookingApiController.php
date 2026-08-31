<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransportBooking;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransportBookingApiController extends Controller
{
    /**
     * ----------------------------------------------------------
     * Create Transport Booking
     * ----------------------------------------------------------
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'vehicle_id' => [
                'required',
                'exists:vehicles,id',
            ],

            'start_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],

            'passengers' => [
                'required',
                'integer',
                'min:1',
            ],

            'pickup_location' => [
                'required',
                'string',
                'max:1000',
            ],

            'dropoff_location' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'special_request' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Vehicle & Vendor
        |--------------------------------------------------------------------------
        */

        $vehicle = Vehicle::approved()
            ->with('vendor')
            ->findOrFail(
                $validated['vehicle_id']
            );


        /*
        |--------------------------------------------------------------------------
        | Vendor Check
        |--------------------------------------------------------------------------
        */

        if (!$vehicle->vendor) {

            return response()->json([
                'success' => false,
                'message' => 'Vehicle vendor information not found.',
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Passenger Capacity Check
        |--------------------------------------------------------------------------
        */

        if (
            $validated['passengers'] >
            $vehicle->passenger_capacity
        ) {

            return response()->json([
                'success' => false,
                'message' =>
                    "Maximum passenger capacity is {$vehicle->passenger_capacity}.",
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Dates
        |--------------------------------------------------------------------------
        */

        $startDate = Carbon::parse(
            $validated['start_date']
        )->startOfDay();


        $endDate = Carbon::parse(
            $validated['end_date']
        )->startOfDay();


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Booking
        |--------------------------------------------------------------------------
        |
        | একই User একই Vehicle-এর জন্য একই অথবা overlapping
        | date range-এ একাধিক active booking করতে পারবে না।
        |
        | Cancelled booking নতুন booking block করবে না।
        |
        */

        $duplicateBooking = TransportBooking::where(
            'user_id',
            Auth::id()
        )
            ->where(
                'vehicle_id',
                $vehicle->id
            )
            ->whereIn(
                'booking_status',
                [
                    'pending',
                    'confirmed',
                ]
            )
            ->whereDate(
                'start_date',
                '<=',
                $endDate->toDateString()
            )
            ->whereDate(
                'end_date',
                '>=',
                $startDate->toDateString()
            )
            ->exists();


        if ($duplicateBooking) {

            return response()->json([
                'success' => false,
                'message' =>
                    'You already have an active booking for this vehicle on the selected dates.',
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Total Days
        |--------------------------------------------------------------------------
        |
        | একই দিনও ১ দিনের booking হিসেবে গণনা হবে।
        |
        */

        $totalDays =
            $startDate->diffInDays($endDate) + 1;


        /*
        |--------------------------------------------------------------------------
        | Pricing
        |--------------------------------------------------------------------------
        */

        $pricePerDay =
            (float) $vehicle->price_per_day;


        $subtotal =
            $pricePerDay * $totalDays;


        $discount = 0;

        $tax = 0;


        $totalAmount =
            $subtotal -
            $discount +
            $tax;


        /*
        |--------------------------------------------------------------------------
        | Commission
        |--------------------------------------------------------------------------
        */

        $commissionRate =
            (float) (
                $vehicle->vendor->commission_rate ?? 0
            );


        $adminCommission =
            ($totalAmount * $commissionRate) / 100;


        $vendorEarning =
            $totalAmount - $adminCommission;


        /*
        |--------------------------------------------------------------------------
        | Generate Unique Booking Code
        |--------------------------------------------------------------------------
        */

        do {

            $bookingCode =
                'TRN-' .
                strtoupper(
                    Str::random(8)
                );

        } while (

            TransportBooking::where(
                'booking_code',
                $bookingCode
            )->exists()

        );


        /*
        |--------------------------------------------------------------------------
        | Create Booking
        |--------------------------------------------------------------------------
        */

        $booking = TransportBooking::create([

            'user_id' =>
                Auth::id(),

            'vendor_id' =>
                $vehicle->vendor_id,

            'vehicle_id' =>
                $vehicle->id,

            'booking_code' =>
                $bookingCode,

            'start_date' =>
                $startDate->toDateString(),

            'end_date' =>
                $endDate->toDateString(),

            'total_days' =>
                $totalDays,

            'passengers' =>
                $validated['passengers'],

            'price_per_day' =>
                $pricePerDay,

            'subtotal' =>
                $subtotal,

            'discount' =>
                $discount,

            'tax' =>
                $tax,

            'total_amount' =>
                $totalAmount,

            'commission_rate' =>
                $commissionRate,

            'admin_commission' =>
                $adminCommission,

            'vendor_earning' =>
                $vendorEarning,

            'payment_status' =>
                'pending',

            'booking_status' =>
                'pending',

            'pickup_location' =>
                $validated['pickup_location'],

            'dropoff_location' =>
                $validated['dropoff_location'] ?? null,

            'special_request' =>
                $validated['special_request'] ?? null,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' =>
                true,

            'message' =>
                'Transport booking created successfully.',

            'data' =>
                $booking,

        ], 201);
    }


    /**
     * ----------------------------------------------------------
     * User Transport Booking List
     * ----------------------------------------------------------
     */
    public function index()
    {
        $bookings = TransportBooking::where(
            'user_id',
            Auth::id()
        )
            ->with([

                'vehicle:id,name,slug,featured_image,vehicle_type',

                'vendor:id,business_name',

            ])
            ->latest()
            ->paginate(10);


        return response()->json([

            'success' =>
                true,

            'message' =>
                'Transport bookings retrieved successfully.',

            'data' =>
                $bookings,

        ]);
    }


    /**
     * ----------------------------------------------------------
     * Single User Transport Booking
     * ----------------------------------------------------------
     */
    public function show(
        TransportBooking $booking
    ) {

        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        */

        if (
            (int) $booking->user_id !==
            (int) Auth::id()
        ) {

            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Load Relations
        |--------------------------------------------------------------------------
        */

        $booking->load([

            'vehicle',

            'vendor:id,business_name',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' =>
                true,

            'data' =>
                $booking,

        ]);
    }
}