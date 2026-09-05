<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Promotion;
use App\Models\PromotionUsage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PromotionService
{
    /**
     * Find a promotion by code.
     */
    public function findByCode(string $code): ?Promotion
    {
        return Promotion::query()
            ->whereRaw(
                'LOWER(code) = ?',
                [strtolower(trim($code))]
            )
            ->first();
    }

    /**
     * Validate promotion for a booking amount.
     */
    public function validate(
        Promotion $promotion,
        float $amount,
        ?int $userId = null
    ): array {

        $amount = round(
            max(0, $amount),
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Active
        |--------------------------------------------------------------------------
        */

        if (!$promotion->is_active) {

            return [
                'valid' => false,
                'message' => 'This promotion is currently inactive.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Start Date
        |--------------------------------------------------------------------------
        */

        if (!$promotion->hasStarted()) {

            return [
                'valid' => false,
                'message' => 'This promotion is not active yet.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Expired
        |--------------------------------------------------------------------------
        */

        if ($promotion->hasExpired()) {

            return [
                'valid' => false,
                'message' => 'This promotion has expired.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Global Usage Limit
        |--------------------------------------------------------------------------
        */

        if ($promotion->isUsageLimitReached()) {

            return [
                'valid' => false,
                'message' => 'This promotion has reached its usage limit.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Per User Usage Limit
        |--------------------------------------------------------------------------
        */

        if (
            $userId &&
            $promotion->isUserUsageLimitReached($userId)
        ) {

            return [
                'valid' => false,
                'message' => 'You have already reached the usage limit for this promotion.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Minimum Amount
        |--------------------------------------------------------------------------
        */

        if (
            $amount < (float) $promotion->minimum_amount
        ) {

            return [
                'valid' => false,
                'message' =>
                    'Minimum booking amount for this promotion is ৳'
                    . number_format(
                        (float) $promotion->minimum_amount,
                        2
                    )
                    . '.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Discount
        |--------------------------------------------------------------------------
        */

        $discount = $promotion->calculateDiscount(
            $amount
        );

        if ($discount <= 0) {

            return [
                'valid' => false,
                'message' =>
                    'This promotion cannot be applied to this booking.',
            ];
        }

        return [
            'valid' => true,

            'message' =>
                'Promotion applied successfully.',

            'promotion' =>
                $promotion,

            'base_amount' =>
                $amount,

            'discount' =>
                round($discount, 2),

            'payable_amount' =>
                round(
                    $amount - $discount,
                    2
                ),
        ];
    }

    /**
     * Preview promotion without recording usage.
     */
    public function preview(
        string $code,
        float $amount,
        ?int $userId = null
    ): array {

        $promotion = $this->findByCode($code);

        if (!$promotion) {

            return [
                'valid' => false,
                'message' => 'Invalid promotion code.',
            ];
        }

        return $this->validate(
            $promotion,
            $amount,
            $userId
        );
    }

    /**
     * Record promotion usage for a confirmed booking.
     *
     * This method must be called only when booking/payment
     * is successfully confirmed.
     */
    public function markAsUsed(
        Booking $booking
    ): PromotionUsage {

        return DB::transaction(function () use ($booking) {

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Usage
            |--------------------------------------------------------------------------
            */

            $existingUsage = PromotionUsage::where(
                'booking_id',
                $booking->id
            )
                ->lockForUpdate()
                ->first();

            if ($existingUsage) {

                return $existingUsage;
            }

            /*
            |--------------------------------------------------------------------------
            | Find Promotion
            |--------------------------------------------------------------------------
            */

            $promotion = $this->findByCode(
                $booking->coupon_code ?? ''
            );

            if (!$promotion) {

                throw ValidationException::withMessages([
                    'promotion' =>
                        'Promotion associated with this booking was not found.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Lock Promotion
            |--------------------------------------------------------------------------
            */

            $promotion = Promotion::where(
                'id',
                $promotion->id
            )
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Validate Promotion Again
            |--------------------------------------------------------------------------
            |
            | Important:
            | Promotion may have become invalid between
            | booking creation and confirmation.
            |
            */

            if (!$promotion->isCurrentlyValid()) {

                throw ValidationException::withMessages([
                    'promotion' =>
                        'This promotion is no longer valid.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Global Usage Limit
            |--------------------------------------------------------------------------
            */

            if (
                $promotion->isUsageLimitReached()
            ) {

                throw ValidationException::withMessages([
                    'promotion' =>
                        'Promotion usage limit has been reached.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Per User Usage Limit
            |--------------------------------------------------------------------------
            */

            if (
                $promotion->isUserUsageLimitReached(
                    $booking->user_id
                )
            ) {

                throw ValidationException::withMessages([
                    'promotion' =>
                        'This user has already reached the promotion usage limit.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Create Usage History
            |--------------------------------------------------------------------------
            */

            $usage = PromotionUsage::create([

                'promotion_id' =>
                    $promotion->id,

                'user_id' =>
                    $booking->user_id,

                'booking_id' =>
                    $booking->id,

                'promotion_code' =>
                    $promotion->code,

                'base_amount' =>
                    round(
                        (float) $booking->subtotal,
                        2
                    ),

                'discount_amount' =>
                    round(
                        (float) $booking->discount,
                        2
                    ),

                'final_amount' =>
                    round(
                        (float) $booking->total_amount,
                        2
                    ),

                'status' =>
                    'used',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Increment Promotion Usage
            |--------------------------------------------------------------------------
            */

            $promotion->increment(
                'used_count'
            );

            return $usage;
        });
    }

    /**
     * Release promotion usage.
     *
     * Used when a confirmed/paid booking is cancelled/refunded.
     */
    public function releaseUsage(
        Booking $booking,
        string $status = 'cancelled'
    ): void {

        DB::transaction(function () use (
            $booking,
            $status
        ) {

            $usage = PromotionUsage::where(
                'booking_id',
                $booking->id
            )
                ->lockForUpdate()
                ->first();

            /*
            |--------------------------------------------------------------------------
            | No Usage
            |--------------------------------------------------------------------------
            */

            if (!$usage) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Already Released
            |--------------------------------------------------------------------------
            */

            if (
                in_array(
                    $usage->status,
                    [
                        'cancelled',
                        'refunded',
                    ],
                    true
                )
            ) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Lock Promotion
            |--------------------------------------------------------------------------
            */

            $promotion = Promotion::where(
                'id',
                $usage->promotion_id
            )
                ->lockForUpdate()
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Update Usage
            |--------------------------------------------------------------------------
            */

            $usage->update([
                'status' => $status,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Restore Usage Count
            |--------------------------------------------------------------------------
            */

            if (
                $promotion &&
                $promotion->used_count > 0
            ) {

                $promotion->decrement(
                    'used_count'
                );
            }
        });
    }
}