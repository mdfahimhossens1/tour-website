<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Commission;
use App\Models\Coupon;
use App\Models\Tour;
use App\Models\TourDate;
use App\Models\Transaction;
use App\Services\CommissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CREATE BOOKING
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'tour_date_id' => 'required|exists:tour_dates,id',
            'person_count' => 'required|integer|min:1',
            'coupon_code' => 'nullable|string',
            'special_request' => 'nullable|string',
        ]);


        return DB::transaction(function () use ($request) {

            /*
            |--------------------------------------------------------------------------
            | Get Tour + Tour Date
            |--------------------------------------------------------------------------
            */

            $tour = Tour::with('vendor')
                ->findOrFail($request->tour_id);

            $tourDate = TourDate::lockForUpdate()
                ->findOrFail($request->tour_date_id);


            /*
            |--------------------------------------------------------------------------
            | Check Vendor
            |--------------------------------------------------------------------------
            */

            $vendor = $tour->vendor;

            if (!$vendor) {

                return response()->json([
                    'success' => false,
                    'message' => 'Vendor information not found for this tour.'
                ], 422);
            }


            /*
            |--------------------------------------------------------------------------
            | Check Vendor Status
            |--------------------------------------------------------------------------
            */

            if ($vendor->status !== 'approved') {

                return response()->json([
                    'success' => false,
                    'message' => 'This vendor is not approved.'
                ], 422);
            }


            /*
            |--------------------------------------------------------------------------
            | Seat Check
            |--------------------------------------------------------------------------
            */

            $persons = $request->person_count;

            if ($tourDate->available_seat < $persons) {

                return response()->json([
                    'success' => false,
                    'message' => 'Not enough seats available.'
                ], 422);
            }


            /*
            |--------------------------------------------------------------------------
            | Price Calculation
            |--------------------------------------------------------------------------
            */

            $unitPrice = $tourDate->special_price ?: $tour->price;

            $subtotal = $unitPrice * $persons;

            $discount = 0;

            $couponCode = null;

            $total = $subtotal;


            /*
            |--------------------------------------------------------------------------
            | Coupon System
            |--------------------------------------------------------------------------
            */

            if ($request->filled('coupon_code')) {

                $coupon = Coupon::where(
                        'code',
                        strtoupper($request->coupon_code)
                    )
                    ->where('status', 1)
                    ->first();


                if ($coupon) {

                    $valid = true;


                    /*
                    |--------------------------------------------------------------------------
                    | Coupon Start Date
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $coupon->start_date &&
                        now()->lt($coupon->start_date)
                    ) {
                        $valid = false;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Coupon End Date
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $coupon->end_date &&
                        now()->gt($coupon->end_date)
                    ) {
                        $valid = false;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Coupon Usage Limit
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $coupon->max_usage &&
                        $coupon->used_count >= $coupon->max_usage
                    ) {
                        $valid = false;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Apply Coupon
                    |--------------------------------------------------------------------------
                    */

                    if ($valid) {

                        if ($coupon->type === 'percent') {

                            $discount =
                                ($total * $coupon->value) / 100;

                        } else {

                            $discount = $coupon->value;

                        }


                        $discount = min(
                            $discount,
                            $total
                        );


                        $total = max(
                            0,
                            $total - $discount
                        );


                        $coupon->increment('used_count');

                        $couponCode = $coupon->code;
                    }
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Create Booking
            |--------------------------------------------------------------------------
            */

            $booking = Booking::create([

                'user_id' => auth()->id(),

                'vendor_id' => $vendor->id,

                'tour_id' => $tour->id,

                'tour_date_id' => $tourDate->id,

                'booking_code' =>
                    'BK-' . strtoupper(Str::random(8)),

                'person_count' => $persons,

                'unit_price' => $unitPrice,

                'subtotal' => $subtotal,

                'discount' => $discount,

                'coupon_code' => $couponCode,

                'total_amount' => $total,

                'payment_status' => 'pending',

                'booking_status' => 'pending',

                'special_request' =>
                    $request->special_request,

            ]);


            /*
            |--------------------------------------------------------------------------
            | Deduct Seats
            |--------------------------------------------------------------------------
            */

            $tourDate->decrement(
                'available_seat',
                $persons
            );


            /*
            |--------------------------------------------------------------------------
            | Create Pending Transaction
            |--------------------------------------------------------------------------
            */

            Transaction::create([

                'user_id' => $booking->user_id,

                'booking_id' => $booking->id,

                'transaction_id' =>
                    'TXN-' . time() . rand(1000, 9999),

                'payment_method' => null,

                'amount' => $total,

                'status' => 'pending',

            ]);


            /*
            |--------------------------------------------------------------------------
            | Response
            |--------------------------------------------------------------------------
            */

            return response()->json([

                'success' => true,

                'message' =>
                    'Booking created successfully.',

                'booking' => new BookingResource(

                    $booking->load([
                        'tour',
                        'tourDate',
                        'user',
                        'vendor',
                    ])

                ),

            ]);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | PENDING BOOKINGS
    |--------------------------------------------------------------------------
    */

    public function pending()
    {
        $bookings = Booking::with([
                'user',
                'tour',
                'vendor',
            ])
            ->where('booking_status', 'pending')
            ->latest()
            ->get();


        return view(
            'admin.bookings.pending',
            compact('bookings')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CONFIRMED BOOKINGS
    |--------------------------------------------------------------------------
    */

    public function confirmed()
    {
        $bookings = Booking::with([
                'user',
                'tour',
                'vendor',
            ])
            ->where('booking_status', 'confirmed')
            ->latest()
            ->get();


        return view(
            'admin.bookings.confirmed',
            compact('bookings')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW SINGLE BOOKING
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $booking = Booking::with([
                'user',
                'tour',
                'tourDate',
                'vendor',
                'commission',
                'transaction',
            ])
            ->findOrFail($id);


        return view(
            'admin.bookings.view',
            compact('booking')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CONFIRM BOOKING
    |--------------------------------------------------------------------------
    */

    public function confirm($id)
    {
        return DB::transaction(function () use ($id) {

            /*
            |--------------------------------------------------------------------------
            | Lock Booking
            |--------------------------------------------------------------------------
            */

            $booking = Booking::with([
                    'tour',
                    'vendor',
                    'user',
                ])
                ->lockForUpdate()
                ->findOrFail($id);


            /*
            |--------------------------------------------------------------------------
            | Already Confirmed
            |--------------------------------------------------------------------------
            */

            if ($booking->booking_status === 'confirmed') {

                return back()->with(
                    'error',
                    'This booking is already confirmed.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Vendor Check
            |--------------------------------------------------------------------------
            */

            $vendor = $booking->vendor;


            if (!$vendor) {

                return back()->with(
                    'error',
                    'Vendor information not found.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Vendor Status Check
            |--------------------------------------------------------------------------
            */

            if ($vendor->status !== 'approved') {

                return back()->with(
                    'error',
                    'This vendor is not approved.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Get Vendor Commission Rate
            |--------------------------------------------------------------------------
            */

            $commissionRate =
                (float) ($vendor->commission_rate ?? 0);


            /*
            |--------------------------------------------------------------------------
            | Validate Commission Rate
            |--------------------------------------------------------------------------
            */

            if (
                $commissionRate < 0 ||
                $commissionRate > 100
            ) {

                return back()->with(
                    'error',
                    'Invalid vendor commission rate.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Calculate Commission
            |--------------------------------------------------------------------------
            */

            $calculation = CommissionService::calculate(

                $booking->total_amount,

                $commissionRate

            );


            /*
            |--------------------------------------------------------------------------
            | Create Commission Record
            |--------------------------------------------------------------------------
            */

            Commission::create([

                'booking_id' => $booking->id,

                'total_amount' =>
                    $booking->total_amount,

                'commission_rate' =>
                    $commissionRate,

                'admin_earning' =>
                    $calculation['admin'],

                'vendor_earning' =>
                    $calculation['vendor'],

            ]);


            /*
            |--------------------------------------------------------------------------
            | Confirm Booking
            |--------------------------------------------------------------------------
            */

            $booking->update([

                'booking_status' => 'confirmed',

                'payment_status' => 'paid',

            ]);


            /*
            |--------------------------------------------------------------------------
            | Update Transaction
            |--------------------------------------------------------------------------
            */

            $transaction = Transaction::where(
                'booking_id',
                $booking->id
            )->first();


            if ($transaction) {

                $transaction->update([

                    'status' => 'success',

                    'paid_at' => now(),

                    'payment_method' =>
                        'manual_admin',

                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            return back()->with(
                'success',
                'Booking confirmed successfully.'
            );
        });
    }


    /*
    |--------------------------------------------------------------------------
    | CANCEL BOOKING
    |--------------------------------------------------------------------------
    */

    public function cancel($id)
    {
        return DB::transaction(function () use ($id) {

            $booking = Booking::lockForUpdate()
                ->findOrFail($id);


            /*
            |--------------------------------------------------------------------------
            | Already Cancelled
            |--------------------------------------------------------------------------
            */

            if ($booking->booking_status === 'cancelled') {

                return back()->with(
                    'error',
                    'This booking is already cancelled.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Don't Cancel Completed Booking
            |--------------------------------------------------------------------------
            */

            if ($booking->booking_status === 'completed') {

                return back()->with(
                    'error',
                    'Completed booking cannot be cancelled.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Cancel Booking
            |--------------------------------------------------------------------------
            */

            $booking->update([

                'booking_status' => 'cancelled',

            ]);


            return back()->with(
                'success',
                'Booking cancelled successfully.'
            );
        });
    }
}
