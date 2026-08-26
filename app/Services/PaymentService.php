<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Commission;
use App\Models\Payment;
use App\Models\RoomBooking;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /*
    |--------------------------------------------------------------------------
    | USER SUBMIT PAYMENT
    |--------------------------------------------------------------------------
    |
    | Supports:
    |
    | 1. Normal Tour Booking
    | 2. Room Booking
    |
    | Payment is initially pending.
    |
    */

    public function submitPayment(array $data): Payment
    {
        return DB::transaction(function () use ($data) {

            /*
            |--------------------------------------------------------------------------
            | Determine Booking Type
            |--------------------------------------------------------------------------
            */

            $bookingType = $data['booking_type'] ?? 'booking';

            /*
            |--------------------------------------------------------------------------
            | Find Booking
            |--------------------------------------------------------------------------
            */

            if ($bookingType === 'room_booking') {

                $booking = RoomBooking::lockForUpdate()
                    ->findOrFail($data['booking_id']);

            } else {

                $booking = Booking::lockForUpdate()
                    ->findOrFail($data['booking_id']);
            }


            /*
            |--------------------------------------------------------------------------
            | Already Paid Protection
            |--------------------------------------------------------------------------
            */

            if ($booking->payment_status === 'paid') {

                throw new \Exception(
                    'This booking has already been paid.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Already Confirmed Protection
            |--------------------------------------------------------------------------
            */

            if (
                isset($booking->booking_status) &&
                in_array(
                    $booking->booking_status,
                    [
                        'confirmed',
                        'checked_in',
                        'checked_out',
                    ]
                )
            ) {
                throw new \Exception(
                    'This booking has already been confirmed.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Duplicate Payment Protection
            |--------------------------------------------------------------------------
            */

            $alreadySubmitted = Payment::where(
                    'paymentable_id',
                    $booking->id
                )
                ->where(
                    'paymentable_type',
                    get_class($booking)
                )
                ->whereIn(
                    'status',
                    [
                        'pending',
                        'paid',
                    ]
                )
                ->exists();


            if ($alreadySubmitted) {

                throw new \Exception(
                    'Payment has already been submitted for this booking.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Duplicate Transaction ID Protection
            |--------------------------------------------------------------------------
            */

            if (!empty($data['trx_id'])) {

                $trxExists = Payment::where(
                    'trx_id',
                    $data['trx_id']
                )->exists();


                if ($trxExists) {

                    throw new \Exception(
                        'This Transaction ID has already been used.'
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Payment Amount
            |--------------------------------------------------------------------------
            |
            | Customer pays FULL booking amount.
            |
            | Example:
            |
            | Booking = 3000
            | Customer pays = 3000
            |
            | Commission is calculated separately.
            |
            */

            $amount = round(
                (float) $booking->total_amount,
                2
            );


            if ($amount <= 0) {

                throw new \Exception(
                    'Invalid booking payment amount.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Create Payment
            |--------------------------------------------------------------------------
            */

            $payment = Payment::create([

                'paymentable_id' =>
                    $booking->id,

                'paymentable_type' =>
                    get_class($booking),

                'trx_id' =>
                    $data['trx_id'],

                'payment_method' =>
                    $data['payment_method'],

                'amount' =>
                    $amount,

                'status' =>
                    'pending',

                'payment_data' => [

                    'note' =>
                        $data['note'] ?? null,

                ],

                'paid_at' =>
                    null,

            ]);


            /*
            |--------------------------------------------------------------------------
            | Old Tour Booking Transaction
            |--------------------------------------------------------------------------
            */

            if ($booking instanceof Booking) {

                Transaction::where(
                    'booking_id',
                    $booking->id
                )->update([

                    'payment_method' =>
                        $data['payment_method'],

                    'note' =>
                        $data['note'] ?? null,

                    'paid_at' =>
                        null,

                ]);
            }


            return $payment;
        });
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN / SUPER ADMIN APPROVE PAYMENT
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | Payment approval does NOT automatically confirm RoomBooking.
    |
    | Payment:
    |
    | pending → paid
    |
    | RoomBooking:
    |
    | pending → pending
    |
    | Vendor must confirm booking separately.
    |
    */

public function approvePayment(Payment $payment): Payment
{
    return DB::transaction(function () use ($payment) {

        /*
        |--------------------------------------------------------------------------
        | Prevent Double Approval
        |--------------------------------------------------------------------------
        */

        if ($payment->status === 'paid') {
            throw new \Exception(
                'Payment has already been approved.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get Booking
        |--------------------------------------------------------------------------
        */

        $booking = $payment->paymentable;

        if (!$booking) {
            throw new \Exception(
                'Booking associated with this payment was not found.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Lock Booking
        |--------------------------------------------------------------------------
        */

        if ($booking instanceof RoomBooking) {

            $booking = RoomBooking::lockForUpdate()
                ->findOrFail($booking->id);

        } elseif ($booking instanceof Booking) {

            $booking = Booking::lockForUpdate()
                ->findOrFail($booking->id);

        } else {

            throw new \Exception(
                'Unsupported booking type.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Already Paid Booking
        |--------------------------------------------------------------------------
        */

        if ($booking->payment_status === 'paid') {
            throw new \Exception(
                'This booking has already been paid.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Verify Payment Amount
        |--------------------------------------------------------------------------
        */

        $bookingAmount = round(
            (float) $booking->total_amount,
            2
        );

        $paymentAmount = round(
            (float) $payment->amount,
            2
        );

        if ($paymentAmount !== $bookingAmount) {
            throw new \Exception(
                'Payment amount does not match booking amount.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | APPROVE PAYMENT
        |--------------------------------------------------------------------------
        */

        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | UPDATE BOOKING PAYMENT STATUS
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Payment approval DOES NOT confirm booking.
        |
        | Vendor will confirm the booking separately.
        |
        */

        $booking->update([
            'payment_status' => 'paid',
        ]);

        /*
        |--------------------------------------------------------------------------
        | OLD TOUR TRANSACTION
        |--------------------------------------------------------------------------
        */

        if ($booking instanceof Booking) {

            Transaction::where(
                'booking_id',
                $booking->id
            )->update([

                'status' => 'success',

                'paid_at' => now(),

            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        |
        | Commission is NOT created here.
        |
        | Commission / vendor earning will be calculated
        | when vendor confirms the booking.
        |
        */

        return $payment->fresh();
    });
}


    /*
    |--------------------------------------------------------------------------
    | ADMIN / SUPER ADMIN REJECT PAYMENT
    |--------------------------------------------------------------------------
    */

    public function rejectPayment(Payment $payment): Payment
    {
        return DB::transaction(function () use ($payment) {

            /*
            |--------------------------------------------------------------------------
            | Lock Payment
            |--------------------------------------------------------------------------
            */

            $payment = Payment::lockForUpdate()
                ->findOrFail($payment->id);


            /*
            |--------------------------------------------------------------------------
            | Prevent Rejecting Paid Payment
            |--------------------------------------------------------------------------
            */

            if ($payment->status === 'paid') {

                throw new \Exception(
                    'Paid payment cannot be rejected.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Get Booking
            |--------------------------------------------------------------------------
            */

            $booking = $payment->paymentable;


            if (!$booking) {

                throw new \Exception(
                    'Booking associated with this payment was not found.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Update Payment
            |--------------------------------------------------------------------------
            */

            $payment->update([

                'status' =>
                    'failed',

                'paid_at' =>
                    null,

            ]);


            /*
            |--------------------------------------------------------------------------
            | Update Booking
            |--------------------------------------------------------------------------
            */

            $booking->update([

                'payment_status' =>
                    'failed',

            ]);


            /*
            |--------------------------------------------------------------------------
            | Old Tour Transaction
            |--------------------------------------------------------------------------
            */

            if ($booking instanceof Booking) {

                Transaction::where(
                    'booking_id',
                    $booking->id
                )->update([

                    'status' =>
                        'failed',

                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Return Updated Payment
            |--------------------------------------------------------------------------
            */

            return $payment->fresh();
        });
    }
}