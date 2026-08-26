<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorWithdrawalController extends Controller
{
    /**
     * Display vendor withdrawal history.
     */
    public function index()
    {
        $vendor = $this->getVendor();

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
        | Withdrawal History
        |--------------------------------------------------------------------------
        */

        $withdrawals = Withdrawal::where(
                'vendor_id',
                $vendor->id
            )
            ->latest()
            ->paginate(10);


        /*
        |--------------------------------------------------------------------------
        | Withdrawal Statistics
        |--------------------------------------------------------------------------
        */

        $totalWithdrawals = Withdrawal::where(
                'vendor_id',
                $vendor->id
            )
            ->whereIn(
                'status',
                [
                    'approved',
                    'completed',
                ]
            )
            ->sum('amount');


        $pendingWithdrawals = Withdrawal::where(
                'vendor_id',
                $vendor->id
            )
            ->where('status', 'pending')
            ->sum('amount');


        $rejectedWithdrawals = Withdrawal::where(
                'vendor_id',
                $vendor->id
            )
            ->where('status', 'rejected')
            ->sum('amount');


        return view(
            'vendor.withdrawals.index',
            compact(
                'wallet',
                'withdrawals',
                'totalWithdrawals',
                'pendingWithdrawals',
                'rejectedWithdrawals'
            )
        );
    }


    /**
     * Store withdrawal request.
     */
    public function store(Request $request)
    {
        $vendor = $this->getVendor();


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'amount' => [
                'required',
                'numeric',
                'min:1',
            ],

            'method' => [
                'required',
                'in:bkash,nagad,bank',
            ],

            'account_details' => [
                'required',
                'string',
                'max:1000',
            ],

        ]);


        $amount = round(
            (float) $validated['amount'],
            2
        );


        /*
        |--------------------------------------------------------------------------
        | Process Withdrawal
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $vendor,
            $validated,
            $amount
        ) {

            /*
            |--------------------------------------------------------------------------
            | Lock Wallet
            |--------------------------------------------------------------------------
            */

            $wallet = Wallet::where(
                'vendor_id',
                $vendor->id
            )
            ->lockForUpdate()
            ->first();


            /*
            |--------------------------------------------------------------------------
            | Create Wallet If Missing
            |--------------------------------------------------------------------------
            */

            if (!$wallet) {

                $wallet = Wallet::create([

                    'vendor_id' =>
                        $vendor->id,

                    'balance' =>
                        0,

                    'pending_balance' =>
                        0,

                    'total_earned' =>
                        0,

                    'total_withdrawn' =>
                        0,

                ]);

            }


            /*
            |--------------------------------------------------------------------------
            | Check Available Balance
            |--------------------------------------------------------------------------
            */

            if (
                $amount >
                (float) $wallet->balance
            ) {

                abort(
                    422,
                    'Insufficient wallet balance.'
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Prevent Multiple Pending Withdrawal
            |--------------------------------------------------------------------------
            */

            $pendingWithdrawalExists =
                Withdrawal::where(
                    'vendor_id',
                    $vendor->id
                )
                ->where(
                    'status',
                    'pending'
                )
                ->exists();


            if ($pendingWithdrawalExists) {

                abort(
                    422,
                    'You already have a pending withdrawal request.'
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Generate Withdrawal Reference
            |--------------------------------------------------------------------------
            */

            $withdrawalReference =
                'WD-' .
                now()->format('YmdHis') .
                '-' .
                strtoupper(
                    substr(
                        uniqid(),
                        -6
                    )
                );


            /*
            |--------------------------------------------------------------------------
            | Create Withdrawal
            |--------------------------------------------------------------------------
            */

            $withdrawal = Withdrawal::create([

                'vendor_id' =>
                    $vendor->id,

                'amount' =>
                    $amount,

                'method' =>
                    $validated['method'],

                'account_details' =>
                    $validated['account_details'],

                'status' =>
                    'pending',

                /*
                |--------------------------------------------------------------
                | Only include this if your withdrawals table has
                | withdrawal_code / reference column.
                |--------------------------------------------------------------
                */

                // 'withdrawal_code' => $withdrawalReference,

            ]);


            /*
            |--------------------------------------------------------------------------
            | Reserve Wallet Balance
            |--------------------------------------------------------------------------
            */

            $wallet->balance =
                round(
                    (float) $wallet->balance -
                    $amount,
                    2
                );

            $wallet->save();


            /*
            |--------------------------------------------------------------------------
            | Create Wallet Transaction
            |--------------------------------------------------------------------------
            */

            WalletTransaction::create([

                'vendor_id' =>
                    $vendor->id,

                /*
                |--------------------------------------------------------------
                | If wallet_transactions.booking_id is nullable.
                |--------------------------------------------------------------
                */

                'booking_id' =>
                    null,

                'type' =>
                    'debit',

                'amount' =>
                    $amount,

                'status' =>
                    'pending',

                'note' =>
                    'Withdrawal request #' .
                    $withdrawal->id .
                    ' submitted and amount reserved from wallet.',

            ]);

        });


        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'vendor.withdrawals.index'
            )
            ->with(
                'success',
                'Withdrawal request submitted successfully. The amount has been reserved from your wallet and is waiting for admin approval.'
            );
    }


    /**
     * Cancel own pending withdrawal request.
     *
     * Vendor can cancel only a pending request.
     */
    public function cancel(
        Withdrawal $withdrawal
    ) {

        $vendor = $this->getVendor();


        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        abort_unless(

            $withdrawal->vendor_id === $vendor->id,

            403,

            'You are not authorized to manage this withdrawal.'

        );


        /*
        |--------------------------------------------------------------------------
        | Status Check
        |--------------------------------------------------------------------------
        */

        if ($withdrawal->status !== 'pending') {

            return back()->with(
                'error',
                'Only pending withdrawal requests can be cancelled.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Cancel Withdrawal
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $withdrawal,
            $vendor
        ) {

            /*
            |--------------------------------------------------------------------------
            | Lock Wallet
            |--------------------------------------------------------------------------
            */

            $wallet = Wallet::where(
                'vendor_id',
                $vendor->id
            )
            ->lockForUpdate()
            ->first();


            /*
            |--------------------------------------------------------------------------
            | Find Pending Debit Transaction
            |--------------------------------------------------------------------------
            */

            $transaction =
                WalletTransaction::where(
                    'vendor_id',
                    $vendor->id
                )
                ->where(
                    'type',
                    'debit'
                )
                ->where(
                    'status',
                    'pending'
                )
                ->where(
                    'note',
                    'like',
                    '%Withdrawal request #' .
                    $withdrawal->id .
                    '%'
                )
                ->latest()
                ->first();


            /*
            |--------------------------------------------------------------------------
            | Return Amount To Wallet
            |--------------------------------------------------------------------------
            */

            if ($wallet) {

                $wallet->balance =
                    round(
                        (float) $wallet->balance +
                        (float) $withdrawal->amount,
                        2
                    );

                $wallet->save();

            }


            /*
            |--------------------------------------------------------------------------
            | Cancel Transaction
            |--------------------------------------------------------------------------
            */

            if ($transaction) {

                $transaction->update([

                    'status' =>
                        'cancelled',

                    'note' =>
                        'Withdrawal request #' .
                        $withdrawal->id .
                        ' cancelled. Amount returned to vendor wallet.',

                ]);

            }


            /*
            |--------------------------------------------------------------------------
            | Cancel Withdrawal
            |--------------------------------------------------------------------------
            */

            $withdrawal->update([

                'status' =>
                    'cancelled',

            ]);

        });


        return back()->with(
            'success',
            'Withdrawal request cancelled and amount returned to your wallet.'
        );
    }


    /**
     * Get logged-in vendor.
     */
    private function getVendor()
    {
        $vendor = Auth::user()->vendor;


        abort_unless(

            $vendor,

            403,

            'Vendor profile not found.'

        );


        return $vendor;
    }
}

