<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class VendorEarningController extends Controller
{
    /**
     * Vendor Earnings
     */
    public function index(Request $request)
    {
        $vendor = auth()->user()->vendor;

        if (!$vendor) {
            abort(403, 'Vendor profile not found.');
        }

        /*
        |--------------------------------------------------------------------------
        | Wallet
        |--------------------------------------------------------------------------
        */

        $wallet = Wallet::firstOrCreate(
            [
                'vendor_id' => $vendor->id,
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
        | Total Earnings
        |--------------------------------------------------------------------------
        */

        $totalEarned = WalletTransaction::where(
                'vendor_id',
                $vendor->id
            )
            ->where('type', 'credit')
            ->where('status', 'completed')
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | Total Withdrawn
        |--------------------------------------------------------------------------
        */

        $totalWithdrawn = WalletTransaction::where(
                'vendor_id',
                $vendor->id
            )
            ->where('type', 'debit')
            ->where('status', 'completed')
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | Available Balance
        |--------------------------------------------------------------------------
        */

        $availableBalance = $wallet->balance ?? 0;


        /*
        |--------------------------------------------------------------------------
        | Pending Balance
        |--------------------------------------------------------------------------
        */

        $pendingBalance = $wallet->pending_balance ?? 0;


        /*
        |--------------------------------------------------------------------------
        | Current Month Earnings
        |--------------------------------------------------------------------------
        */

        $monthlyEarning = WalletTransaction::where(
                'vendor_id',
                $vendor->id
            )
            ->where('type', 'credit')
            ->where('status', 'completed')
            ->whereMonth(
                'created_at',
                now()->month
            )
            ->whereYear(
                'created_at',
                now()->year
            )
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | Today's Earnings
        |--------------------------------------------------------------------------
        */

        $todayEarning = WalletTransaction::where(
                'vendor_id',
                $vendor->id
            )
            ->where('type', 'credit')
            ->where('status', 'completed')
            ->whereDate(
                'created_at',
                today()
            )
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | Earning Transactions
        |--------------------------------------------------------------------------
        */

        $transactions = WalletTransaction::with([
                'booking.tour',
            ])
            ->where(
                'vendor_id',
                $vendor->id
            )
            ->where('type', 'credit')
            ->latest()
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'vendor.earnings.index',
            compact(
                'wallet',
                'totalEarned',
                'totalWithdrawn',
                'availableBalance',
                'pendingBalance',
                'monthlyEarning',
                'todayEarning',
                'transactions'
            )
        );
    }
}