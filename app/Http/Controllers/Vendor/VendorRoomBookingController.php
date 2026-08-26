<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Room;
use App\Models\RoomBooking;
use App\Models\RoomAvailability;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class VendorRoomBookingController extends Controller
{
    /**
     * Display vendor room bookings.
     */
    public function index()
    {
        $vendor = $this->getVendor();

        $bookings = RoomBooking::with([
            'user',
            'vendor',
            'resort',
            'room',
            'guests',
            'payments',
        ])
            ->where('vendor_id', $vendor->id)
            ->latest()
            ->paginate(20);

        return view(
            'vendor.room-bookings.index',
            compact('bookings')
        );
    }


    /**
     * Show single room booking.
     */
    public function show(RoomBooking $booking)
    {
        $this->authorizeBooking($booking);

        $booking->load([
            'user',
            'vendor',
            'resort',
            'room',
            'guests',
            'payments',
        ]);

        return view(
            'vendor.room-bookings.show',
            compact('booking')
        );
    }


    /**
     * Show edit form.
     */
    public function edit(RoomBooking $booking)
    {
        $this->authorizeBooking($booking);

        if (
            in_array(
                $booking->booking_status,
                [
                    'checked_out',
                    'cancelled',
                ],
                true
            )
        ) {
            return back()->with(
                'error',
                'This booking can no longer be edited.'
            );
        }

        $booking->load([
            'user',
            'vendor',
            'resort',
            'room',
            'guests',
        ]);

        return view(
            'vendor.room-bookings.edit',
            compact('booking')
        );
    }


    /**
     * Update room booking.
     */
    public function update(
        Request $request,
        RoomBooking $booking
    ) {
        $this->authorizeBooking($booking);

        if (
            in_array(
                $booking->booking_status,
                [
                    'checked_out',
                    'cancelled',
                ],
                true
            )
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'This booking can no longer be edited.'
                );
        }

        $validated = $request->validate([
            'check_in' => [
                'required',
                'date',
            ],

            'check_out' => [
                'required',
                'date',
                'after:check_in',
            ],

            'adults' => [
                'required',
                'integer',
                'min:1',
            ],

            'children' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'booking_status' => [
                'required',
                Rule::in([
                    'pending',
                    'confirmed',
                    'checked_in',
                    'checked_out',
                    'cancelled',
                ]),
            ],

            'payment_status' => [
                'required',
                Rule::in([
                    'pending',
                    'paid',
                    'failed',
                    'refunded',
                ]),
            ],

            'special_request' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Payment Protection
        |--------------------------------------------------------------------------
        */

        if (
            $validated['payment_status'] !==
            $booking->payment_status
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Payment status cannot be changed from the edit page.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Status Protection
        |--------------------------------------------------------------------------
        */

        if (
            $booking->booking_status === 'checked_in' &&
            $validated['booking_status'] !== 'checked_in'
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'A checked-in booking cannot be moved to another status from edit.'
                );
        }


        if (
            $booking->booking_status === 'confirmed' &&
            !in_array(
                $validated['booking_status'],
                [
                    'confirmed',
                    'checked_in',
                ],
                true
            )
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'A confirmed booking can only remain confirmed or proceed to check-in.'
                );
        }


        if (
            $validated['booking_status'] === 'checked_out' &&
            $booking->booking_status !== 'checked_in'
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Only a checked-in booking can be checked out.'
                );
        }


        if (
            $validated['booking_status'] === 'cancelled'
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Please use the Cancel Booking action.'
                );
        }


        if (
            $booking->payment_status === 'refunded'
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'A refunded payment cannot be changed from the vendor panel.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Dates
        |--------------------------------------------------------------------------
        */

        $oldCheckIn = Carbon::parse(
            $booking->check_in
        )->format('Y-m-d');

        $oldCheckOut = Carbon::parse(
            $booking->check_out
        )->format('Y-m-d');

        $newCheckIn = Carbon::parse(
            $validated['check_in']
        )->format('Y-m-d');

        $newCheckOut = Carbon::parse(
            $validated['check_out']
        )->format('Y-m-d');

        $datesChanged =
            $oldCheckIn !== $newCheckIn ||
            $oldCheckOut !== $newCheckOut;


        /*
        |--------------------------------------------------------------------------
        | Date changes only while pending
        |--------------------------------------------------------------------------
        */

        if (
            $datesChanged &&
            $booking->booking_status !== 'pending'
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Stay dates cannot be changed after the booking has been confirmed.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | DATE CHANGED
        |--------------------------------------------------------------------------
        */

        if ($datesChanged) {

            try {

                DB::transaction(function () use (
                    $booking,
                    $validated,
                    $oldCheckIn,
                    $oldCheckOut,
                    $newCheckIn,
                    $newCheckOut
                ) {

                    $lockedBooking =
                        RoomBooking::where(
                            'id',
                            $booking->id
                        )
                        ->lockForUpdate()
                        ->firstOrFail();


                    $roomCount = max(
                        1,
                        (int) $lockedBooking->room_count
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Old availability
                    |--------------------------------------------------------------------------
                    */

                    $oldAvailability =
                        $this->getOrCreateAvailabilityRecords(
                            (int) $lockedBooking->room_id,
                            $oldCheckIn,
                            $oldCheckOut,
                            true
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Release old dates
                    |--------------------------------------------------------------------------
                    */

                    foreach ($oldAvailability as $day) {

                        $day->available_rooms = min(
                            (int) $day->total_rooms,
                            (int) $day->available_rooms +
                            $roomCount
                        );

                        $day->is_sold_out =
                            $day->available_rooms <= 0;

                        $day->save();
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | New availability
                    |--------------------------------------------------------------------------
                    */

                    $newAvailability =
                        $this->getOrCreateAvailabilityRecords(
                            (int) $lockedBooking->room_id,
                            $newCheckIn,
                            $newCheckOut,
                            true
                        );


                    foreach ($newAvailability as $day) {

                        if ((bool) $day->is_closed) {

                            throw new \RuntimeException(
                                'Room is closed on ' .
                                Carbon::parse($day->date)
                                    ->format('d M Y') .
                                '.'
                            );
                        }


                        if (
                            (int) $day->available_rooms <
                            $roomCount
                        ) {

                            throw new \RuntimeException(
                                'Not enough rooms available on ' .
                                Carbon::parse($day->date)
                                    ->format('d M Y') .
                                '.'
                            );
                        }
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Reserve new dates
                    |--------------------------------------------------------------------------
                    */

                    foreach ($newAvailability as $day) {

                        $day->available_rooms = max(
                            0,
                            (int) $day->available_rooms -
                            $roomCount
                        );

                        $day->is_sold_out =
                            $day->available_rooms <= 0;

                        $day->save();
                    }


                    $children =
                        (int) (
                            $validated['children'] ?? 0
                        );


                    $newNights =
                        Carbon::parse($newCheckIn)
                            ->diffInDays(
                                Carbon::parse($newCheckOut)
                            );


                    $lockedBooking->update([

                        'check_in' =>
                            $newCheckIn,

                        'check_out' =>
                            $newCheckOut,

                        'total_nights' =>
                            $newNights,

                        'adults' =>
                            $validated['adults'],

                        'children' =>
                            $children,

                        'booking_status' =>
                            $validated['booking_status'],

                        'special_request' =>
                            $validated['special_request']
                            ?? null,
                    ]);
                });

            } catch (\RuntimeException $e) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        $e->getMessage()
                    );
            }

        } else {

            /*
            |--------------------------------------------------------------------------
            | No date change
            |--------------------------------------------------------------------------
            */

            $checkIn = Carbon::parse(
                $validated['check_in']
            );

            $checkOut = Carbon::parse(
                $validated['check_out']
            );

            $totalNights =
                $checkIn->diffInDays($checkOut);

            $children =
                (int) (
                    $validated['children'] ?? 0
                );


            $booking->update([

                'check_in' =>
                    $validated['check_in'],

                'check_out' =>
                    $validated['check_out'],

                'total_nights' =>
                    $totalNights,

                'adults' =>
                    $validated['adults'],

                'children' =>
                    $children,

                'booking_status' =>
                    $validated['booking_status'],

                'special_request' =>
                    $validated['special_request']
                    ?? null,
            ]);
        }


        return redirect()
            ->route(
                'vendor.room-bookings.show',
                $booking
            )
            ->with(
                'success',
                'Room booking updated successfully.'
            );
    }


    /**
     * Approve payment.
     */
    public function approvePayment(
        Payment $payment
    ) {
        $booking = $payment->paymentable;

        if (!$booking instanceof RoomBooking) {

            return back()->with(
                'error',
                'This payment does not belong to a room booking.'
            );
        }

        $this->authorizeBooking($booking);

        if ($payment->status === 'paid') {

            return back()->with(
                'error',
                'Payment has already been approved.'
            );
        }

        if ($payment->status !== 'pending') {

            return back()->with(
                'error',
                'Only pending payments can be approved.'
            );
        }


        try {

            DB::transaction(function () use ($payment) {

                $lockedPayment =
                    Payment::where(
                        'id',
                        $payment->id
                    )
                    ->lockForUpdate()
                    ->firstOrFail();


                $booking =
                    $lockedPayment->paymentable;


                if (!$booking instanceof RoomBooking) {

                    throw new \RuntimeException(
                        'This payment does not belong to a room booking.'
                    );
                }


                $lockedBooking =
                    RoomBooking::where(
                        'id',
                        $booking->id
                    )
                    ->lockForUpdate()
                    ->firstOrFail();


                if (
                    $lockedPayment->status !==
                    'pending'
                ) {

                    throw new \RuntimeException(
                        'Only pending payments can be approved.'
                    );
                }


                if (
                    $lockedBooking->payment_status ===
                    'paid'
                ) {

                    throw new \RuntimeException(
                        'This booking has already been paid.'
                    );
                }


                $bookingAmount = round(
                    (float) $lockedBooking->total_amount,
                    2
                );

                $paymentAmount = round(
                    (float) $lockedPayment->amount,
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


                $lockedPayment->update([
                    'status' => 'paid',
                    'paid_at' => now(),
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
            'Payment approved successfully. You can now confirm the booking.'
        );
    }


    /**
     * Reject payment.
     */
    public function rejectPayment(
        Payment $payment
    ) {
        $booking = $payment->paymentable;

        if (!$booking instanceof RoomBooking) {

            return back()->with(
                'error',
                'This payment does not belong to a room booking.'
            );
        }

        $this->authorizeBooking($booking);

        if ($payment->status === 'paid') {

            return back()->with(
                'error',
                'Paid payment cannot be rejected.'
            );
        }

        if ($payment->status !== 'pending') {

            return back()->with(
                'error',
                'Only pending payments can be rejected.'
            );
        }


        try {

            DB::transaction(function () use ($payment) {

                $lockedPayment =
                    Payment::where(
                        'id',
                        $payment->id
                    )
                    ->lockForUpdate()
                    ->firstOrFail();


                $booking =
                    $lockedPayment->paymentable;


                if (!$booking instanceof RoomBooking) {

                    throw new \RuntimeException(
                        'This payment does not belong to a room booking.'
                    );
                }


                $lockedBooking =
                    RoomBooking::where(
                        'id',
                        $booking->id
                    )
                    ->lockForUpdate()
                    ->firstOrFail();


                if (
                    $lockedPayment->status ===
                    'paid'
                ) {

                    throw new \RuntimeException(
                        'Paid payment cannot be rejected.'
                    );
                }


                $lockedPayment->update([
                    'status' => 'failed',
                    'paid_at' => null,
                ]);


                $lockedBooking->update([
                    'payment_status' => 'failed',
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
     * Confirm booking.
     *
     * Availability is NOT deducted here.
     * It is already reserved when booking is created.
     */
    public function confirm(
        RoomBooking $booking
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
                    RoomBooking::where(
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
                | Verify payment
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
                | Verify availability only
                |--------------------------------------------------------------------------
                */

                $availability =
                    $this->getOrCreateAvailabilityRecords(
                        (int) $lockedBooking->room_id,
                        Carbon::parse(
                            $lockedBooking->check_in
                        )->format('Y-m-d'),
                        Carbon::parse(
                            $lockedBooking->check_out
                        )->format('Y-m-d'),
                        true
                    );


                foreach ($availability as $day) {

                    if ((bool) $day->is_closed) {

                        throw new \RuntimeException(
                            'Room is closed on ' .
                            Carbon::parse($day->date)
                                ->format('d M Y') .
                            '.'
                        );
                    }


                    if (
                        (int) $day->available_rooms < 0
                    ) {

                        throw new \RuntimeException(
                            'Invalid room availability detected.'
                        );
                    }
                }


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
                | Update booking financial data
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
                        'room_booking_id' =>
                            $lockedBooking->id,
                    ],

                    [
                        'booking_id' =>
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
                            'Room booking #' .
                            $lockedBooking->booking_code .
                            ' earning pending until guest checkout.',
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
                'Unable to confirm booking. Please try again.'
            );
        }


        return back()->with(
            'success',
            'Room booking confirmed successfully. Vendor earning has been added to pending balance.'
        );
    }


    /**
     * Check-in guest.
     */
    public function checkIn(
        RoomBooking $booking
    ) {
        $this->authorizeBooking($booking);

        if (
            $booking->booking_status !==
            'confirmed'
        ) {

            return back()->with(
                'error',
                'Only confirmed bookings can be checked in.'
            );
        }


        if (
            $booking->payment_status !==
            'paid'
        ) {

            return back()->with(
                'error',
                'Guest cannot be checked in until payment is completed.'
            );
        }


        $booking->update([
            'booking_status' => 'checked_in',
        ]);


        return back()->with(
            'success',
            'Guest checked in successfully.'
        );
    }


    /**
     * Check-out guest.
     */
    public function checkOut(
        RoomBooking $booking
    ) {
        $this->authorizeBooking($booking);

        if (
            $booking->booking_status !==
            'checked_in'
        ) {

            return back()->with(
                'error',
                'Only checked-in bookings can be checked out.'
            );
        }


        try {

            DB::transaction(function () use ($booking) {

                $lockedBooking =
                    RoomBooking::where(
                        'id',
                        $booking->id
                    )
                    ->lockForUpdate()
                    ->firstOrFail();


                if (
                    $lockedBooking->booking_status !==
                    'checked_in'
                ) {

                    throw new \RuntimeException(
                        'Booking is no longer checked in.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Release vendor pending earning
                |--------------------------------------------------------------------------
                */

                $wallet =
                    Wallet::where(
                        'vendor_id',
                        $lockedBooking->vendor_id
                    )
                    ->lockForUpdate()
                    ->first();


                if ($wallet) {

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
                                'Room booking #' .
                                $lockedBooking->booking_code .
                                ' earning released after guest checkout.',
                        ]);
                    }
                }


                $lockedBooking->update([
                    'booking_status' => 'checked_out',
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
                'Unable to complete checkout.'
            );
        }


        return back()->with(
            'success',
            'Guest checked out and vendor earning released successfully.'
        );
    }


    /**
     * Cancel booking.
     */
    public function cancel(
        RoomBooking $booking
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
            'checked_out'
        ) {

            return back()->with(
                'error',
                'A checked-out booking cannot be cancelled.'
            );
        }


        try {

            DB::transaction(function () use ($booking) {

                $lockedBooking =
                    RoomBooking::where(
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
                    'checked_out'
                ) {

                    throw new \RuntimeException(
                        'A checked-out booking cannot be cancelled.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Get availability
                |--------------------------------------------------------------------------
                */

                $availability =
                    $this->getOrCreateAvailabilityRecords(
                        (int) $lockedBooking->room_id,
                        Carbon::parse(
                            $lockedBooking->check_in
                        )->format('Y-m-d'),
                        Carbon::parse(
                            $lockedBooking->check_out
                        )->format('Y-m-d'),
                        true
                    );


                $roomCount = max(
                    1,
                    (int) $lockedBooking->room_count
                );


                /*
                |--------------------------------------------------------------------------
                | Return rooms
                |--------------------------------------------------------------------------
                */

                foreach ($availability as $day) {

                    $day->available_rooms =
                        min(
                            (int) $day->total_rooms,
                            (int) $day->available_rooms +
                            $roomCount
                        );

                    $day->is_sold_out =
                        $day->available_rooms <= 0;

                    $day->save();
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
                            'Room booking #' .
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
                    'room_booking_id',
                    $lockedBooking->id
                )->delete();


                /*
                |--------------------------------------------------------------------------
                | Cancel booking
                |--------------------------------------------------------------------------
                */

                $lockedBooking->update([
                    'booking_status' => 'cancelled',
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
                'Unable to cancel booking. Please try again.'
            );
        }


        return back()->with(
            'success',
            'Room booking cancelled successfully.'
        );
    }


    /**
     * Get or create availability records.
     *
     * IMPORTANT:
     * rooms.total_rooms is the source of truth.
     *
     * Existing availability records are synchronized
     * with the current room total.
     */
    private function getOrCreateAvailabilityRecords(
        int $roomId,
        string $checkIn,
        string $checkOut,
        bool $lock = true
    ) {
        $roomQuery = Room::where(
            'id',
            $roomId
        );

        if ($lock) {
            $roomQuery->lockForUpdate();
        }

        $room = $roomQuery->first();


        if (!$room) {

            throw new \RuntimeException(
                'Room not found.'
            );
        }


        $totalRooms =
            (int) $room->total_rooms;


        if ($totalRooms <= 0) {

            throw new \RuntimeException(
                'No rooms are configured for this room. Please set total rooms first.'
            );
        }


        $start =
            Carbon::parse($checkIn)
                ->startOfDay();

        $end =
            Carbon::parse($checkOut)
                ->startOfDay();


        if (!$start->lt($end)) {

            throw new \RuntimeException(
                'Invalid booking dates.'
            );
        }


        $records = collect();

        $current = $start->copy();


        while ($current->lt($end)) {

            $date =
                $current->toDateString();


            /*
            |--------------------------------------------------------------------------
            | Lock availability record
            |--------------------------------------------------------------------------
            */

            $availabilityQuery =
                RoomAvailability::where(
                    'room_id',
                    $roomId
                )
                ->whereDate(
                    'date',
                    $date
                );


            if ($lock) {
                $availabilityQuery->lockForUpdate();
            }


            $availability =
                $availabilityQuery->first();


            /*
            |--------------------------------------------------------------------------
            | Count actual occupied rooms
            |--------------------------------------------------------------------------
            |
            | This is the important fix.
            |
            | We don't blindly trust old available_rooms values.
            |
            */

            $bookedRooms =
                RoomBooking::where(
                    'room_id',
                    $roomId
                )
                ->whereIn(
                    'booking_status',
                    [
                        'pending',
                        'confirmed',
                        'checked_in',
                    ]
                )
                ->where(
                    'check_in',
                    '<=',
                    $date
                )
                ->where(
                    'check_out',
                    '>',
                    $date
                )
                ->sum(
                    'room_count'
                );


            $bookedRooms =
                max(
                    0,
                    (int) $bookedRooms
                );


            /*
            |--------------------------------------------------------------------------
            | Calculate real availability
            |--------------------------------------------------------------------------
            */

            $availableRooms =
                max(
                    0,
                    $totalRooms -
                    $bookedRooms
                );


            /*
            |--------------------------------------------------------------------------
            | Create record if missing
            |--------------------------------------------------------------------------
            */

            if (!$availability) {

                $availability =
                    RoomAvailability::create([

                        'room_id' =>
                            $roomId,

                        'date' =>
                            $date,

                        'price' =>
                            null,

                        'total_rooms' =>
                            $totalRooms,

                        'available_rooms' =>
                            $availableRooms,

                        'is_closed' =>
                            false,

                        'is_sold_out' =>
                            $availableRooms <= 0,
                    ]);


                if ($lock) {

                    $availability =
                        RoomAvailability::where(
                            'id',
                            $availability->id
                        )
                        ->lockForUpdate()
                        ->first();
                }

            } else {

                /*
                |--------------------------------------------------------------------------
                | Synchronize existing record
                |--------------------------------------------------------------------------
                |
                | If room total_rooms changed from 1 to 7,
                | old availability records will become 7 as well.
                |
                */

                $availability->total_rooms =
                    $totalRooms;


                /*
                | Keep manually closed status.
                */

                $availability->available_rooms =
                    $availableRooms;


                $availability->is_sold_out =
                    $availableRooms <= 0;


                $availability->save();
            }


            /*
            |--------------------------------------------------------------------------
            | Validate
            |--------------------------------------------------------------------------
            */

            if (
                (int) $availability->total_rooms <= 0
            ) {

                throw new \RuntimeException(
                    'No rooms are configured in availability for ' .
                    $date .
                    '.'
                );
            }


            $records->push(
                $availability
            );


            $current->addDay();
        }


        return $records;
    }


    /**
     * Get logged-in vendor.
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
     * Ensure booking belongs to logged-in vendor.
     */
    private function authorizeBooking(
        RoomBooking $booking
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