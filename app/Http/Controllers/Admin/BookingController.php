<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Tour;
use App\Models\Commission;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\CommissionService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // CREATE BOOKING (USER SIDE OR ADMIN SIDE)
    public function store(Request $request)
    {
        $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'persons' => 'required|integer|min:1',
        ]);

        $tour = Tour::with('vendor')->findOrFail($request->tour_id);

        $vendor = $tour->vendor;

        $persons = $request->persons;

        $price = $tour->price;

        $total = $price * $persons;

        $rate = 10;

        $calc = CommissionService::calculate($total, $rate);

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'tour_id' => $tour->id,
            'vendor_id' => $vendor->id,

            'persons' => $persons,

            'price' => $price,
            'total_price' => $total,

            'commission_rate' => $rate,
            'commission_amount' => $calc['admin'],
            'vendor_earning' => $calc['vendor'],

            'booking_status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        activityLog('Booking', 'CREATE', 'Booking created');

        return back()->with('success', 'Booking created successfully');
    }

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

    // SHOW
    public function show($id)
    {
        $booking = Booking::with(['user','tour','vendor'])
            ->findOrFail($id);

        return view('admin.bookings.view', compact('booking'));
    }

    // CONFIRM BOOKING
    public function confirm($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->booking_status = 'confirmed';
        $booking->payment_status = 'paid';
        $booking->save();

        $rate = 10;

        $calc = CommissionService::calculate($booking->total_price, $rate);

        Commission::create([
            'booking_id' => $booking->id,
            'total_amount' => $booking->total_price,
            'commission_rate' => $rate,
            'admin_earning' => $calc['admin'],
            'vendor_earning' => $calc['vendor'],
        ]);

        return back()->with('success', 'Booking Confirmed');
    }

    // CANCEL
    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->booking_status = 'cancelled';
        $booking->save();

        return back()->with('success', 'Booking Cancelled');
    }
}