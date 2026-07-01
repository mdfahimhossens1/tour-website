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
     * Create Booking
     */
    public function create(array $data, User $user)
    {
        return DB::transaction(function () use ($data, $user) {

            //
            // 1
            //

            $tour = Tour::findOrFail($data['tour_id']);

            //
            // 2
            //

            $tourDate = TourDate::lockForUpdate()
                ->findOrFail($data['tour_date_id']);

            //
            // 3
            //

            $this->validateSeat(
                $tourDate,
                $data['person_count']
            );

            //
            // 4
            //

            $price = $this->calculatePrice(
                $tour,
                $tourDate,
                $data['person_count']
            );

            //
            // 5
            //

            $coupon = $this->applyCoupon(
                $data['coupon_code'] ?? null,
                $price['total']
            );

            //
            // 6
            //

            $booking = $this->storeBooking(
                $user,
                $tour,
                $tourDate,
                $data,
                $price,
                $coupon
            );

            //
            // 7
            //

            $this->deductSeat(
                $tourDate,
                $data['person_count']
            );

            //
            // 8
            //

            $this->storeTransaction(
                $booking,
                $user
            );

            //
            // 9
            //

            $this->storePayment(
                $booking
            );

            return [

                'booking' => $booking,

                'discount' => $coupon['discount'],

                'coupon' => $coupon['code'],

            ];

        });
    }

    /**
 * --------------------------------------------------------------------------
 * Validate Seat Availability
 * --------------------------------------------------------------------------
 */
protected function validateSeat(TourDate $tourDate, int $personCount): void
{
    if ($personCount <= 0) {
        throw new \Exception('Invalid person count.');
    }

    if ($tourDate->available_seat < $personCount) {
        throw new \Exception('Not enough seats available.');
    }
}

/**
 * --------------------------------------------------------------------------
 * Calculate Tour Price
 * --------------------------------------------------------------------------
 */
protected function calculatePrice(
    Tour $tour,
    TourDate $tourDate,
    int $personCount
): array {

    // যদি Tour Date এ Special Price থাকে তাহলে সেটা ব্যবহার করবে
    $unitPrice = $tourDate->special_price
        ? (float) $tourDate->special_price
        : (float) $tour->price;

    $subtotal = $unitPrice * $personCount;

    return [

        'unit_price' => $unitPrice,

        'person_count' => $personCount,

        'subtotal' => $subtotal,

        'total' => $subtotal,

    ];
}

/**
 * --------------------------------------------------------------------------
 * Apply Coupon
 * --------------------------------------------------------------------------
 */
protected function applyCoupon(?string $couponCode, float $total): array
{
    $discount = 0;
    $code = null;

    if (empty($couponCode)) {

        return [
            'discount' => 0,
            'code' => null,
            'total' => $total,
        ];
    }

    $coupon = Coupon::where('code', strtoupper($couponCode))
        ->where('status', 1)
        ->first();

    if (!$coupon) {

        return [
            'discount' => 0,
            'code' => null,
            'total' => $total,
        ];
    }

    if ($coupon->start_date && now()->lt($coupon->start_date)) {
        throw new \Exception('Coupon is not active yet.');
    }

    if ($coupon->end_date && now()->gt($coupon->end_date)) {
        throw new \Exception('Coupon has expired.');
    }

    if (
        $coupon->max_usage &&
        $coupon->used_count >= $coupon->max_usage
    ) {
        throw new \Exception('Coupon usage limit exceeded.');
    }

    if ($coupon->type === 'percentage') {

        $discount = ($total * $coupon->value) / 100;

    } else {

        $discount = $coupon->value;
    }

    $coupon->increment('used_count');

    return [

        'discount' => round($discount, 2),

        'code' => $coupon->code,

        'total' => max(0, $total - $discount),

    ];
}

/**
 * --------------------------------------------------------------------------
 * Store Booking
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

        'vendor_id' => $tour->vendor_id,

        'tour_id' => $tour->id,

        'tour_date_id' => $tourDate->id,

        'booking_code' => 'BK-' . strtoupper(Str::random(8)),

        'person_count' => $data['person_count'],

        'total_amount' => $coupon['total'],

        'payment_status' => 'pending',

        'booking_status' => 'pending',

        'special_request' => $data['special_request'] ?? null,

    ]);
}
/**
 * --------------------------------------------------------------------------
 * Deduct Seat
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
 * Create Transaction
 * --------------------------------------------------------------------------
 */
protected function storeTransaction(
    Booking $booking,
    User $user
): Transaction {

    return Transaction::create([

        'user_id' => $user->id,

        'booking_id' => $booking->id,

        'transaction_id' => 'TXN-' . now()->format('YmdHis') . rand(1000,9999),

        'payment_method' => null,

        'amount' => $booking->total_amount,

        'status' => 'pending',

    ]);

}
/**
 * --------------------------------------------------------------------------
 * Create Payment
 * --------------------------------------------------------------------------
 */
protected function storePayment(
    Booking $booking
): Payment {

    return Payment::create([

        'booking_id' => $booking->id,

        'transaction_id' => null,

        'payment_method' => null,

        'amount' => $booking->total_amount,

        'status' => 'pending',

        'payment_data' => null,

        'paid_at' => null,

    ]);

}
}