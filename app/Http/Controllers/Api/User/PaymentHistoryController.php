<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentHistoryController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with([
            'booking.tour',
            'booking.tourDate',
        ])
        ->whereHas('booking', function ($query) use ($request) {

            $query->where('user_id', $request->user()->id);

        })
        ->latest()
        ->get();

        return response()->json([

            'success' => true,

            'data' => $payments,

        ]);
    }
}