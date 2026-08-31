<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\RoomBooking;
use App\Models\TransportBooking;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /*
    |----------------------------------------------------------------------
    | USER SUBMIT PAYMENT
    |----------------------------------------------------------------------
    |
    | Supports:
    |
    | 1. Tour Booking
    | 2. Room Booking
    | 3. Transport Booking
    |
    | Payment is initially pending.
    |
    */

public function submitPayment(array $data): Payment
{
    return DB::transaction(function () use ($data) {

        /*
        |--------------------------------------------------------------
        | Find & Lock Booking
        |--------------------------------------------------------------
        */

        $booking = $this->findBooking(
            $data['booking_type'],
            $data['booking_id']
        );

        /*
        |--------------------------------------------------------------
        | Already Paid Protection
        |--------------------------------------------------------------
        */

        if ($booking->payment_status === 'paid') {
            throw new \Exception(
                'This booking has already been paid.'
            );
        }

        /*
        |--------------------------------------------------------------
        | Booking Status Protection
        |--------------------------------------------------------------
        */

        if (
            isset($booking->booking_status) &&
            in_array(
                $booking->booking_status,
                [
                    'confirmed',
                    'checked_in',
                    'checked_out',
                    'completed',
                ]
            )
        ) {
            throw new \Exception(
                'This booking cannot accept a new payment.'
            );
        }

        /*
        |--------------------------------------------------------------
        | Duplicate Transaction ID Protection
        |--------------------------------------------------------------
        */

        $existingTrx = Payment::where(
            'trx_id',
            $data['trx_id']
        )
            ->where(function ($query) use ($booking) {

                $query->where(
                    'paymentable_id',
                    '!=',
                    $booking->id
                )
                    ->orWhere(
                        'paymentable_type',
                        '!=',
                        get_class($booking)
                    );
            })
            ->exists();

        if ($existingTrx) {
            throw new \Exception(
                'This Transaction ID has already been used.'
            );
        }

        /*
        |--------------------------------------------------------------
        | Payment Amount
        |--------------------------------------------------------------
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
        |--------------------------------------------------------------
        | Find Existing Pending Payment
        |--------------------------------------------------------------
        */

        $payment = $booking->payments()
            ->where('status', 'pending')
            ->latest()
            ->lockForUpdate()
            ->first();

        /*
        |--------------------------------------------------------------
        | Update Existing Pending Payment
        |--------------------------------------------------------------
        */

        if ($payment) {

            $payment->update([

                'trx_id' =>
                    $data['trx_id'],

                'payment_method' =>
                    $data['payment_method'],

                'amount' =>
                    $amount,

                'payment_data' => [

                    'note' =>
                        $data['note'] ?? null,

                    'booking_type' =>
                        $data['booking_type'],

                ],

                'paid_at' =>
                    null,

            ]);

        } else {

            /*
            |--------------------------------------------------------------
            | Create New Payment
            |--------------------------------------------------------------
            */

            $payment = $booking->payments()->create([

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

                    'booking_type' =>
                        $data['booking_type'],

                ],

                'paid_at' =>
                    null,

            ]);
        }

        /*
        |--------------------------------------------------------------
        | Update Tour Booking Transaction
        |--------------------------------------------------------------
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

        return $payment->fresh();
    });
}

    /*
    |----------------------------------------------------------------------
    | APPROVE PAYMENT
    |----------------------------------------------------------------------
    */

    public function approvePayment(Payment $payment): Payment
    {
        return DB::transaction(function () use ($payment) {

            /*
            |--------------------------------------------------------------
            | Lock Payment
            |--------------------------------------------------------------
            */

            $payment = Payment::lockForUpdate()
                ->findOrFail($payment->id);


            /*
            |--------------------------------------------------------------
            | Prevent Double Approval
            |--------------------------------------------------------------
            */

            if ($payment->status === 'paid') {
                throw new \Exception(
                    'Payment has already been approved.'
                );
            }


            /*
            |--------------------------------------------------------------
            | Get Booking
            |--------------------------------------------------------------
            */

            $booking = $payment->paymentable;

            if (!$booking) {
                throw new \Exception(
                    'Booking associated with this payment was not found.'
                );
            }


            /*
            |--------------------------------------------------------------
            | Lock Actual Booking
            |--------------------------------------------------------------
            */

            $booking = $this->lockBooking($booking);


            /*
            |--------------------------------------------------------------
            | Prevent Already Paid
            |--------------------------------------------------------------
            */

            if ($booking->payment_status === 'paid') {
                throw new \Exception(
                    'This booking has already been paid.'
                );
            }


            /*
            |--------------------------------------------------------------
            | Verify Amount
            |--------------------------------------------------------------
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
            |--------------------------------------------------------------
            | Approve Payment
            |--------------------------------------------------------------
            */

            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);


            /*
            |--------------------------------------------------------------
            | Update Booking Payment Status
            |--------------------------------------------------------------
            */

            $booking->update([
                'payment_status' => 'paid',
            ]);


            /*
            |--------------------------------------------------------------
            | Old Tour Transaction Support
            |--------------------------------------------------------------
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


            return $payment->fresh();
        });
    }


    /*
    |----------------------------------------------------------------------
    | REJECT PAYMENT
    |----------------------------------------------------------------------
    */

    public function rejectPayment(Payment $payment): Payment
    {
        return DB::transaction(function () use ($payment) {

            /*
            |--------------------------------------------------------------
            | Lock Payment
            |--------------------------------------------------------------
            */

            $payment = Payment::lockForUpdate()
                ->findOrFail($payment->id);


            /*
            |--------------------------------------------------------------
            | Prevent Rejecting Paid Payment
            |--------------------------------------------------------------
            */

            if ($payment->status === 'paid') {
                throw new \Exception(
                    'Paid payment cannot be rejected.'
                );
            }


            $booking = $payment->paymentable;

            if (!$booking) {
                throw new \Exception(
                    'Booking associated with this payment was not found.'
                );
            }


            /*
            |--------------------------------------------------------------
            | Lock Booking
            |--------------------------------------------------------------
            */

            $booking = $this->lockBooking($booking);


            /*
            |--------------------------------------------------------------
            | Update Payment
            |--------------------------------------------------------------
            */

            $payment->update([
                'status' => 'failed',
                'paid_at' => null,
            ]);


            /*
            |--------------------------------------------------------------
            | Update Booking
            |--------------------------------------------------------------
            */

            $booking->update([
                'payment_status' => 'failed',
            ]);


            /*
            |--------------------------------------------------------------
            | Old Tour Transaction Support
            |--------------------------------------------------------------
            */

            if ($booking instanceof Booking) {

                Transaction::where(
                    'booking_id',
                    $booking->id
                )->update([
                    'status' => 'failed',
                ]);
            }


            return $payment->fresh();
        });
    }


    /*
    |----------------------------------------------------------------------
    | FIND BOOKING BY TYPE
    |----------------------------------------------------------------------
    */

    private function findBooking(
        string $bookingType,
        int $bookingId
    ) {
        return match ($bookingType) {

            'booking' =>
                Booking::lockForUpdate()
                    ->findOrFail($bookingId),

            'room_booking' =>
                RoomBooking::lockForUpdate()
                    ->findOrFail($bookingId),

            'transport_booking' =>
                TransportBooking::lockForUpdate()
                    ->findOrFail($bookingId),

            default =>
                throw new \Exception(
                    'Unsupported booking type.'
                ),
        };
    }


    /*
    |----------------------------------------------------------------------
    | LOCK BOOKING MODEL
    |----------------------------------------------------------------------
    */

    private function lockBooking($booking)
    {
        return match (get_class($booking)) {

            Booking::class =>
                Booking::lockForUpdate()
                    ->findOrFail($booking->id),

            RoomBooking::class =>
                RoomBooking::lockForUpdate()
                    ->findOrFail($booking->id),

            TransportBooking::class =>
                TransportBooking::lockForUpdate()
                    ->findOrFail($booking->id),

            default =>
                throw new \Exception(
                    'Unsupported booking type.'
                ),
        };
    }
}