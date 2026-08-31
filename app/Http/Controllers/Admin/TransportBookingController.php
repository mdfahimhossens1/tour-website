<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransportBooking;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransportBookingController extends Controller
{
    /**
     * ----------------------------------------------------------
     * Transport Booking List
     * ----------------------------------------------------------
     */
    public function index(Request $request)
    {
        $query = TransportBooking::with([
            'user',
            'vendor',
            'vehicle',
            'latestPayment',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'booking_code',
                    'like',
                    "%{$search}%"
                )

                    ->orWhere(
                        'pickup_location',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'dropoff_location',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhereHas(
                        'user',
                        function ($userQuery) use ($search) {

                            $userQuery
                                ->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'email',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    )

                    ->orWhereHas(
                        'vehicle',
                        function ($vehicleQuery) use ($search) {

                            $vehicleQuery
                                ->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'registration_number',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'brand',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'model',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Booking Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('booking_status')) {

            $query->where(
                'booking_status',
                $request->booking_status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Payment Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_status')) {

            $query->where(
                'payment_status',
                $request->payment_status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('start_date')) {

            $query->whereDate(
                'start_date',
                '>=',
                $request->start_date
            );
        }


        if ($request->filled('end_date')) {

            $query->whereDate(
                'end_date',
                '<=',
                $request->end_date
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $bookings = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();


        return view(
            'admin.transport-bookings.index',
            compact('bookings')
        );
    }


    /**
     * ----------------------------------------------------------
     * Store Transport Booking
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
                'nullable',
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

        $vehicle = Vehicle::with('vendor')
            ->findOrFail(
                $validated['vehicle_id']
            );


        if (!$vehicle->status) {

            return back()
                ->withInput()
                ->withErrors([
                    'vehicle_id' =>
                        'This vehicle is currently unavailable.',
                ]);
        }


        if (!$vehicle->vendor) {

            return back()
                ->withInput()
                ->withErrors([
                    'vehicle_id' =>
                        'This vehicle has no vendor assigned.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Passenger Capacity Check
        |--------------------------------------------------------------------------
        */

        if (
            $validated['passengers'] >
            (int) $vehicle->passenger_capacity
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'passengers' =>
                        "Maximum passenger capacity is {$vehicle->passenger_capacity}.",
                ]);
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
        | Check Vehicle Availability
        |--------------------------------------------------------------------------
        |
        | একই গাড়ি একই অথবা overlapping date-এ
        | একাধিক active booking নিতে পারবে না।
        |
        */

        $hasVehicleConflict = TransportBooking::where(
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


        if ($hasVehicleConflict) {

            return back()
                ->withInput()
                ->withErrors([
                    'start_date' =>
                        'This vehicle is already booked for the selected dates.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Days
        |--------------------------------------------------------------------------
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
            $subtotal
            - $discount
            + $tax;


        /*
        |--------------------------------------------------------------------------
        | Commission
        |--------------------------------------------------------------------------
        */

        $commissionRate = max(
            0,
            min(
                100,
                (float) ($vehicle->vendor->commission_rate ?? 0)
            )
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
                $validated['pickup_location'] ?? null,

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

            'booking' =>
                $booking,

        ], 201);
    }


    /**
     * ----------------------------------------------------------
     * Update Transport Booking
     * ----------------------------------------------------------
     */
    public function update(
        Request $request,
        TransportBooking $transportBooking
    ) {

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'start_date' => [
                'required',
                'date',
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

            'payment_status' => [
                'required',
                'in:pending,paid,failed,refunded',
            ],

            'booking_status' => [
                'required',
                'in:pending,confirmed,cancelled,completed',
            ],

            'pickup_location' => [
                'nullable',
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
        | Vehicle
        |--------------------------------------------------------------------------
        */

        $vehicle = $transportBooking->vehicle;

        if (!$vehicle) {

            return back()
                ->withInput()
                ->withErrors([
                    'vehicle_id' =>
                        'Vehicle information not found.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Passenger Capacity Check
        |--------------------------------------------------------------------------
        */

        if (
            $validated['passengers'] >
            (int) $vehicle->passenger_capacity
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'passengers' =>
                        "Maximum passenger capacity is {$vehicle->passenger_capacity}.",
                ]);
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
        | Vehicle Conflict Check
        |--------------------------------------------------------------------------
        |
        | নিজের booking বাদ দিয়ে অন্য active booking-এর
        | সাথে date overlap হচ্ছে কিনা check করা হবে।
        |
        */

        $hasVehicleConflict = TransportBooking::where(
            'vehicle_id',
            $transportBooking->vehicle_id
        )
            ->where(
                'id',
                '!=',
                $transportBooking->id
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


        if ($hasVehicleConflict) {

            return back()
                ->withInput()
                ->withErrors([
                    'start_date' =>
                        'This vehicle is already booked for the selected dates.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Days
        |--------------------------------------------------------------------------
        */

        $totalDays =
            $startDate->diffInDays($endDate) + 1;


        /*
        |--------------------------------------------------------------------------
        | Recalculate Price
        |--------------------------------------------------------------------------
        */

        $pricePerDay =
            (float) $transportBooking->price_per_day;


        $subtotal =
            $pricePerDay * $totalDays;


        $discount =
            (float) $transportBooking->discount;


        $tax =
            (float) $transportBooking->tax;


        $totalAmount =
            $subtotal
            - $discount
            + $tax;


        /*
        |--------------------------------------------------------------------------
        | Recalculate Commission
        |--------------------------------------------------------------------------
        |
        | Booking তৈরি হওয়ার সময়ের commission rate
        | একই রাখা হচ্ছে।
        |
        */

        $commissionRate = max(
            0,
            min(
                100,
                (float) $transportBooking->commission_rate
            )
        );


        $adminCommission =
            ($totalAmount * $commissionRate) / 100;


        $vendorEarning =
            $totalAmount - $adminCommission;


        /*
        |--------------------------------------------------------------------------
        | Update Booking
        |--------------------------------------------------------------------------
        */

        $transportBooking->update([

            'start_date' =>
                $startDate->toDateString(),

            'end_date' =>
                $endDate->toDateString(),

            'total_days' =>
                $totalDays,

            'passengers' =>
                $validated['passengers'],

            'subtotal' =>
                $subtotal,

            'total_amount' =>
                $totalAmount,

            'admin_commission' =>
                $adminCommission,

            'vendor_earning' =>
                $vendorEarning,

            'payment_status' =>
                $validated['payment_status'],

            'booking_status' =>
                $validated['booking_status'],

            'pickup_location' =>
                $validated['pickup_location'] ?? null,

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

        return redirect()
            ->route(
                'admin.transport-bookings.index'
            )
            ->with(
                'success',
                'Transport booking updated successfully.'
            );
    }


    /**
     * ----------------------------------------------------------
     * Delete Transport Booking
     * ----------------------------------------------------------
     */
    public function destroy(
        TransportBooking $transportBooking
    ) {

        /*
        |--------------------------------------------------------------------------
        | Completed Booking Protection
        |--------------------------------------------------------------------------
        */

        if (
            $transportBooking->booking_status ===
            'completed'
        ) {

            return back()->with(
                'error',
                'A completed transport booking cannot be deleted.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        $transportBooking->delete();


        return redirect()
            ->route(
                'admin.transport-bookings.index'
            )
            ->with(
                'success',
                'Transport booking deleted successfully.'
            );
    }
}
