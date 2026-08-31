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

class VendorTransportBookingController extends Controller
{
    /**
     * ----------------------------------------------------------
     * Display Vendor Transport Bookings
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
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {

                        $vehicleQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('registration_number', 'like', "%{$search}%")
                            ->orWhere('brand', 'like', "%{$search}%")
                            ->orWhere('model', 'like', "%{$search}%");
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Booking Status Filter
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
        | Payment Status Filter
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
     * Show Single Transport Booking
     * ----------------------------------------------------------
     */
    public function show(TransportBooking $booking)
    {
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
     * Approve Payment
     * ----------------------------------------------------------
     */
    public function approvePayment(
        TransportBooking $booking,
        Payment $payment
    ) {
        $this->authorizeBooking($booking);

        $this->validatePaymentOwnership(
            $booking,
            $payment
        );

        try {

            DB::transaction(function () use (
                $booking,
                $payment
            ) {

                $lockedPayment = Payment::where(
                    'id',
                    $payment->id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                $lockedBooking = TransportBooking::where(
                    'id',
                    $booking->id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($lockedPayment->status !== 'pending') {

                    throw new \RuntimeException(
                        'This payment has already been processed.'
                    );
                }

                if ($lockedBooking->booking_status === 'cancelled') {

                    throw new \RuntimeException(
                        'A cancelled booking payment cannot be approved.'
                    );
                }

                if ($lockedBooking->booking_status === 'completed') {

                    throw new \RuntimeException(
                        'A completed booking payment cannot be approved.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Verify Payment Amount
                |--------------------------------------------------------------------------
                */

                $paymentAmount = round(
                    (float) $lockedPayment->amount,
                    2
                );

                $bookingAmount = round(
                    (float) $lockedBooking->total_amount,
                    2
                );

                if ($paymentAmount !== $bookingAmount) {

                    throw new \RuntimeException(
                        'Payment amount does not match the booking total.'
                    );
                }

                $lockedPayment->update([
                    'status' => 'paid',
                ]);

                $lockedBooking->update([
                    'payment_status' => 'paid',
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
                'Unable to approve payment. Please try again.'
            );
        }

        return back()->with(
            'success',
            'Payment approved successfully.'
        );
    }


    /**
     * ----------------------------------------------------------
     * Reject Payment
     * ----------------------------------------------------------
     */
    public function rejectPayment(
        TransportBooking $booking,
        Payment $payment
    ) {
        $this->authorizeBooking($booking);

        $this->validatePaymentOwnership(
            $booking,
            $payment
        );

        try {

            DB::transaction(function () use (
                $booking,
                $payment
            ) {

                $lockedPayment = Payment::where(
                    'id',
                    $payment->id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($lockedPayment->status !== 'pending') {

                    throw new \RuntimeException(
                        'This payment has already been processed.'
                    );
                }

                $lockedPayment->update([
                    'status' => 'failed',
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
                'Unable to reject payment. Please try again.'
            );
        }

        return back()->with(
            'success',
            'Payment rejected successfully.'
        );
    }


    /**
     * ----------------------------------------------------------
     * Confirm Booking
     * ----------------------------------------------------------
     */
    public function confirm(TransportBooking $booking)
    {
        $this->authorizeBooking($booking);

        try {

            DB::transaction(function () use ($booking) {

                $lockedBooking = TransportBooking::with([
                    'vendor',
                ])
                    ->where('id', $booking->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                /*
                |--------------------------------------------------------------------------
                | Booking Status Validation
                |--------------------------------------------------------------------------
                */

                if (
                    $lockedBooking->booking_status ===
                    'confirmed'
                ) {

                    throw new \RuntimeException(
                        'Booking is already confirmed.'
                    );
                }

                if (
                    $lockedBooking->booking_status !==
                    'pending'
                ) {

                    throw new \RuntimeException(
                        'Only pending bookings can be confirmed.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Payment Validation
                |--------------------------------------------------------------------------
                */

                if (
                    $lockedBooking->payment_status !==
                    'paid'
                ) {

                    throw new \RuntimeException(
                        'Booking cannot be confirmed until payment is approved.'
                    );
                }

                $payment = $lockedBooking->payments()
                    ->where('status', 'paid')
                    ->latest('id')
                    ->lockForUpdate()
                    ->first();

                if (!$payment) {

                    throw new \RuntimeException(
                        'No approved payment was found for this booking.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Verify Payment Amount
                |--------------------------------------------------------------------------
                */

                if (
                    round((float) $payment->amount, 2) !==
                    round((float) $lockedBooking->total_amount, 2)
                ) {

                    throw new \RuntimeException(
                        'Payment amount does not match the booking total.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Vehicle Availability
                |--------------------------------------------------------------------------
                */

                $this->ensureVehicleAvailable(
                    (int) $lockedBooking->vehicle_id,
                    Carbon::parse(
                        $lockedBooking->start_date
                    )->toDateString(),
                    Carbon::parse(
                        $lockedBooking->end_date
                    )->toDateString(),
                    (int) $lockedBooking->id
                );

                /*
                |--------------------------------------------------------------------------
                | Vendor Validation
                |--------------------------------------------------------------------------
                */

                $vendor = $lockedBooking->vendor;

                if (!$vendor) {

                    throw new \RuntimeException(
                        'Vendor profile not found for this booking.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Financial Calculation
                |--------------------------------------------------------------------------
                */

                $totalAmount = round(
                    (float) $lockedBooking->total_amount,
                    2
                );

                $commissionRate = round(
                    (float) (
                        $lockedBooking->commission_rate
                        ?? $vendor->commission_rate
                        ?? 0
                    ),
                    2
                );

                $commissionRate = max(
                    0,
                    min(100, $commissionRate)
                );

                $adminCommission = round(
                    $totalAmount * ($commissionRate / 100),
                    2
                );

                $vendorEarning = round(
                    $totalAmount - $adminCommission,
                    2
                );

                /*
                |--------------------------------------------------------------------------
                | Update Booking
                |--------------------------------------------------------------------------
                */

                $lockedBooking->update([

                    'payment_status' => 'paid',

                    'booking_status' => 'confirmed',

                    'commission_rate' => $commissionRate,

                    'admin_commission' => $adminCommission,

                    'vendor_earning' => $vendorEarning,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Commission Record
                |--------------------------------------------------------------------------
                */

                Commission::updateOrCreate(

                    [
                        'transport_booking_id' =>
                            $lockedBooking->id,
                    ],

                    [
                        'booking_id' => null,

                        'room_booking_id' => null,

                        'total_amount' => $totalAmount,

                        'commission_rate' => $commissionRate,

                        'admin_earning' => $adminCommission,

                        'vendor_earning' => $vendorEarning,
                    ]
                );

                /*
                |--------------------------------------------------------------------------
                | Vendor Wallet
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
                | Prevent Duplicate Wallet Credit
                |--------------------------------------------------------------------------
                */

                $alreadyCredited = WalletTransaction::where(
                    'booking_id',
                    $lockedBooking->id
                )
                    ->where('type', 'credit')
                    ->exists();

                if (!$alreadyCredited) {

                    $wallet->pending_balance =
                        (float) $wallet->pending_balance +
                        $vendorEarning;

                    $wallet->save();

                    WalletTransaction::create([

                        'vendor_id' => $vendor->id,

                        'booking_id' => $lockedBooking->id,

                        'type' => 'credit',

                        'amount' => $vendorEarning,

                        'status' => 'pending',

                        'note' =>
                            'Transport booking #' .
                            $lockedBooking->booking_code .
                            ' earning pending until booking completion.',
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
     * Complete Booking
     * ----------------------------------------------------------
     */
    public function complete(TransportBooking $booking)
    {
        $this->authorizeBooking($booking);

        try {

            DB::transaction(function () use ($booking) {

                $lockedBooking = TransportBooking::where(
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
                        'Only confirmed bookings can be completed.'
                    );
                }

                if (
                    $lockedBooking->payment_status !==
                    'paid'
                ) {

                    throw new \RuntimeException(
                        'Booking cannot be completed until payment is paid.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Get Pending Earning Transaction
                |--------------------------------------------------------------------------
                */

                $transaction = WalletTransaction::where(
                    'booking_id',
                    $lockedBooking->id
                )
                    ->where('type', 'credit')
                    ->where('status', 'pending')
                    ->lockForUpdate()
                    ->first();

                if (!$transaction) {

                    throw new \RuntimeException(
                        'Pending vendor earning transaction not found.'
                    );
                }

                $wallet = Wallet::where(
                    'vendor_id',
                    $lockedBooking->vendor_id
                )
                    ->lockForUpdate()
                    ->first();

                if (!$wallet) {

                    throw new \RuntimeException(
                        'Vendor wallet not found.'
                    );
                }

                $amount = round(
                    (float) $transaction->amount,
                    2
                );

                /*
                |--------------------------------------------------------------------------
                | Release Earning
                |--------------------------------------------------------------------------
                */

                $wallet->pending_balance = max(
                    0,
                    round(
                        (float) $wallet->pending_balance - $amount,
                        2
                    )
                );

                $wallet->balance =
                    round(
                        (float) $wallet->balance + $amount,
                        2
                    );

                $wallet->total_earned =
                    round(
                        (float) $wallet->total_earned + $amount,
                        2
                    );

                $wallet->save();

                $transaction->update([

                    'status' => 'completed',

                    'note' =>
                        'Transport booking #' .
                        $lockedBooking->booking_code .
                        ' earning released after booking completion.',
                ]);

                /*
                |--------------------------------------------------------------------------
                | Complete Booking
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
     * Cancel Booking
     * ----------------------------------------------------------
     */
    public function cancel(TransportBooking $booking)
    {
        $this->authorizeBooking($booking);

        try {

            DB::transaction(function () use ($booking) {

                $lockedBooking = TransportBooking::where(
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
                | Reverse Pending Vendor Earning
                |--------------------------------------------------------------------------
                */

                $transaction = WalletTransaction::where(
                    'booking_id',
                    $lockedBooking->id
                )
                    ->where('type', 'credit')
                    ->where('status', 'pending')
                    ->lockForUpdate()
                    ->first();

                if ($transaction) {

                    $wallet = Wallet::where(
                        'vendor_id',
                        $lockedBooking->vendor_id
                    )
                        ->lockForUpdate()
                        ->first();

                    if ($wallet) {

                        $wallet->pending_balance = max(
                            0,
                            round(
                                (float) $wallet->pending_balance -
                                (float) $transaction->amount,
                                2
                            )
                        );

                        $wallet->save();
                    }

                    $transaction->update([

                        'status' => 'cancelled',

                        'note' =>
                            'Transport booking #' .
                            $lockedBooking->booking_code .
                            ' cancelled. Pending vendor earning reversed.',
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Remove Commission
                |--------------------------------------------------------------------------
                */

                Commission::where(
                    'transport_booking_id',
                    $lockedBooking->id
                )->delete();

                /*
                |--------------------------------------------------------------------------
                | Cancel Booking
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
     * Delete Transport Booking
     * ----------------------------------------------------------
     */
    public function destroy(TransportBooking $booking)
    {
        $this->authorizeBooking($booking);

        try {

            DB::transaction(function () use ($booking) {

                $lockedBooking = TransportBooking::where(
                    'id',
                    $booking->id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                /*
                |--------------------------------------------------------------------------
                | Status Protection
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

                if (
                    $lockedBooking->booking_status ===
                    'confirmed'
                ) {

                    throw new \RuntimeException(
                        'A confirmed booking cannot be deleted. Please cancel it first.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Safety: Pending Wallet Transaction
                |--------------------------------------------------------------------------
                */

                $pendingTransaction =
                    WalletTransaction::where(
                        'booking_id',
                        $lockedBooking->id
                    )
                        ->where('type', 'credit')
                        ->where('status', 'pending')
                        ->lockForUpdate()
                        ->exists();

                if ($pendingTransaction) {

                    throw new \RuntimeException(
                        'This booking has a pending vendor earning and cannot be deleted.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Delete Related Financial Records
                |--------------------------------------------------------------------------
                */

                Commission::where(
                    'transport_booking_id',
                    $lockedBooking->id
                )->delete();

                WalletTransaction::where(
                    'booking_id',
                    $lockedBooking->id
                )->delete();

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
                | Delete Booking
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
     * Check Vehicle Availability
     * ----------------------------------------------------------
     *
     * Only confirmed bookings block a vehicle.
     *
     * Pending bookings are requests and should not permanently
     * block the vehicle until payment and confirmation.
     */
    private function ensureVehicleAvailable(
        int $vehicleId,
        string $startDate,
        string $endDate,
        ?int $ignoreBookingId = null
    ): void {

        $vehicle = Vehicle::where(
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

        /*
        |--------------------------------------------------------------------------
        | Overlapping Date Check
        |--------------------------------------------------------------------------
        |
        | Existing: start <= requested end
        | AND
        | Existing: end >= requested start
        |
        | Dates are inclusive.
        |
        */

        $query = TransportBooking::where(
            'vehicle_id',
            $vehicleId
        )
            ->where(
                'booking_status',
                'confirmed'
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

        if ($query->exists()) {

            throw new \RuntimeException(
                'This vehicle is already confirmed for the selected dates.'
            );
        }
    }


    /**
     * ----------------------------------------------------------
     * Validate Payment Ownership
     * ----------------------------------------------------------
     */
    private function validatePaymentOwnership(
        TransportBooking $booking,
        Payment $payment
    ): void {

        if (
            $payment->paymentable_type !==
            TransportBooking::class ||

            (int) $payment->paymentable_id !==
            (int) $booking->id
        ) {

            abort(
                404,
                'This payment does not belong to this transport booking.'
            );
        }
    }


    /**
     * ----------------------------------------------------------
     * Get Logged-in Vendor
     * ----------------------------------------------------------
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


    /**
     * ----------------------------------------------------------
     * Authorize Booking Ownership
     * ----------------------------------------------------------
     */
    private function authorizeBooking(
        TransportBooking $booking
    ): void {

        $vendor = $this->getVendor();

        abort_unless(
            (int) $booking->vendor_id ===
            (int) $vendor->id,
            403,
            'You are not authorized to manage this booking.'
        );
    }
}