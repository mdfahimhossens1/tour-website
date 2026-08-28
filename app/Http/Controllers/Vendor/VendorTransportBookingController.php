<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Payment;
use App\Models\TransportBooking;
use App\Models\Vehicle;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class VendorTransportBookingController extends Controller
{
    /**
     * ----------------------------------------------------------
     * Display vendor transport bookings
     * ----------------------------------------------------------
     */
    public function index(Request $request)
    {
        $vendor = $this->getVendor();

        $query = TransportBooking::with([
            'user',
            'vendor',
            'vehicle',
            'payments',
            'latestPayment',
        ])
            ->where('vendor_id', $vendor->id);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'booking_code',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'pickup_location',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'dropoff_location',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas('user', function ($userQuery) use ($search) {

                    $userQuery
                        ->where(
                            'name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'email',
                            'like',
                            "%{$search}%"
                        );
                })

                ->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {

                    $vehicleQuery
                        ->where(
                            'name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'registration_number',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'brand',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'model',
                            'like',
                            "%{$search}%"
                        );
                });
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Booking Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('booking_status')) {

            $query->where(
                'booking_status',
                $request->booking_status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Payment Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_status')) {

            $query->where(
                'payment_status',
                $request->payment_status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('start_date')) {

            $query->whereDate(
                'start_date',
                '>=',
                $request->start_date
            );
        }


        if ($request->filled('end_date')) {

            $query->whereDate(
                'end_date',
                '<=',
                $request->end_date
            );
        }


        $bookings = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();


        return view(
            'vendor.transport-bookings.index',
            compact('bookings')
        );
    }


    /**
     * ----------------------------------------------------------
     * Show single transport booking
     * ----------------------------------------------------------
     */
    public function show(
        TransportBooking $booking
    ) {
        $this->authorizeBooking($booking);

        $booking->load([
            'user',
            'vendor',
            'vehicle',
            'payments',
            'latestPayment',
        ]);


        return view(
            'vendor.transport-bookings.show',
            compact('booking')
        );
    }



    /**
     * ----------------------------------------------------------
     * Approve payment
     * ----------------------------------------------------------
     */
public function approvePayment(
    TransportBooking $booking,
    Payment $payment
) {
    $vendor = $this->getVendor();

    // Security: Vendor নিজের booking কিনা
    if ($booking->vendor_id !== $vendor->id) {
        abort(403, 'Unauthorized booking access.');
    }

    // Verify this payment belongs to this transport booking
    if (
        $payment->paymentable_type !== TransportBooking::class ||
        (int) $payment->paymentable_id !== (int) $booking->id
    ) {
        return back()->with(
            'error',
            'This payment does not belong to this transport booking.'
        );
    }

    if ($payment->status !== 'pending') {
        return back()->with(
            'error',
            'This payment has already been processed.'
        );
    }

    DB::transaction(function () use ($booking, $payment) {

        $payment->update([
            'status' => 'paid',
        ]);

        $booking->update([
            'payment_status' => 'paid',
        ]);
    });

    return back()->with(
        'success',
        'Payment approved successfully.'
    );
}


    /**
     * ----------------------------------------------------------
     * Reject payment
     * ----------------------------------------------------------
     */
public function rejectPayment(
    TransportBooking $booking,
    Payment $payment
) {
    $vendor = $this->getVendor();

    // Security: Vendor নিজের booking কিনা
    if ($booking->vendor_id !== $vendor->id) {
        abort(403, 'Unauthorized booking access.');
    }

    // Verify payment belongs to this booking
    if (
        $payment->paymentable_type !== TransportBooking::class ||
        (int) $payment->paymentable_id !== (int) $booking->id
    ) {
        return back()->with(
            'error',
            'This payment does not belong to this transport booking.'
        );
    }

    if ($payment->status !== 'pending') {
        return back()->with(
            'error',
            'This payment has already been processed.'
        );
    }

    $payment->update([
        'status' => 'failed',
    ]);

    return back()->with(
        'success',
        'Payment rejected successfully.'
    );
}


    /**
     * ----------------------------------------------------------
     * Confirm booking
     * ----------------------------------------------------------
     */
    public function confirm(
        TransportBooking $booking
    ) {
        $this->authorizeBooking($booking);


        if (
            $booking->booking_status ===
            'confirmed'
        ) {

            return back()->with(
                'error',
                'Booking is already confirmed.'
            );
        }


        if (
            $booking->booking_status !==
            'pending'
        ) {

            return back()->with(
                'error',
                'Only pending bookings can be confirmed.'
            );
        }


        if (
            $booking->payment_status !==
            'paid'
        ) {

            return back()->with(
                'error',
                'Booking cannot be confirmed until payment is approved.'
            );
        }


        try {

            DB::transaction(function () use ($booking) {

                $lockedBooking =
                    TransportBooking::where(
                        'id',
                        $booking->id
                    )
                    ->lockForUpdate()
                    ->firstOrFail();


                if (
                    $lockedBooking->booking_status !==
                    'pending'
                ) {

                    throw new \RuntimeException(
                        'Booking is no longer pending.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Verify paid payment
                |--------------------------------------------------------------------------
                */

                $payment =
                    $lockedBooking->payments()
                        ->where(
                            'status',
                            'paid'
                        )
                        ->latest('id')
                        ->lockForUpdate()
                        ->first();


                if (!$payment) {

                    throw new \RuntimeException(
                        'Booking cannot be confirmed until payment is approved.'
                    );
                }


                $bookingAmount = round(
                    (float) $lockedBooking->total_amount,
                    2
                );


                $paymentAmount = round(
                    (float) $payment->amount,
                    2
                );


                if (
                    $paymentAmount !==
                    $bookingAmount
                ) {

                    throw new \RuntimeException(
                        'Payment amount does not match the booking total.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Verify vehicle availability
                |--------------------------------------------------------------------------
                */

                $this->ensureVehicleAvailable(
                    (int) $lockedBooking->vehicle_id,
                    Carbon::parse(
                        $lockedBooking->start_date
                    )->format('Y-m-d'),
                    Carbon::parse(
                        $lockedBooking->end_date
                    )->format('Y-m-d'),
                    (int) $lockedBooking->id
                );


                /*
                |--------------------------------------------------------------------------
                | Vendor
                |--------------------------------------------------------------------------
                */

                $vendor =
                    $lockedBooking->vendor;


                if (!$vendor) {

                    throw new \RuntimeException(
                        'Vendor profile not found for this booking.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Commission
                |--------------------------------------------------------------------------
                */

                $totalAmount = round(
                    (float) $lockedBooking->total_amount,
                    2
                );


                $commissionRate = round(
                    (float) (
                        $lockedBooking->commission_rate ??
                        $vendor->commission_rate ??
                        10
                    ),
                    2
                );


                $commissionRate = max(
                    0,
                    min(100, $commissionRate)
                );


                $adminCommission = round(
                    $totalAmount *
                    ($commissionRate / 100),
                    2
                );


                $vendorEarning = round(
                    $totalAmount -
                    $adminCommission,
                    2
                );


                /*
                |--------------------------------------------------------------------------
                | Update financial data
                |--------------------------------------------------------------------------
                */

                $lockedBooking->update([

                    'payment_status' =>
                        'paid',

                    'booking_status' =>
                        'confirmed',

                    'commission_rate' =>
                        $commissionRate,

                    'admin_commission' =>
                        $adminCommission,

                    'vendor_earning' =>
                        $vendorEarning,
                ]);


                /*
                |--------------------------------------------------------------------------
                | Commission record
                |--------------------------------------------------------------------------
                */

                Commission::updateOrCreate(

                    [
                        'transport_booking_id' =>
                            $lockedBooking->id,
                    ],

                    [
                        'booking_id' =>
                            null,

                        'room_booking_id' =>
                            null,

                        'total_amount' =>
                            $totalAmount,

                        'commission_rate' =>
                            $commissionRate,

                        'admin_earning' =>
                            $adminCommission,

                        'vendor_earning' =>
                            $vendorEarning,
                    ]
                );


                /*
                |--------------------------------------------------------------------------
                | Vendor Wallet
                |--------------------------------------------------------------------------
                */

                $wallet =
                    Wallet::firstOrCreate(

                        [
                            'vendor_id' =>
                                $vendor->id,
                        ],

                        [
                            'balance' =>
                                0,

                            'pending_balance' =>
                                0,

                            'total_earned' =>
                                0,

                            'total_withdrawn' =>
                                0,
                        ]
                    );


                /*
                |--------------------------------------------------------------------------
                | Prevent duplicate earning
                |--------------------------------------------------------------------------
                */

                $alreadyCredited =
                    WalletTransaction::where(
                        'booking_id',
                        $lockedBooking->id
                    )
                    ->where(
                        'type',
                        'credit'
                    )
                    ->exists();


                if (!$alreadyCredited) {

                    $wallet->pending_balance =
                        (float) $wallet->pending_balance +
                        $vendorEarning;

                    $wallet->save();


                    WalletTransaction::create([

                        'vendor_id' =>
                            $vendor->id,

                        'booking_id' =>
                            $lockedBooking->id,

                        'type' =>
                            'credit',

                        'amount' =>
                            $vendorEarning,

                        'status' =>
                            'pending',

                        'note' =>
                            'Transport booking #' .
                            $lockedBooking->booking_code .
                            ' earning pending until booking is completed.',
                    ]);
                }
            });

        } catch (\RuntimeException $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );

        } catch (\Throwable $e) {

            report($e);

            return back()->with(
                'error',
                'Unable to confirm transport booking. Please try again.'
            );
        }


        return back()->with(
            'success',
            'Transport booking confirmed successfully. Vendor earning has been added to pending balance.'
        );
    }


    /**
     * ----------------------------------------------------------
     * Complete booking
     * ----------------------------------------------------------
     */
    public function complete(
        TransportBooking $booking
    ) {
        $this->authorizeBooking($booking);


        if (
            $booking->booking_status !==
            'confirmed'
        ) {

            return back()->with(
                'error',
                'Only confirmed bookings can be completed.'
            );
        }


        if (
            $booking->payment_status !==
            'paid'
        ) {

            return back()->with(
                'error',
                'Booking cannot be completed until payment is paid.'
            );
        }


        try {

            DB::transaction(function () use ($booking) {

                $lockedBooking =
                    TransportBooking::where(
                        'id',
                        $booking->id
                    )
                    ->lockForUpdate()
                    ->firstOrFail();


                if (
                    $lockedBooking->booking_status !==
                    'confirmed'
                ) {

                    throw new \RuntimeException(
                        'Booking is no longer confirmed.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Release pending earning
                |--------------------------------------------------------------------------
                */

                $transaction =
                    WalletTransaction::where(
                        'booking_id',
                        $lockedBooking->id
                    )
                    ->where(
                        'type',
                        'credit'
                    )
                    ->where(
                        'status',
                        'pending'
                    )
                    ->lockForUpdate()
                    ->first();


                if ($transaction) {

                    $wallet =
                        Wallet::where(
                            'vendor_id',
                            $lockedBooking->vendor_id
                        )
                        ->lockForUpdate()
                        ->first();


                    if ($wallet) {

                        $amount =
                            (float) $transaction->amount;


                        $wallet->pending_balance =
                            max(
                                0,
                                (float) $wallet->pending_balance -
                                $amount
                            );


                        $wallet->balance =
                            (float) $wallet->balance +
                            $amount;


                        $wallet->total_earned =
                            (float) $wallet->total_earned +
                            $amount;


                        $wallet->save();


                        $transaction->update([

                            'status' =>
                                'completed',

                            'note' =>
                                'Transport booking #' .
                                $lockedBooking->booking_code .
                                ' earning released after booking completion.',
                        ]);
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | Complete booking
                |--------------------------------------------------------------------------
                */

                $lockedBooking->update([

                    'booking_status' =>
                        'completed',
                ]);
            });

        } catch (\RuntimeException $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );

        } catch (\Throwable $e) {

            report($e);

            return back()->with(
                'error',
                'Unable to complete transport booking.'
            );
        }


        return back()->with(
            'success',
            'Transport booking completed and vendor earning released successfully.'
        );
    }


    /**
     * ----------------------------------------------------------
     * Cancel booking
     * ----------------------------------------------------------
     */
    public function cancel(
        TransportBooking $booking
    ) {
        $this->authorizeBooking($booking);


        if (
            $booking->booking_status ===
            'cancelled'
        ) {

            return back()->with(
                'error',
                'Booking is already cancelled.'
            );
        }


        if (
            $booking->booking_status ===
            'completed'
        ) {

            return back()->with(
                'error',
                'A completed booking cannot be cancelled.'
            );
        }


        try {

            DB::transaction(function () use ($booking) {

                $lockedBooking =
                    TransportBooking::where(
                        'id',
                        $booking->id
                    )
                    ->lockForUpdate()
                    ->firstOrFail();


                if (
                    $lockedBooking->booking_status ===
                    'cancelled'
                ) {

                    throw new \RuntimeException(
                        'Booking is already cancelled.'
                    );
                }


                if (
                    $lockedBooking->booking_status ===
                    'completed'
                ) {

                    throw new \RuntimeException(
                        'A completed booking cannot be cancelled.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Reverse pending vendor earning
                |--------------------------------------------------------------------------
                */

                $transaction =
                    WalletTransaction::where(
                        'booking_id',
                        $lockedBooking->id
                    )
                    ->where(
                        'type',
                        'credit'
                    )
                    ->where(
                        'status',
                        'pending'
                    )
                    ->lockForUpdate()
                    ->first();


                if ($transaction) {

                    $wallet =
                        Wallet::where(
                            'vendor_id',
                            $lockedBooking->vendor_id
                        )
                        ->lockForUpdate()
                        ->first();


                    if ($wallet) {

                        $wallet->pending_balance =
                            max(
                                0,
                                (float) $wallet->pending_balance -
                                (float) $transaction->amount
                            );


                        $wallet->save();
                    }


                    $transaction->update([

                        'status' =>
                            'cancelled',

                        'note' =>
                            'Transport booking #' .
                            $lockedBooking->booking_code .
                            ' cancelled. Pending vendor earning reversed.',
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Remove commission
                |--------------------------------------------------------------------------
                */

                Commission::where(
                    'transport_booking_id',
                    $lockedBooking->id
                )->delete();


                /*
                |--------------------------------------------------------------------------
                | Cancel booking
                |--------------------------------------------------------------------------
                */

                $lockedBooking->update([

                    'booking_status' =>
                        'cancelled',
                ]);
            });

        } catch (\RuntimeException $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );

        } catch (\Throwable $e) {

            report($e);

            return back()->with(
                'error',
                'Unable to cancel transport booking.'
            );
        }


        return back()->with(
            'success',
            'Transport booking cancelled successfully.'
        );
    }

/**
 * ----------------------------------------------------------
 * Delete transport booking
 * ----------------------------------------------------------
 */
public function destroy(
    TransportBooking $booking
) {
    $this->authorizeBooking($booking);

    /*
    |--------------------------------------------------------------------------
    | Completed booking cannot be deleted
    |--------------------------------------------------------------------------
    */

    if ($booking->booking_status === 'completed') {

        return back()->with(
            'error',
            'A completed booking cannot be deleted.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Confirmed booking cannot be directly deleted
    |--------------------------------------------------------------------------
    */

    if ($booking->booking_status === 'confirmed') {

        return back()->with(
            'error',
            'A confirmed booking cannot be deleted. Please cancel the booking first.'
        );
    }


    try {

        DB::transaction(function () use ($booking) {

            /*
            |--------------------------------------------------------------------------
            | Lock booking
            |--------------------------------------------------------------------------
            */

            $lockedBooking =
                TransportBooking::where(
                    'id',
                    $booking->id
                )
                ->lockForUpdate()
                ->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | Re-check vendor ownership
            |--------------------------------------------------------------------------
            */

            $vendor = $this->getVendor();

            if (
                (int) $lockedBooking->vendor_id !==
                (int) $vendor->id
            ) {

                abort(
                    403,
                    'You are not authorized to delete this booking.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Completed protection
            |--------------------------------------------------------------------------
            */

            if (
                $lockedBooking->booking_status ===
                'completed'
            ) {

                throw new \RuntimeException(
                    'A completed booking cannot be deleted.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Confirmed protection
            |--------------------------------------------------------------------------
            */

            if (
                $lockedBooking->booking_status ===
                'confirmed'
            ) {

                throw new \RuntimeException(
                    'A confirmed booking cannot be deleted. Please cancel the booking first.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Safety check for wallet transaction
            |--------------------------------------------------------------------------
            |
            | Normally a pending vendor earning should only exist for a
            | confirmed booking. We do not delete such a transaction
            | blindly because it affects vendor balance.
            |
            */

            $walletTransaction =
                WalletTransaction::where(
                    'booking_id',
                    $lockedBooking->id
                )
                ->where(
                    'type',
                    'credit'
                )
                ->where(
                    'status',
                    'pending'
                )
                ->lockForUpdate()
                ->first();


            if ($walletTransaction) {

                throw new \RuntimeException(
                    'This booking has a pending vendor earning and cannot be deleted.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Delete commission
            |--------------------------------------------------------------------------
            */

            Commission::where(
                'transport_booking_id',
                $lockedBooking->id
            )->delete();


            /*
            |--------------------------------------------------------------------------
            | Delete wallet transactions
            |--------------------------------------------------------------------------
            |
            | Cancelled/completed transactions should normally already be
            | handled by the booking lifecycle.
            |
            */

            WalletTransaction::where(
                'booking_id',
                $lockedBooking->id
            )->delete();


            /*
            |--------------------------------------------------------------------------
            | Delete payments
            |--------------------------------------------------------------------------
            */

            Payment::where(
                'paymentable_type',
                TransportBooking::class
            )
            ->where(
                'paymentable_id',
                $lockedBooking->id
            )
            ->delete();


            /*
            |--------------------------------------------------------------------------
            | Delete booking
            |--------------------------------------------------------------------------
            */

            $lockedBooking->delete();
        });


    } catch (\RuntimeException $e) {

        return back()->with(
            'error',
            $e->getMessage()
        );


    } catch (\Throwable $e) {

        report($e);

        return back()->with(
            'error',
            'Unable to delete transport booking. Please try again.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Success
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route(
            'vendor.transport-bookings.index'
        )
        ->with(
            'success',
            'Transport booking deleted successfully.'
        );
}
    /**
     * ----------------------------------------------------------
     * Check vehicle availability
     * ----------------------------------------------------------
     *
     * Booking dates are inclusive.
     *
     * Example:
     * 10 - 12
     *
     * conflicts with:
     * 12 - 15
     *
     * because both include the 12th.
     */
    private function ensureVehicleAvailable(
        int $vehicleId,
        string $startDate,
        string $endDate,
        ?int $ignoreBookingId = null
    ): void {

        $vehicle =
            Vehicle::where(
                'id',
                $vehicleId
            )
            ->lockForUpdate()
            ->first();


        if (!$vehicle) {

            throw new \RuntimeException(
                'Vehicle not found.'
            );
        }


        if (!(bool) $vehicle->status) {

            throw new \RuntimeException(
                'This vehicle is currently unavailable.'
            );
        }


        $query =
            TransportBooking::where(
                'vehicle_id',
                $vehicleId
            )
            ->whereIn(
                'booking_status',
                [
                    'pending',
                    'confirmed',
                ]
            )
            ->whereDate(
                'start_date',
                '<=',
                $endDate
            )
            ->whereDate(
                'end_date',
                '>=',
                $startDate
            );


        if ($ignoreBookingId) {

            $query->where(
                'id',
                '!=',
                $ignoreBookingId
            );
        }


        $conflict =
            $query->exists();


        if ($conflict) {

            throw new \RuntimeException(
                'This vehicle is already booked for the selected dates.'
            );
        }
    }


    /**
     * ----------------------------------------------------------
     * Get logged-in vendor
     * ----------------------------------------------------------
     */
    private function getVendor()
    {
        $vendor =
            Auth::user()->vendor;


        abort_unless(
            $vendor,
            403,
            'Vendor profile not found.'
        );


        return $vendor;
    }


    /**
     * ----------------------------------------------------------
     * Ensure booking belongs to vendor
     * ----------------------------------------------------------
     */
    private function authorizeBooking(
        TransportBooking $booking
    ): void {

        $vendor =
            $this->getVendor();


        abort_unless(
            (int) $booking->vendor_id ===
            (int) $vendor->id,
            403,
            'You are not authorized to manage this booking.'
        );
    }
}