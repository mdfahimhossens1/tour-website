<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\Commission;
use App\Models\TourDate;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /**
     * User Payment Submit
     */
    public function submitPayment(array $data)
    {
        return DB::transaction(function () use ($data) {

            $booking = Booking::with('tourDate')
                ->findOrFail($data['booking_id']);

            // Prevent double payment
            if ($booking->payment_status === 'paid') {
                throw new \Exception('Already paid booking');
            }

            // =========================
            // Create Payment Record
            // =========================
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'transaction_id' => $data['transaction_id'],
                'payment_method' => $data['payment_method'],
                'amount' => $booking->total_amount,
                'status' => 'pending',
                'payment_data' => [
                    'note' => $data['note'] ?? null,
                ],
            ]);

            // =========================
            // Update Transaction
            // =========================
            Transaction::where('booking_id', $booking->id)
                ->update([
                    'payment_method' => $data['payment_method'],
                    'status' => 'pending',
                ]);

            return $payment;
        });
    }

    /**
     * Admin Approve Payment
     */
    public function approvePayment(Payment $payment)
    {
        return DB::transaction(function () use ($payment) {

            $booking = Booking::with('tourDate')
                ->findOrFail($payment->booking_id);

            // =========================
            // Update Payment
            // =========================
            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            // =========================
            // Update Booking
            // =========================
            $booking->update([
                'payment_status' => 'paid',
                'booking_status' => 'confirmed',
            ]);

            // =========================
            // Commission Calculation
            // =========================
            $adminCommission = ($booking->total_amount * 10) / 100;
            $vendorEarning = $booking->total_amount - $adminCommission;

            Commission::create([
                'booking_id' => $booking->id,
                'total_amount' => $booking->total_amount,
                'commission_rate' => 10,
                'admin_earning' => $adminCommission,
                'vendor_earning' => $vendorEarning,
            ]);

            // =========================
            // Update Transaction
            // =========================
            Transaction::where('booking_id', $booking->id)
                ->update([
                    'status' => 'success',
                    'paid_at' => now(),
                ]);

            return $payment;
        });
    }

    /**
     * Reject Payment
     */
    public function rejectPayment(Payment $payment)
    {
        return DB::transaction(function () use ($payment) {

            $payment->update([
                'status' => 'failed',
            ]);

            Transaction::where('booking_id', $payment->booking_id)
                ->update([
                    'status' => 'failed',
                ]);

            return $payment;
        });
    }
}