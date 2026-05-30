<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingReportController extends Controller
{
    // =========================
    // BOOKING REPORT
    // =========================
    public function bookingReport(Request $request)
    {
        $bookings = Booking::with(['user', 'tour'])
            ->when($request->status, function ($q) use ($request) {
                $q->where('booking_status', $request->status);
            })
            ->when($request->from, function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->from);
            })
            ->when($request->to, function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->to);
            })
            ->latest()
            ->get();

        return view('admin.reports.bookings', compact('bookings'));
    }

    // =========================
    // REVENUE REPORT
    // =========================
    public function revenueReport(Request $request)
    {
        $bookings = Booking::with(['user', 'tour'])
            ->where('booking_status', 'confirmed')
            ->when($request->from, function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->from);
            })
            ->when($request->to, function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->to);
            })
            ->latest()
            ->get();

        $totalRevenue = $bookings->sum('total_amount');

        return view('admin.reports.revenue', compact('bookings', 'totalRevenue'));
    }
}