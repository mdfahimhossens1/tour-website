<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Models\Wallet;

class AdminWithdrawalController extends Controller
{
    public function index()
    {
        $withdrawals = Withdrawal::with('vendor.user')
            ->latest()
            ->paginate(20);

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function approve($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Already processed');
        }

        $withdrawal->status = 'approved';
        $withdrawal->save();

        return back()->with('success', 'Withdrawal approved');
    }

    public function reject($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Already processed');
        }

        $wallet = Wallet::where('vendor_id', $withdrawal->vendor_id)->first();

        // refund money
        $wallet->balance += $withdrawal->amount;
        $wallet->total_withdrawn -= $withdrawal->amount;
        $wallet->save();

        $withdrawal->status = 'rejected';
        $withdrawal->save();

        return back()->with('success', 'Withdrawal rejected and refunded');
    }
}