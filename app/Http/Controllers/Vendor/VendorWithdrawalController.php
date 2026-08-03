<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorWithdrawalController extends Controller
{
    /**
     * Display withdrawal history.
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
        | Get Wallet
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


        return view(
            'vendor.withdrawals.index',
            compact(
                'wallet',
                'withdrawals'
            )
        );
    }


    /**
     * Store withdrawal request.
     */
    public function store(Request $request)
    {
        $vendor = Auth::user()->vendor;

        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );


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


        /*
        |--------------------------------------------------------------------------
        | Get Wallet
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
        | Check Balance
        |--------------------------------------------------------------------------
        */

        if (
            (float) $validated['amount'] >
            (float) $wallet->balance
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Insufficient wallet balance.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Multiple Pending Requests
        |--------------------------------------------------------------------------
        */

        $pendingWithdrawalExists = Withdrawal::where(
                'vendor_id',
                $vendor->id
            )
            ->where(
                'status',
                'pending'
            )
            ->exists();


        if ($pendingWithdrawalExists) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'You already have a pending withdrawal request.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Withdrawal Request
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $vendor,
            $validated
        ) {

            Withdrawal::create([

                'vendor_id' => $vendor->id,

                'amount' => $validated['amount'],

                'method' => $validated['method'],

                'account_details' =>
                    $validated['account_details'],

                'status' => 'pending',

            ]);
        });


        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('vendor.withdrawals.index')
            ->with(
                'success',
                'Withdrawal request submitted successfully. Please wait for admin approval.'
            );
    }
}
