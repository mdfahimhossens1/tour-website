<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;

class VendorWalletController extends Controller
{
    /**
     * Display vendor wallet.
     */
    public function index()
    {
        $vendor = Auth::user()->vendor;

        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );

        /*
        |--------------------------------------------------------------------------
        | Get / Create Wallet
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
        | Wallet Transactions
        |--------------------------------------------------------------------------
        */

        $transactions = WalletTransaction::with([
                'booking',
            ])
            ->where('vendor_id', $vendor->id)
            ->latest()
            ->paginate(15);


        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalCredits = WalletTransaction::where(
                'vendor_id',
                $vendor->id
            )
            ->where('type', 'credit')
            ->where('status', 'completed')
            ->sum('amount');


        $totalDebits = WalletTransaction::where(
                'vendor_id',
                $vendor->id
            )
            ->where('type', 'debit')
            ->where('status', 'completed')
            ->sum('amount');


        return view(
            'vendor.wallet.index',
            compact(
                'wallet',
                'transactions',
                'totalCredits',
                'totalDebits'
            )
        );
    }
}
