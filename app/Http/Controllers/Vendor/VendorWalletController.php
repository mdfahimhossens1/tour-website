<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;

class VendorWalletController extends Controller
{
    public function index()
    {
        $vendor = auth()->user()->vendor;

        if (!$vendor) {
            abort(403, 'Vendor profile not found.');
        }

        $wallet = Wallet::firstOrCreate(
            ['vendor_id' => $vendor->id],
            [
                'balance' => 0,
                'pending_balance' => 0,
                'total_earned' => 0,
                'total_withdrawn' => 0,
            ]
        );

        $transactions = WalletTransaction::where('vendor_id', $vendor->id)
            ->latest()
            ->paginate(10);

        return view('vendor.wallet.index', compact('wallet', 'transactions'));
    }
}