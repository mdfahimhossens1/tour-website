<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Commission;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /**
     * --------------------------------------------------------------------------
     * User Submit Payment
     * --------------------------------------------------------------------------
     */
    public function submitPayment(array $data): Payment
    {
        return DB::transaction(function () use ($data) {

            /*
            |--------------------------------------------------------------------------
            | Lock Booking
            |--------------------------------------------------------------------------
            */

            $booking = Booking::lockForUpdate()->findOrFail(
                $data['booking_id']
            );

            /*
            |--------------------------------------------------------------------------
            | Prevent Already Paid Booking
            |--------------------------------------------------------------------------
            */

            if ($booking->payment_status === 'paid') {
                throw new \Exception('This booking has already been paid.');
            }

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Payment Submission
            |--------------------------------------------------------------------------
            */

            $alreadySubmitted = Payment::where('booking_id', $booking->id)
                ->whereIn('status', ['pending', 'paid'])
                ->exists();

            if ($alreadySubmitted) {
                throw new \Exception(
                    'Payment has already been submitted for this booking.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Create Payment Record
            |--------------------------------------------------------------------------
            */

            $payment = Payment::create([

                'booking_id'     => $booking->id,

                'trx_id'         => $data['trx_id'],

                'payment_method' => $data['payment_method'],

                'amount'         => $booking->total_amount,

                'status'         => 'pending',

                'payment_data'   => [

                    'note' => $data['note'] ?? null,

                ],

                'paid_at'        => null,

            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Transaction
            |--------------------------------------------------------------------------
            */

            Transaction::where('booking_id', $booking->id)
                ->update([

                    'payment_method' => $data['payment_method'],

                    'note' => $data['note'] ?? null,

                ]);

            return $payment;
        });
    }

    /**
     * --------------------------------------------------------------------------
     * Admin Approve Payment
     * --------------------------------------------------------------------------
     */
    public function approvePayment(Payment $payment): Payment
    {
        return DB::transaction(function () use ($payment) {

            /*
            |--------------------------------------------------------------------------
            | Lock Booking
            |--------------------------------------------------------------------------
            */

            $booking = Booking::lockForUpdate()
                ->findOrFail($payment->booking_id);

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
            | Update Payment
            |--------------------------------------------------------------------------
            */

            $payment->update([

                'status' => 'paid',

                'paid_at' => now(),

            ]);
            /*
            |--------------------------------------------------------------------------
            | Update Booking
            |--------------------------------------------------------------------------
            */

            $booking->update([

                'payment_status' => 'paid',

                'booking_status' => 'confirmed',

            ]);

            /*
            |--------------------------------------------------------------------------
            | Create Commission (Only Once)
            |--------------------------------------------------------------------------
            */

            if (! Commission::where('booking_id', $booking->id)->exists()) {

                $adminCommission = round(
                    ($booking->total_amount * 10) / 100,
                    2
                );

                $vendorEarning = round(
                    $booking->total_amount - $adminCommission,
                    2
                );

                Commission::create([

                    'booking_id' => $booking->id,

                    'total_amount' => $booking->total_amount,

                    'commission_rate' => 10,

                    'admin_earning' => $adminCommission,

                    'vendor_earning' => $vendorEarning,

                ]);

                /*
                |--------------------------------------------------------------------------
                | Optional: Save in Booking Table
                |--------------------------------------------------------------------------
                */

                $booking->update([

                    'admin_commission' => $adminCommission,

                    'vendor_earning' => $vendorEarning,

                ]);

            }

            /*
            |--------------------------------------------------------------------------
            | Update Transaction
            |--------------------------------------------------------------------------
            */

            Transaction::where('booking_id', $booking->id)
                ->update([

                    'status' => 'success',

                    'paid_at' => now(),

                ]);

            return $payment;

        });
    }

    /**
     * --------------------------------------------------------------------------
     * Admin Reject Payment
     * --------------------------------------------------------------------------
     */
    public function rejectPayment(Payment $payment): Payment
    {
        return DB::transaction(function () use ($payment) {

            /*
            |--------------------------------------------------------------------------
            | Prevent Reject After Payment
            |--------------------------------------------------------------------------
            */

            if ($payment->status === 'paid') {

                throw new \Exception(
                    'Paid payment cannot be rejected.'
                );

            }

            /*
            |--------------------------------------------------------------------------
            | Update Payment
            |--------------------------------------------------------------------------
            */

            $payment->update([

                'status' => 'failed',

            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Transaction
            |--------------------------------------------------------------------------
            */

            Transaction::where('booking_id', $payment->booking_id)
                ->update([

                    'status' => 'failed',

                ]);

            /*
            |--------------------------------------------------------------------------
            | Update Booking
            |--------------------------------------------------------------------------
            */

            Booking::where('id', $payment->booking_id)
                ->update([

                    'payment_status' => 'failed',

                ]);

            return $payment;

        });
    }
}