<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
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
            'coupon_code' => 'nullable|string|max:100',
            'special_request' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {

            $user = auth()->user();

            /*
            |--------------------------------------------------------------------------
            | Tour
            |--------------------------------------------------------------------------
            */

            $tour = Tour::findOrFail($request->tour_id);

            /*
            |--------------------------------------------------------------------------
            | Tour Date
            |--------------------------------------------------------------------------
            */

            $tourDate = TourDate::where('id', $request->tour_date_id)
                ->where('tour_id', $tour->id)
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Booking
            |--------------------------------------------------------------------------
            |
            | Same user cannot book same tour again while
            | pending or confirmed.
            |
            */

            $alreadyBooked = Booking::where('user_id', $user->id)
                ->where('tour_id', $tour->id)
                ->whereIn('booking_status', [
                    'pending',
                    'confirmed',
                ])
                ->exists();

            if ($alreadyBooked) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already booked this tour.',
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Person Count
            |--------------------------------------------------------------------------
            */

            $persons = (int) $request->person_count;

            if ($persons <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid person count.',
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Seat Check
            |--------------------------------------------------------------------------
            */

            if ($tourDate->available_seat < $persons) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough seats available.',
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Price
            |--------------------------------------------------------------------------
            */

            $unitPrice = (
                $tourDate->special_price !== null &&
                (float) $tourDate->special_price > 0
            )
                ? (float) $tourDate->special_price
                : (float) $tour->price;

            $subtotal = round(
                $unitPrice * $persons,
                2
            );

            $discount = 0;
            $couponCode = null;
            $total = $subtotal;

            /*
            |--------------------------------------------------------------------------
            | Coupon
            |--------------------------------------------------------------------------
            */

            if ($request->filled('coupon_code')) {

                $coupon = Coupon::where(
                    'code',
                    strtoupper(trim($request->coupon_code))
                )
                    ->where('status', 1)
                    ->lockForUpdate()
                    ->first();

                if ($coupon) {

                    $valid = true;

                    if (
                        $coupon->start_date &&
                        now()->lt($coupon->start_date)
                    ) {
                        $valid = false;
                    }

                    if (
                        $coupon->end_date &&
                        now()->gt($coupon->end_date)
                    ) {
                        $valid = false;
                    }

                    if (
                        $coupon->max_usage &&
                        $coupon->used_count >= $coupon->max_usage
                    ) {
                        $valid = false;
                    }

                    if ($valid) {

                        if (
                            $coupon->type === 'percentage' ||
                            $coupon->type === 'percent'
                        ) {

                            $discount =
                                ($subtotal * (float) $coupon->value) / 100;

                        } else {

                            $discount =
                                (float) $coupon->value;
                        }

                        $discount = min(
                            round($discount, 2),
                            $subtotal
                        );

                        $total = max(
                            0,
                            round($subtotal - $discount, 2)
                        );

                        $coupon->increment('used_count');

                        $couponCode = $coupon->code;
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Vendor
            |--------------------------------------------------------------------------
            |
            | Vendor is NOT required for booking.
            |
            | If tour has a vendor, we store vendor_id so commission
            | can be calculated later.
            |
            | If tour has no vendor, vendor_id remains NULL.
            |
            */

            $vendorId = $tour->vendor_id ?? null;

            /*
            |--------------------------------------------------------------------------
            | Create Booking
            |--------------------------------------------------------------------------
            */

            $booking = Booking::create([

                'user_id' => $user->id,

                'vendor_id' => $vendorId,

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
            | Reduce Available Seats
            |--------------------------------------------------------------------------
            */

            $tourDate->decrement(
                'available_seat',
                $persons
            );

            /*
            |--------------------------------------------------------------------------
            | Create Payment
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            | trx_id cannot be NULL.
            |
            */

            $booking->payments()->create([

                'trx_id' =>
                    'PAY-' . strtoupper(Str::random(16)),

                'payment_method' => null,

                'amount' => $total,

                'status' => 'pending',

                'paid_at' => null,

            ]);

            /*
            |--------------------------------------------------------------------------
            | Create Transaction
            |--------------------------------------------------------------------------
            */

            Transaction::create([

                'user_id' => $booking->user_id,

                'booking_id' => $booking->id,

                'transaction_id' =>
                    'TXN-' . strtoupper(Str::random(16)),

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
                        'user',
                        'tour',
                        'tourDate',
                        'payments',
                    ])
                ),

            ], 201);
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
            'tourDate',
            'payments',
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
            'tourDate',
            'payments',
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
    | SHOW BOOKING
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
            'payments',
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

            $booking = Booking::with([
                'tour',
                'user',
                'vendor',
                'payments',
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
            | Cancelled
            |--------------------------------------------------------------------------
            */

            if ($booking->booking_status === 'cancelled') {

                return back()->with(
                    'error',
                    'Cancelled booking cannot be confirmed.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Vendor / Commission
            |--------------------------------------------------------------------------
            |
            | Vendor is OPTIONAL.
            |
            | No vendor = booking can still be confirmed.
            |
            | Vendor exists = commission will be calculated.
            |
            */

            $vendor = $booking->vendor;

            if ($vendor) {

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
                | Create Commission
                |--------------------------------------------------------------------------
                */

                if (!$booking->commission()->exists()) {

                    $calculation = CommissionService::calculate(
                        (float) $booking->total_amount,
                        $commissionRate
                    );

                    Commission::create([

                        'booking_id' =>
                            $booking->id,

                        'total_amount' =>
                            $booking->total_amount,

                        'commission_rate' =>
                            $commissionRate,

                        'admin_earning' =>
                            $calculation['admin'],

                        'vendor_earning' =>
                            $calculation['vendor'],

                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            $payment = $booking->payments()
                ->latest()
                ->lockForUpdate()
                ->first();

            if ($payment) {

                $payment->update([

                    'status' => 'paid',

                    /*
                    | Never replace an existing payment method.
                    */

                    'payment_method' =>
                        $payment->payment_method
                        ?? 'manual',

                    'paid_at' =>
                        $payment->paid_at
                        ?? now(),

                    /*
                    | Make sure trx_id exists.
                    */

                    'trx_id' =>
                        $payment->trx_id
                        ?? 'PAY-' . strtoupper(Str::random(16)),

                ]);

            } else {

                /*
                |--------------------------------------------------------------------------
                | Create Payment If Missing
                |--------------------------------------------------------------------------
                */

                $payment = $booking->payments()->create([

                    'trx_id' =>
                        'PAY-' . strtoupper(Str::random(16)),

                    'payment_method' =>
                        'manual',

                    'amount' =>
                        $booking->total_amount,

                    'status' =>
                        'paid',

                    'paid_at' =>
                        now(),

                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Transaction
            |--------------------------------------------------------------------------
            */

            $transaction = Transaction::where(
                'booking_id',
                $booking->id
            )
                ->lockForUpdate()
                ->first();

            if ($transaction) {

                $transaction->update([

                    'status' =>
                        'success',

                    'paid_at' =>
                        $transaction->paid_at
                        ?? now(),

                    'payment_method' =>
                        $payment->payment_method
                        ?? 'manual',

                ]);

            } else {

                Transaction::create([

                    'user_id' =>
                        $booking->user_id,

                    'booking_id' =>
                        $booking->id,

                    'transaction_id' =>
                        'TXN-' . strtoupper(Str::random(16)),

                    'payment_method' =>
                        $payment->payment_method
                        ?? 'manual',

                    'amount' =>
                        $booking->total_amount,

                    'status' =>
                        'success',

                    'paid_at' =>
                        now(),

                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Confirm Booking
            |--------------------------------------------------------------------------
            */

            $booking->update([

                'booking_status' =>
                    'confirmed',

                'payment_status' =>
                    'paid',

            ]);

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

            $booking = Booking::with([
                'tourDate',
            ])
                ->lockForUpdate()
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
            | Completed Booking
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
            | Restore Seats
            |--------------------------------------------------------------------------
            */

            $tourDate = TourDate::lockForUpdate()
                ->find($booking->tour_date_id);

            if ($tourDate) {

                $tourDate->increment(
                    'available_seat',
                    $booking->person_count
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Restore Coupon Usage
            |--------------------------------------------------------------------------
            */

            if ($booking->coupon_code) {

                $coupon = Coupon::where(
                    'code',
                    $booking->coupon_code
                )
                    ->lockForUpdate()
                    ->first();

                if (
                    $coupon &&
                    $coupon->used_count > 0
                ) {

                    $coupon->decrement(
                        'used_count'
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Cancel Booking
            |--------------------------------------------------------------------------
            */

            $booking->update([

                'booking_status' =>
                    'cancelled',

                'payment_status' =>
                    'failed',

            ]);

            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            $booking->payments()
                ->where('status', 'pending')
                ->update([

                    'status' =>
                        'failed',

                ]);

            /*
            |--------------------------------------------------------------------------
            | Transaction
            |--------------------------------------------------------------------------
            */

            Transaction::where(
                'booking_id',
                $booking->id
            )
                ->where('status', 'pending')
                ->update([

                    'status' =>
                        'failed',

                ]);

            return back()->with(
                'success',
                'Booking cancelled successfully.'
            );
        });
    }
}