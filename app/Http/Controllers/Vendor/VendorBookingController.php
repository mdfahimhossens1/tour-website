<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Wallet;
use App\Models\WalletTransaction;

class VendorBookingController extends Controller
{
    /**
     * BOOKING LIST
     */
    public function index()
    {
        $vendor = auth()->user()->vendor;

        if (!$vendor) {
            abort(403, 'Vendor profile not found.');
        }

        $bookings = Booking::with(['tour', 'user'])
            ->where('vendor_id', $vendor->id)
            ->latest()
            ->paginate(20);

        return view('vendor.bookings.index', compact('bookings'));
    }

    /**
     * SINGLE BOOKING VIEW
     */
    public function show($id)
    {
        $vendor = auth()->user()->vendor;

        if (!$vendor) {
            abort(403, 'Vendor profile not found.');
        }

        $booking = Booking::with(['tour', 'user'])
            ->where('vendor_id', $vendor->id)
            ->findOrFail($id);

        return view('vendor.bookings.show', compact('booking'));
    }

    /**
     * CONFIRM BOOKING (MOVE TO WALLET PENDING)
     */
    public function confirm($id)
    {
        $vendor = auth()->user()->vendor;

        if (!$vendor) {
            abort(403, 'Vendor profile not found.');
        }

        $booking = Booking::where('vendor_id', $vendor->id)
            ->findOrFail($id);

        // Already confirmed check
        if ($booking->booking_status === 'confirmed') {
            return back()->with('error', 'Already confirmed');
        }

        $booking->booking_status = 'confirmed';
        $booking->payment_status = 'paid';
        $booking->save();

        // Wallet auto create
        $wallet = Wallet::firstOrCreate(
            ['vendor_id' => $vendor->id],
            [
                'balance' => 0,
                'pending_balance' => 0,
                'total_earned' => 0,
                'total_withdrawn' => 0,
            ]
        );

        // Booking.com style → pending balance
        $wallet->pending_balance += $booking->vendor_earning;
        $wallet->total_earned += $booking->vendor_earning;
        $wallet->save();

        // Transaction log
        WalletTransaction::create([
            'vendor_id' => $vendor->id,
            'booking_id' => $booking->id,
            'type' => 'credit',
            'amount' => $booking->vendor_earning,
            'status' => 'pending',
            'note' => 'Booking confirmed → pending wallet',
        ]);

        return back()->with('success', 'Booking confirmed successfully');
    }

    /**
     * CANCEL BOOKING
     */
    public function cancel($id)
    {
        $vendor = auth()->user()->vendor;

        if (!$vendor) {
            abort(403, 'Vendor profile not found.');
        }

        $booking = Booking::where('vendor_id', $vendor->id)
            ->findOrFail($id);

        if ($booking->booking_status === 'cancelled') {
            return back()->with('error', 'Already cancelled');
        }

        $booking->booking_status = 'cancelled';
        $booking->save();

        return back()->with('success', 'Booking cancelled successfully');
    }
}