<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Commission;
use App\Services\CommissionService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // PENDING
    public function pending()
    {
        $bookings = Booking::with(['user','tour'])
            ->where('booking_status', 'pending')
            ->latest()
            ->get();

        return view('admin.bookings.pending', compact('bookings'));
    }

    // CONFIRMED
    public function confirmed()
    {
        $bookings = Booking::with(['user','tour'])
            ->where('booking_status', 'confirmed')
            ->latest()
            ->get();

        return view('admin.bookings.confirmed', compact('bookings'));
    }

    // VIEW SINGLE
    public function show($id)
    {
        $booking = Booking::with(['user','tour','tourDate'])
            ->findOrFail($id);

        return view('admin.bookings.view', compact('booking'));
    }

    // CONFIRM BOOKING
public function confirm($id)
{
    $booking = Booking::with('tour')->findOrFail($id);

    $booking->booking_status = 'confirmed';
    $booking->payment_status = 'paid';
    $booking->save();

    // 🔥 commission rate (future: settings table)
    $rate = 10;

    $result = CommissionService::calculate(
        $booking->total_amount,
        $rate
    );

    Commission::create([
        'booking_id' => $booking->id,
        'total_amount' => $booking->total_amount,
        'commission_rate' => $rate,
        'admin_earning' => $result['admin'],
        'vendor_earning' => $result['vendor'],
    ]);

    return back()->with('success', 'Booking Confirmed + Commission Calculated');
}

    // CANCEL BOOKING
    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->booking_status = 'cancelled';
        $booking->save();

        return back()->with('success', 'Booking Cancelled');
    }
}