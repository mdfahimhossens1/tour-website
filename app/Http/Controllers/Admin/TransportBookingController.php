<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\TransportBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransportBookingController extends Controller
{
    /**
     * Transport Booking List
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

                $q->where('booking_code', 'like', "%{$search}%")

                    ->orWhere('pickup_location', 'like', "%{$search}%")

                    ->orWhere('dropoff_location', 'like', "%{$search}%")

                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })

->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {
    $vehicleQuery->where('name', 'like', "%{$search}%")
        ->orWhere('registration_number', 'like', "%{$search}%")
        ->orWhere('brand', 'like', "%{$search}%")
        ->orWhere('model', 'like', "%{$search}%");
});

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
     * Store Transport Booking
     */
    public function store(Request $request)
    {
        $request->validate([

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
        | Vehicle
        |--------------------------------------------------------------------------
        */

        $vehicle = Vehicle::with('vendor')
            ->findOrFail($request->vehicle_id);


        /*
        |--------------------------------------------------------------------------
        | Vehicle Status
        |--------------------------------------------------------------------------
        */

        if (!$vehicle->status) {

            return back()->withErrors([
                'vehicle_id' => 'This vehicle is currently unavailable.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Vendor
        |--------------------------------------------------------------------------
        */

        $vendor = $vehicle->vendor;

        if (!$vendor) {

            return back()->withErrors([
                'vehicle_id' => 'This vehicle has no vendor assigned.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Days
        |--------------------------------------------------------------------------
        */

        $startDate = Carbon::parse(
            $request->start_date
        );

        $endDate = Carbon::parse(
            $request->end_date
        );

        $totalDays =
            $startDate->diffInDays($endDate) + 1;


        if ($totalDays < 1) {
            $totalDays = 1;
        }


        /*
        |--------------------------------------------------------------------------
        | Price
        |--------------------------------------------------------------------------
        */

        $pricePerDay =
            (float) $vehicle->price_per_day;

        $subtotal =
            $pricePerDay * $totalDays;


        /*
        |--------------------------------------------------------------------------
        | Discount
        |--------------------------------------------------------------------------
        */

        $discount = 0;


        /*
        |--------------------------------------------------------------------------
        | Tax
        |--------------------------------------------------------------------------
        */

        $tax = 0;


        /*
        |--------------------------------------------------------------------------
        | Total
        |--------------------------------------------------------------------------
        */

        $totalAmount =
            $subtotal
            - $discount
            + $tax;


        /*
        |--------------------------------------------------------------------------
        | Commission
        |--------------------------------------------------------------------------
        */

        $commissionRate =
            (float) $vendor->commission_rate;

        $adminCommission =
            ($totalAmount * $commissionRate) / 100;

        $vendorEarning =
            $totalAmount - $adminCommission;


        /*
        |--------------------------------------------------------------------------
        | Booking Code
        |--------------------------------------------------------------------------
        */

        $bookingCode =
            'TRN-' .
            strtoupper(
                Str::random(8)
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
                $vendor->id,

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
                $request->passengers,

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
                $request->pickup_location,

            'dropoff_location' =>
                $request->dropoff_location,

            'special_request' =>
                $request->special_request,
        ]);


        return response()->json([
            'success' => true,

            'message' =>
                'Transport booking created successfully.',

            'booking' =>
                $booking,

        ], 201);
    }


    /**
     * Update Transport Booking
     */
    public function update(
        Request $request,
        TransportBooking $transportBooking
    ) {

        $request->validate([

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
        | Dates
        |--------------------------------------------------------------------------
        */

        $startDate = Carbon::parse(
            $request->start_date
        );

        $endDate = Carbon::parse(
            $request->end_date
        );


        $totalDays =
            $startDate->diffInDays($endDate) + 1;


        if ($totalDays < 1) {
            $totalDays = 1;
        }


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
        */

        $commissionRate =
            (float) $transportBooking->commission_rate;


        $adminCommission =
            ($totalAmount * $commissionRate) / 100;


        $vendorEarning =
            $totalAmount - $adminCommission;


        /*
        |--------------------------------------------------------------------------
        | Update
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
                $request->passengers,

            'subtotal' =>
                $subtotal,

            'total_amount' =>
                $totalAmount,

            'admin_commission' =>
                $adminCommission,

            'vendor_earning' =>
                $vendorEarning,

            'payment_status' =>
                $request->payment_status,

            'booking_status' =>
                $request->booking_status,

            'pickup_location' =>
                $request->pickup_location,

            'dropoff_location' =>
                $request->dropoff_location,

            'special_request' =>
                $request->special_request,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.transport-bookings.index')
            ->with(
                'success',
                'Transport booking updated successfully.'
            );
    }


    /**
     * Delete Transport Booking
     */
    public function destroy(
        TransportBooking $transportBooking
    ) {

        $transportBooking->delete();


        return redirect()
            ->route('admin.transport-bookings.index')
            ->with(
                'success',
                'Transport booking deleted successfully.'
            );
    }
}