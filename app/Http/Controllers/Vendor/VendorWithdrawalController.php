<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Withdrawal;

class VendorWithdrawalController extends Controller
{
    public function index()
    {
        $vendor = auth()->user()->vendor;

        $withdrawals = Withdrawal::where('vendor_id', $vendor->id)
            ->latest()
            ->paginate(10);

        return view('vendor.withdrawals.index', compact('withdrawals'));
    }

   public function store(Request $request)
{
    $vendor = auth()->user()->vendor;

    $wallet = Wallet::where('vendor_id', $vendor->id)->first();

    $request->validate([
        'amount' => 'required|numeric|min:1',
        'method' => 'nullable|string',
        'account_number' => 'nullable|string',
    ]);

    if ($request->amount > $wallet->balance) {
        return back()->with('error', 'Insufficient balance');
    }

    Withdrawal::create([
        'vendor_id' => $vendor->id,
        'amount' => $request->amount,
        'method' => $request->method,
        'account_number' => $request->account_number,
        'status' => 'pending',
    ]);

    return back()->with('success', 'Withdraw request sent to admin');
}
}