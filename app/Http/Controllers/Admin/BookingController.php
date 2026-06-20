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
    // =========================
    // CREATE BOOKING (USER/API)
    // =========================
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

            $tour = Tour::findOrFail($request->tour_id);
            $tourDate = TourDate::lockForUpdate()->findOrFail($request->tour_date_id);

            $persons = $request->person_count;

            // =========================
            // SEAT CHECK (SAFE)
            // =========================
            if ($tourDate->available_seat < $persons) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough seats available'
                ], 422);
            }

            // =========================
            // PRICE CALCULATION
            // =========================
            $price = $tour->price;
            $total = $price * $persons;

            $discount = 0;
            $couponCode = null;

            // =========================
            // COUPON SYSTEM
            // =========================
            if ($request->coupon_code) {

                $coupon = Coupon::where('code', strtoupper($request->coupon_code))
                    ->where('status', 1)
                    ->first();

                if ($coupon) {

                    $valid = true;

                    if ($coupon->start_date && now()->lt($coupon->start_date)) {
                        $valid = false;
                    }

                    if ($coupon->end_date && now()->gt($coupon->end_date)) {
                        $valid = false;
                    }

                    if ($coupon->max_usage && $coupon->used_count >= $coupon->max_usage) {
                        $valid = false;
                    }

                    if ($valid) {

                        if ($coupon->type === 'percent') {
                            $discount = ($total * $coupon->value) / 100;
                        } else {
                            $discount = $coupon->value;
                        }

                        $total = max(0, $total - $discount);

                        $coupon->increment('used_count');

                        $couponCode = $coupon->code;
                    }
                }
            }

            // =========================
            // CREATE BOOKING
            // =========================
            $booking = Booking::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'tour_id' => $tour->id,
                'tour_date_id' => $tourDate->id,

                'booking_code' => 'BK-' . strtoupper(Str::random(8)),

                'person_count' => $persons,
                'total_amount' => $total,

                'payment_status' => 'pending',
                'booking_status' => 'pending',

                'special_request' => $request->special_request,
            ]);

            // =========================
            // SEAT DEDUCT (IMPORTANT)
            // =========================
            $tourDate->decrement('available_seat', $persons);

            // =========================
            // CREATE TRANSACTION
            // =========================
            Transaction::create([
                'user_id' => $booking->user_id,
                'booking_id' => $booking->id,
                'transaction_id' => 'TXN-' . time() . rand(1000,9999),
                'payment_method' => null,
                'amount' => $total,
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'booking' => $booking,
                'discount' => $discount,
                'coupon' => $couponCode,
            ]);
        });
    }

    // =========================
    // PENDING BOOKINGS
    // =========================
    public function pending()
    {
        $bookings = Booking::with(['user', 'tour'])
            ->where('booking_status', 'pending')
            ->latest()
            ->get();

        return view('admin.bookings.pending', compact('bookings'));
    }

    // =========================
    // CONFIRMED BOOKINGS
    // =========================
    public function confirmed()
    {
        $bookings = Booking::with(['user', 'tour'])
            ->where('booking_status', 'confirmed')
            ->latest()
            ->get();

        return view('admin.bookings.confirmed', compact('bookings'));
    }

    // =========================
    // SHOW SINGLE BOOKING
    // =========================
    public function show($id)
    {
        $booking = Booking::with(['user', 'tour', 'tourDate'])
            ->findOrFail($id);

        return view('admin.bookings.view', compact('booking'));
    }

    // =========================
    // CONFIRM BOOKING (ADMIN)
    // =========================
    public function confirm($id)
    {
        return DB::transaction(function () use ($id) {

            $booking = Booking::with(['tourDate', 'user', 'tour'])
                ->lockForUpdate()
                ->findOrFail($id);

            if ($booking->booking_status === 'confirmed') {
                return back()->with('error', 'Already confirmed');
            }

            $rate = 10;

            $calc = CommissionService::calculate(
                $booking->total_amount,
                $rate
            );

            $booking->update([
                'booking_status' => 'confirmed',
                'payment_status' => 'paid',
                'admin_commission' => $calc['admin'],
                'vendor_earning' => $calc['vendor'],
            ]);

            Commission::create([
                'booking_id' => $booking->id,
                'total_amount' => $booking->total_amount,
                'commission_rate' => $rate,
                'admin_earning' => $calc['admin'],
                'vendor_earning' => $calc['vendor'],
            ]);

            // update transaction safely
            $transaction = Transaction::where('booking_id', $booking->id)->first();

            if ($transaction) {
                $transaction->update([
                    'status' => 'success',
                    'paid_at' => now(),
                    'payment_method' => 'manual_admin',
                ]);
            }

            return back()->with('success', 'Booking Confirmed Successfully');
        });
    }

    // =========================
    // CANCEL BOOKING
    // =========================
    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->booking_status === 'cancelled') {
            return back()->with('error', 'Already cancelled');
        }

        $booking->update([
            'booking_status' => 'cancelled'
        ]);

        return back()->with('success', 'Booking Cancelled Successfully');
    }
}