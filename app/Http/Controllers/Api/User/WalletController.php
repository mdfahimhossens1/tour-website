<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\UserWallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $wallet = UserWallet::firstOrCreate(
            ['user_id' => $user->id],
            [
                'balance' => 0,
                'pending_balance' => 0,
                'total_earned' => 0,
                'total_spent' => 0,
                'total_withdrawn' => 0,
            ]
        );

        return response()->json([
            'success' => true,
            'data' => [
                'balance' => $wallet->balance,
                'pending_balance' => $wallet->pending_balance,
                'total_earned' => $wallet->total_earned,
                'total_spent' => $wallet->total_spent,
                'total_withdrawn' => $wallet->total_withdrawn,
            ],
        ]);
    }
}