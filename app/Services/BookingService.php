<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Payment;
use App\Models\Tour;
use App\Models\TourDate;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    /**
     * --------------------------------------------------------------------------
     * CREATE BOOKING
     * --------------------------------------------------------------------------
     */
    public function create(array $data, User $user): array
    {
        return DB::transaction(function () use ($data, $user) {

            /*
            |----------------------------------------------------------------------
            | 1. Get Tour
            |----------------------------------------------------------------------
            */

            $tour = Tour::findOrFail($data['tour_id']);

            /*
            |----------------------------------------------------------------------
            | 2. Get Tour Date With Lock
            |----------------------------------------------------------------------
            */

            $tourDate = TourDate::where('id', $data['tour_date_id'])
                ->where('tour_id', $tour->id)
                ->lockForUpdate()
                ->first();

            if (!$tourDate) {
                throw new Exception(
                    'The selected tour date does not belong to this tour.'
                );
            }

            /*
            |----------------------------------------------------------------------
            | 3. Prevent Duplicate Active Booking
            |----------------------------------------------------------------------
            */

            $alreadyBooked = Booking::where('user_id', $user->id)
                ->where('tour_id', $tour->id)
                ->where('tour_date_id', $tourDate->id)
                ->whereIn('booking_status', [
                    'pending',
                    'confirmed',
                ])
                ->exists();

            if ($alreadyBooked) {
                throw new Exception(
                    'You have already booked this tour for this date.'
                );
            }

            /*
            |----------------------------------------------------------------------
            | 4. Person Count
            |----------------------------------------------------------------------
            */

            $personCount = (int) $data['person_count'];

            /*
            |----------------------------------------------------------------------
            | 5. Validate Available Seats
            |----------------------------------------------------------------------
            */

            $this->validateSeat(
                $tourDate,
                $personCount
            );

            /*
            |----------------------------------------------------------------------
            | 6. Calculate Price
            |----------------------------------------------------------------------
            */

            $price = $this->calculatePrice(
                $tour,
                $tourDate,
                $personCount
            );

            /*
            |----------------------------------------------------------------------
            | 7. Apply Coupon
            |----------------------------------------------------------------------
            */

            $coupon = $this->applyCoupon(
                $data['coupon_code'] ?? null,
                $price['subtotal']
            );

            /*
            |----------------------------------------------------------------------
            | 8. Create Booking
            |----------------------------------------------------------------------
            */

            $booking = $this->storeBooking(
                $user,
                $tour,
                $tourDate,
                $data,
                $price,
                $coupon
            );

            /*
            |----------------------------------------------------------------------
            | 9. Deduct Available Seats
            |----------------------------------------------------------------------
            */

            $this->deductSeat(
                $tourDate,
                $personCount
            );

            /*
            |----------------------------------------------------------------------
            | 10. Create Pending Payment
            |----------------------------------------------------------------------
            */

            $this->storePayment($booking);

            /*
            |----------------------------------------------------------------------
            | 11. Create Pending Transaction
            |----------------------------------------------------------------------
            */

            $this->storeTransaction(
                $booking,
                $user
            );

            /*
            |----------------------------------------------------------------------
            | 12. Return Fresh Booking
            |----------------------------------------------------------------------
            */

            $booking = $booking->fresh([
                'tour',
                'tourDate',
                'transaction',
                'payment',
            ]);

            return [
                'booking' => $booking,
                'discount' => $coupon['discount'],
                'coupon' => $coupon['code'],
            ];
        });
    }

    /**
     * --------------------------------------------------------------------------
     * VALIDATE SEAT AVAILABILITY
     * --------------------------------------------------------------------------
     */
    protected function validateSeat(
        TourDate $tourDate,
        int $personCount
    ): void {

        if ($personCount < 1) {
            throw new Exception(
                'Invalid person count.'
            );
        }

        if ((int) $tourDate->available_seat < $personCount) {
            throw new Exception(
                'Not enough seats available.'
            );
        }
    }

    /**
     * --------------------------------------------------------------------------
     * CALCULATE TOUR PRICE
     * --------------------------------------------------------------------------
     */
    protected function calculatePrice(
        Tour $tour,
        TourDate $tourDate,
        int $personCount
    ): array {

        /*
        |----------------------------------------------------------------------
        | Use Special Price When Available
        |----------------------------------------------------------------------
        */

        $unitPrice = (
            $tourDate->special_price !== null &&
            (float) $tourDate->special_price > 0
        )
            ? (float) $tourDate->special_price
            : (float) $tour->price;

        $subtotal = round(
            $unitPrice * $personCount,
            2
        );

        return [
            'unit_price' => $unitPrice,
            'person_count' => $personCount,
            'subtotal' => $subtotal,
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * APPLY COUPON
     * --------------------------------------------------------------------------
     */
    protected function applyCoupon(
        ?string $couponCode,
        float $total
    ): array {

        /*
        |----------------------------------------------------------------------
        | No Coupon
        |----------------------------------------------------------------------
        */

        if (empty($couponCode)) {
            return [
                'discount' => 0,
                'code' => null,
                'total' => $total,
            ];
        }

        /*
        |----------------------------------------------------------------------
        | Find Coupon
        |----------------------------------------------------------------------
        */

        $coupon = Coupon::where(
            'code',
            strtoupper(trim($couponCode))
        )
            ->where('status', 1)
            ->lockForUpdate()
            ->first();

        /*
        |----------------------------------------------------------------------
        | Invalid Coupon
        |----------------------------------------------------------------------
        */

        if (!$coupon) {
            throw new Exception(
                'Invalid coupon code.'
            );
        }

        /*
        |----------------------------------------------------------------------
        | Check Start Date
        |----------------------------------------------------------------------
        */

        if (
            $coupon->start_date &&
            now()->lt($coupon->start_date)
        ) {
            throw new Exception(
                'Coupon is not active yet.'
            );
        }

        /*
        |----------------------------------------------------------------------
        | Check End Date
        |----------------------------------------------------------------------
        */

        if (
            $coupon->end_date &&
            now()->gt($coupon->end_date)
        ) {
            throw new Exception(
                'Coupon has expired.'
            );
        }

        /*
        |----------------------------------------------------------------------
        | Check Usage Limit
        |----------------------------------------------------------------------
        */

        if (
            $coupon->max_usage &&
            $coupon->used_count >= $coupon->max_usage
        ) {
            throw new Exception(
                'Coupon usage limit exceeded.'
            );
        }

        /*
        |----------------------------------------------------------------------
        | Calculate Discount
        |----------------------------------------------------------------------
        */

        if (
            $coupon->type === 'percentage' ||
            $coupon->type === 'percent'
        ) {

            $discount = (
                $total * (float) $coupon->value
            ) / 100;

        } else {

            $discount = (float) $coupon->value;
        }

        /*
        |----------------------------------------------------------------------
        | Prevent Discount From Exceeding Total
        |----------------------------------------------------------------------
        */

        $discount = min(
            round($discount, 2),
            $total
        );

        $finalTotal = max(
            0,
            round($total - $discount, 2)
        );

        /*
        |----------------------------------------------------------------------
        | Increase Coupon Usage
        |----------------------------------------------------------------------
        */

        $coupon->increment('used_count');

        return [
            'discount' => $discount,
            'code' => $coupon->code,
            'total' => $finalTotal,
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * STORE BOOKING
     * --------------------------------------------------------------------------
     */
    protected function storeBooking(
        User $user,
        Tour $tour,
        TourDate $tourDate,
        array $data,
        array $price,
        array $coupon
    ): Booking {

        return Booking::create([

            'user_id' => $user->id,

            /*
            |----------------------------------------------------------------------
            | Vendor Is Optional
            |----------------------------------------------------------------------
            */

            'vendor_id' => $tour->vendor_id ?? null,

            'tour_id' => $tour->id,

            'tour_date_id' => $tourDate->id,

            /*
            |----------------------------------------------------------------------
            | Unique Booking Code
            |----------------------------------------------------------------------
            */

            'booking_code' =>
                'BK-' . strtoupper(Str::random(8)),

            'person_count' => $price['person_count'],

            'unit_price' => $price['unit_price'],

            'subtotal' => $price['subtotal'],

            'coupon_code' => $coupon['code'],

            'discount' => $coupon['discount'],

            'total_amount' => $coupon['total'],

            'payment_status' => 'pending',

            'booking_status' => 'pending',

            'special_request' =>
                $data['special_request'] ?? null,

        ]);
    }

    /**
     * --------------------------------------------------------------------------
     * DEDUCT AVAILABLE SEATS
     * --------------------------------------------------------------------------
     */
    protected function deductSeat(
        TourDate $tourDate,
        int $personCount
    ): void {

        $tourDate->decrement(
            'available_seat',
            $personCount
        );
    }

    /**
     * --------------------------------------------------------------------------
     * CREATE PENDING PAYMENT
     * --------------------------------------------------------------------------
     */
    protected function storePayment(
        Booking $booking
    ): Payment {

        return $booking->payments()->create([

            'trx_id' =>
                'PAY-' . strtoupper(Str::random(16)),

            /*
            |----------------------------------------------------------------------
            | Payment Method
            |----------------------------------------------------------------------
            | Actual payment method can be selected later.
            */

            'payment_method' =>
                'manual',

            'amount' =>
                $booking->total_amount,

            'status' =>
                'pending',

            'paid_at' =>
                null,

        ]);
    }

    /**
     * --------------------------------------------------------------------------
     * CREATE PENDING TRANSACTION
     * --------------------------------------------------------------------------
     */
    protected function storeTransaction(
        Booking $booking,
        User $user
    ): Transaction {

        return Transaction::create([

            'user_id' =>
                $user->id,

            'booking_id' =>
                $booking->id,

            'transaction_id' =>
                'TXN-' . strtoupper(Str::random(16)),

            'payment_method' =>
                'manual',

            'amount' =>
                $booking->total_amount,

            'status' =>
                'pending',

        ]);
    }
}