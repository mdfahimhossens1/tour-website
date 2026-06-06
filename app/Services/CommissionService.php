<?php

namespace App\Services;

class CommissionService
{
    public static function calculate($amount, $rate)
    {
        $commission = ($amount * $rate) / 100;

        return [
            'admin' => $commission,
            'vendor' => $amount - $commission
        ];
    }
}