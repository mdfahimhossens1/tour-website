<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\DB;

class AdminWithdrawalController extends Controller
{
    /**
     * Display withdrawal requests.
     */
    public function index()
    {
        $withdrawals = Withdrawal::with('vendor')
            ->latest()
            ->paginate(15);

        return view(
            'admin.withdrawals.index',
            compact('withdrawals')
        );
    }


    /**
     * Show withdrawal details.
     */
    public function show($id)
    {
        $withdrawal = Withdrawal::with('vendor')
            ->findOrFail($id);

        return view(
            'admin.withdrawals.show',
            compact('withdrawal')
        );
    }


    /**
     * Approve withdrawal request.
     */
    public function approve($id)
    {
        DB::transaction(function () use ($id) {

            $withdrawal = Withdrawal::lockForUpdate()
                ->with('vendor')
                ->findOrFail($id);


            /*
            |--------------------------------------------------------------------------
            | Already Processed
            |--------------------------------------------------------------------------
            */

            if ($withdrawal->status !== 'pending') {
                abort(
                    422,
                    'This withdrawal request has already been processed.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Vendor Wallet
            |--------------------------------------------------------------------------
            */

            $wallet = Wallet::where(
                    'vendor_id',
                    $withdrawal->vendor_id
                )
                ->lockForUpdate()
                ->first();


            if (!$wallet) {
                abort(
                    422,
                    'Vendor wallet not found.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Check Balance
            |--------------------------------------------------------------------------
            */

            if (
                (float) $wallet->balance <
                (float) $withdrawal->amount
            ) {
                abort(
                    422,
                    'Vendor does not have sufficient wallet balance.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Deduct Wallet Balance
            |--------------------------------------------------------------------------
            */

            $wallet->balance -= $withdrawal->amount;

            $wallet->total_withdrawn += $withdrawal->amount;

            $wallet->save();


            /*
            |--------------------------------------------------------------------------
            | Mark Withdrawal Approved
            |--------------------------------------------------------------------------
            */

            $withdrawal->status = 'approved';

            $withdrawal->save();


            /*
            |--------------------------------------------------------------------------
            | Create Debit Transaction
            |--------------------------------------------------------------------------
            */

            WalletTransaction::create([
                'vendor_id' => $withdrawal->vendor_id,
                'booking_id' => null,
                'type' => 'debit',
                'amount' => $withdrawal->amount,
                'status' => 'completed',
                'note' => 'Withdrawal #' . $withdrawal->id . ' approved',
            ]);
        });


        return redirect()
            ->route('admin.withdrawals.index')
            ->with(
                'success',
                'Withdrawal approved and wallet balance deducted successfully.'
            );
    }


    /**
     * Reject withdrawal request.
     */
    public function reject($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Only Pending Withdrawal Can Be Rejected
        |--------------------------------------------------------------------------
        */

        if ($withdrawal->status !== 'pending') {
            return back()->with(
                'error',
                'This withdrawal request has already been processed.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Reject Withdrawal
        |--------------------------------------------------------------------------
        */

        $withdrawal->status = 'rejected';

        $withdrawal->save();


        return redirect()
            ->route('admin.withdrawals.index')
            ->with(
                'success',
                'Withdrawal request rejected successfully.'
            );
    }
}