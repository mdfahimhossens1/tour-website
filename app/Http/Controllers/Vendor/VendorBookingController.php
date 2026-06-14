<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Wallet;
use App\Models\WalletTransaction;

class VendorBookingController extends Controller
{
    /**
     * Booking List
     */
    public function index()
    {
        $vendor = auth()->user()->vendor;

        if (!$vendor) {
            abort(403, 'Vendor profile not found.');
        }

        $bookings = Booking::with([
                'tour',
                'user'
            ])
            ->where('vendor_id', $vendor->id)
            ->latest()
            ->paginate(20);

        return view(
            'vendor.bookings.index',
            compact('bookings')
        );
    }

    /**
     * Single Booking
     */
    public function show($id)
    {
        $vendor = auth()->user()->vendor;

        if (!$vendor) {
            abort(403, 'Vendor profile not found.');
        }

        $booking = Booking::with([
                'tour',
                'user'
            ])
            ->where('vendor_id', $vendor->id)
            ->findOrFail($id);

        return view(
            'vendor.bookings.show',
            compact('booking')
        );
    }

    /**
     * Confirm Booking
     */
public function confirm($id)
{
    $vendor = auth()->user()->vendor;

    if (!$vendor) {
        abort(403, 'Vendor profile not found.');
    }

    $booking = Booking::where(
            'vendor_id',
            $vendor->id
        )
        ->findOrFail($id);

    /*
    |--------------------------------------------------------------------------
    | Already Confirmed?
    |--------------------------------------------------------------------------
    */

    if ($booking->booking_status === 'confirmed') {
        return back()->with(
            'error',
            'Booking already confirmed'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Prevent Double Wallet Credit
    |--------------------------------------------------------------------------
    */

    $alreadyCredited = WalletTransaction::where(
            'booking_id',
            $booking->id
        )
        ->where(
            'type',
            'credit'
        )
        ->exists();

    if ($alreadyCredited) {
        return back()->with(
            'error',
            'Wallet already credited for this booking'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Check Tour Date & Seats
    |--------------------------------------------------------------------------
    */

    $tourDate = $booking->tourDate;

    if (!$tourDate) {
        return back()->with(
            'error',
            'Tour date not found'
        );
    }

    if (
        $tourDate->available_seat <
        $booking->person_count
    ) {
        return back()->with(
            'error',
            'Not enough seats available'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Confirm Booking
    |--------------------------------------------------------------------------
    */

    $booking->booking_status = 'confirmed';
    $booking->payment_status = 'paid';
    $booking->save();

    /*
    |--------------------------------------------------------------------------
    | Reduce Available Seats
    |--------------------------------------------------------------------------
    */

    $tourDate->available_seat =
        $tourDate->available_seat -
        $booking->person_count;

    $tourDate->save();

    /*
    |--------------------------------------------------------------------------
    | Create/Get Wallet
    |--------------------------------------------------------------------------
    */

    $wallet = Wallet::firstOrCreate(
        [
            'vendor_id' => $vendor->id
        ],
        [
            'balance' => 0,
            'pending_balance' => 0,
            'total_earned' => 0,
            'total_withdrawn' => 0,
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | Calculate Earning
    |--------------------------------------------------------------------------
    */

    $amount =
        $booking->vendor_earning
        ?? $booking->total_amount
        ?? 0;

    /*
    |--------------------------------------------------------------------------
    | Credit Wallet
    |--------------------------------------------------------------------------
    */

    $wallet->balance += $amount;
    $wallet->total_earned += $amount;
    $wallet->save();

    /*
    |--------------------------------------------------------------------------
    | Wallet Transaction
    |--------------------------------------------------------------------------
    */

    WalletTransaction::create([
        'vendor_id' => $vendor->id,
        'booking_id' => $booking->id,
        'type' => 'credit',
        'amount' => $amount,
        'status' => 'completed',
        'note' => 'Booking #' . $booking->id . ' confirmed',
    ]);

    return back()->with(
        'success',
        'Booking confirmed and wallet credited successfully'
    );
}

    /**
     * Cancel Booking
     */
public function cancel($id)
{
    $vendor = auth()->user()->vendor;

    if (!$vendor) {
        abort(403, 'Vendor profile not found.');
    }

    $booking = Booking::where(
            'vendor_id',
            $vendor->id
        )
        ->findOrFail($id);

    if ($booking->booking_status === 'cancelled') {
        return back()->with(
            'error',
            'Booking already cancelled'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Return Seats If Confirmed
    |--------------------------------------------------------------------------
    */

    if ($booking->booking_status === 'confirmed') {

        $tourDate = $booking->tourDate;

        if ($tourDate) {

            $tourDate->available_seat +=
                $booking->person_count;

            $tourDate->save();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Cancel Booking
    |--------------------------------------------------------------------------
    */

    $booking->booking_status = 'cancelled';
    $booking->save();

    return back()->with(
        'success',
        'Booking cancelled successfully'
    );
}
}