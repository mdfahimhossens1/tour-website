<?php

namespace App\Services;

class CommissionService
{
    /**
     * Calculate admin commission and vendor earning.
     *
     * @param float|int|string $amount
     * @param float|int|string $rate
     * @return array
     */
    public static function calculate($amount, $rate): array
    {
        $amount = (float) $amount;
        $rate = (float) $rate;


        /*
        |--------------------------------------------------------------------------
        | Safety
        |--------------------------------------------------------------------------
        */

        $amount = max(0, $amount);

        $rate = max(0, min(100, $rate));


        /*
        |--------------------------------------------------------------------------
        | Commission Calculation
        |--------------------------------------------------------------------------
        */

        $commission = ($amount * $rate) / 100;

        $vendorEarning = $amount - $commission;


        /*
        |--------------------------------------------------------------------------
        | Return
        |--------------------------------------------------------------------------
        */

        return [

            'admin' => round($commission, 2),

            'vendor' => round($vendorEarning, 2),

        ];
    }
}
