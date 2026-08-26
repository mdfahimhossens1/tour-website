<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

class UserTransactionController extends Controller
{
    /**
     * Logged-in user's transaction history
     */
    public function index(Request $request)
    {
        $transactions = Transaction::with([
            'booking.tour',
        ])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }
}