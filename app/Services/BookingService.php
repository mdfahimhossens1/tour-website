<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Payment;
use App\Models\Tour;
use App\Models\TourDate;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class BookingService
{
    /**
     * --------------------------------------------------------------------------
     * CREATE BOOKING
     * --------------------------------------------------------------------------
     */
    public function create(array $data, User $user)
    {
        return DB::transaction(function () use ($data, $user) {

            /*
            |--------------------------------------------------------------------------
            | 1. Get Tour
            |--------------------------------------------------------------------------
            */

            $tour = Tour::findOrFail($data['tour_id']);

            /*
            |--------------------------------------------------------------------------
            | 2. Get Tour Date
            |--------------------------------------------------------------------------
            */

            $tourDate = TourDate::where('id', $data['tour_date_id'])
                ->where('tour_id', $tour->id)
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | 3. Prevent Duplicate Active Booking
            |--------------------------------------------------------------------------
            |
            | Same user + same tour + same tour date:
            | pending / confirmed = cannot book again
            |
            | cancelled = can book again
            |
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
            |--------------------------------------------------------------------------
            | 4. Person Count
            |--------------------------------------------------------------------------
            */

            $personCount = (int) $data['person_count'];

            /*
            |--------------------------------------------------------------------------
            | 5. Validate Seats
            |--------------------------------------------------------------------------
            */

            $this->validateSeat(
                $tourDate,
                $personCount
            );

            /*
            |--------------------------------------------------------------------------
            | 6. Calculate Price
            |--------------------------------------------------------------------------
            */

            $price = $this->calculatePrice(
                $tour,
                $tourDate,
                $personCount
            );

            /*
            |--------------------------------------------------------------------------
            | 7. Apply Coupon
            |--------------------------------------------------------------------------
            */

            $coupon = $this->applyCoupon(
                $data['coupon_code'] ?? null,
                $price['subtotal']
            );

            /*
            |--------------------------------------------------------------------------
            | 8. Store Booking
            |--------------------------------------------------------------------------
            |
            | Vendor is NOT required here.
            |
            | If tour has vendor_id:
            |     booking.vendor_id = tour.vendor_id
            |
            | If tour doesn't have vendor:
            |     booking.vendor_id = null
            |
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
            |--------------------------------------------------------------------------
            | 9. Deduct Seats
            |--------------------------------------------------------------------------
            */

            $this->deductSeat(
                $tourDate,
                $personCount
            );

            /*
            |--------------------------------------------------------------------------
            | 10. Create Payment
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            | trx_id can NOT be null.
            |
            */

            $this->storePayment($booking);

            /*
            |--------------------------------------------------------------------------
            | 11. Create Transaction
            |--------------------------------------------------------------------------
            */

            $this->storeTransaction(
                $booking,
                $user
            );

            /*
            |--------------------------------------------------------------------------
            | 12. Return Result
            |--------------------------------------------------------------------------
            */

            return [
                'booking' => $booking->fresh([
                    'tour',
                    'tourDate',
                    'transaction',
                    'payments',
                ]),

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

        if ($personCount <= 0) {
            throw new Exception(
                'Invalid person count.'
            );
        }

        if ($tourDate->available_seat < $personCount) {
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
        |--------------------------------------------------------------------------
        | Special Price
        |--------------------------------------------------------------------------
        */

        $unitPrice = (
            $tourDate->special_price !== null &&
            (float) $tourDate->special_price > 0
        )
            ? (float) $tourDate->special_price
            : (float) $tour->price;

        /*
        |--------------------------------------------------------------------------
        | Subtotal
        |--------------------------------------------------------------------------
        */

        $subtotal = round(
            $unitPrice * $personCount,
            2
        );

        return [
            'unit_price' => $unitPrice,

            'person_count' => $personCount,

            'subtotal' => $subtotal,

            'total' => $subtotal,
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

        if (empty($couponCode)) {
            return [
                'discount' => 0,
                'code' => null,
                'total' => $total,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Find Coupon
        |--------------------------------------------------------------------------
        */

        $coupon = Coupon::where(
            'code',
            strtoupper(trim($couponCode))
        )
            ->where('status', 1)
            ->lockForUpdate()
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Invalid Coupon
        |--------------------------------------------------------------------------
        |
        | তোমার আগের logic অনুযায়ী invalid coupon হলে booking বন্ধ না করে
        | simply discount ছাড়া booking হবে।
        |
        */

        if (!$coupon) {
            return [
                'discount' => 0,
                'code' => null,
                'total' => $total,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Start Date
        |--------------------------------------------------------------------------
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
        |--------------------------------------------------------------------------
        | End Date
        |--------------------------------------------------------------------------
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
        |--------------------------------------------------------------------------
        | Maximum Usage
        |--------------------------------------------------------------------------
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
        |--------------------------------------------------------------------------
        | Calculate Discount
        |--------------------------------------------------------------------------
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
        |--------------------------------------------------------------------------
        | Discount Cannot Exceed Total
        |--------------------------------------------------------------------------
        */

        $discount = min(
            round($discount, 2),
            $total
        );

        /*
        |--------------------------------------------------------------------------
        | Final Total
        |--------------------------------------------------------------------------
        */

        $finalTotal = max(
            0,
            round($total - $discount, 2)
        );

        /*
        |--------------------------------------------------------------------------
        | Increase Coupon Usage
        |--------------------------------------------------------------------------
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

            /*
            |--------------------------------------------------------------------------
            | User
            |--------------------------------------------------------------------------
            */

            'user_id' => $user->id,

            /*
            |--------------------------------------------------------------------------
            | Vendor
            |--------------------------------------------------------------------------
            |
            | Vendor is optional.
            |
            | Tour-এর vendor থাকলে সেটি save হবে।
            | না থাকলে null হবে।
            |
            */

            'vendor_id' => $tour->vendor_id ?? null,

            /*
            |--------------------------------------------------------------------------
            | Tour
            |--------------------------------------------------------------------------
            */

            'tour_id' => $tour->id,

            /*
            |--------------------------------------------------------------------------
            | Tour Date
            |--------------------------------------------------------------------------
            */

            'tour_date_id' => $tourDate->id,

            /*
            |--------------------------------------------------------------------------
            | Booking Code
            |--------------------------------------------------------------------------
            */

            'booking_code' =>
                'BK-' . strtoupper(Str::random(8)),

            /*
            |--------------------------------------------------------------------------
            | Person Count
            |--------------------------------------------------------------------------
            */

            'person_count' =>
                (int) $data['person_count'],

            /*
            |--------------------------------------------------------------------------
            | Price
            |--------------------------------------------------------------------------
            */

            'unit_price' =>
                $price['unit_price'],

            'subtotal' =>
                $price['subtotal'],

            /*
            |--------------------------------------------------------------------------
            | Coupon
            |--------------------------------------------------------------------------
            */

            'coupon_code' =>
                $coupon['code'],

            'discount' =>
                $coupon['discount'],

            'total_amount' =>
                $coupon['total'],

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'payment_status' =>
                'pending',

            'booking_status' =>
                'pending',

            /*
            |--------------------------------------------------------------------------
            | Special Request
            |--------------------------------------------------------------------------
            */

            'special_request' =>
                $data['special_request'] ?? null,
        ]);
    }

    /**
     * --------------------------------------------------------------------------
     * DEDUCT SEAT
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
     * CREATE PAYMENT
     * --------------------------------------------------------------------------
     */
    protected function storePayment(
        Booking $booking
    ): Payment {

        return $booking->payments()->create([

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |--------------------------------------------------------------------------
            | payments.trx_id is NOT NULL.
            | তাই এখানে কখনো null যাবে না।
            */

            'trx_id' =>
                'PAY-' . strtoupper(Str::random(16)),

            /*
            |--------------------------------------------------------------------------
            | Payment Method
            |--------------------------------------------------------------------------
            |
            | Booking create হওয়ার সময় এখনো actual payment হয়নি।
            |
            */

            'payment_method' =>
                'manual',

            /*
            |--------------------------------------------------------------------------
            | Amount
            |--------------------------------------------------------------------------
            */

            'amount' =>
                $booking->total_amount,

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' =>
                'pending',

            /*
            |--------------------------------------------------------------------------
            | Paid At
            |--------------------------------------------------------------------------
            */

            'paid_at' =>
                null,
        ]);
    }

    /**
     * --------------------------------------------------------------------------
     * CREATE TRANSACTION
     * --------------------------------------------------------------------------
     */
    protected function storeTransaction(
        Booking $booking,
        User $user
    ): Transaction {

        return Transaction::create([

            /*
            |--------------------------------------------------------------------------
            | User
            |--------------------------------------------------------------------------
            */

            'user_id' =>
                $user->id,

            /*
            |--------------------------------------------------------------------------
            | Booking
            |--------------------------------------------------------------------------
            */

            'booking_id' =>
                $booking->id,

            /*
            |--------------------------------------------------------------------------
            | Transaction ID
            |--------------------------------------------------------------------------
            */

            'transaction_id' =>
                'TXN-' .
                strtoupper(Str::random(16)),

            /*
            |--------------------------------------------------------------------------
            | Payment Method
            |--------------------------------------------------------------------------
            */

            'payment_method' =>
                'manual',

            /*
            |--------------------------------------------------------------------------
            | Amount
            |--------------------------------------------------------------------------
            */

            'amount' =>
                $booking->total_amount,

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' =>
                'pending',
        ]);
    }
}