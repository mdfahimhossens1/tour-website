<?php

namespace App\Services;

use App\Models\TaxRule;
use Illuminate\Support\Collection;

class TaxService
{
    /**
     * Calculate applicable taxes.
     *
     * @param float $baseAmount
     * @param string $appliesTo booking|vendor_payout
     * @return array
     */
    public function calculate(float $baseAmount, string $appliesTo): array
    {
        $baseAmount = round(max(0, $baseAmount), 2);

        $rules = $this->getApplicableRules($appliesTo);

        $taxAmount = 0;

        $breakdown = [];

        foreach ($rules as $rule) {

            $ruleTax = $this->calculateRuleTax(
                $baseAmount,
                $rule
            );

            $taxAmount += $ruleTax;

            $breakdown[] = [
                'id' => $rule->id,
                'name' => $rule->name,
                'code' => $rule->code,
                'type' => $rule->type,
                'rate' => (float) $rule->rate,
                'applies_to' => $rule->applies_to,
                'priority' => (int) $rule->priority,
                'tax_amount' => round($ruleTax, 2),
            ];
        }

        $taxAmount = round($taxAmount, 2);

        return [
            'base_amount' => $baseAmount,
            'tax_amount' => $taxAmount,
            'total_amount' => round($baseAmount + $taxAmount, 2),
            'rules' => $breakdown,
        ];
    }

    /**
     * Calculate taxes for booking.
     */
    public function calculateForBooking(float $baseAmount): array
    {
        return $this->calculate(
            $baseAmount,
            'booking'
        );
    }

    /**
     * Calculate taxes for vendor payout.
     */
    public function calculateForVendorPayout(float $baseAmount): array
    {
        return $this->calculate(
            $baseAmount,
            'vendor_payout'
        );
    }

    /**
     * Get currently effective tax rules.
     */
    public function getApplicableRules(string $appliesTo): Collection
    {
        return TaxRule::query()
            ->currentlyEffective()
            ->where(function ($query) use ($appliesTo) {

                $query->where('applies_to', $appliesTo)
                    ->orWhere('applies_to', 'both');

            })
            ->orderBy('priority', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * Calculate tax amount for a single rule.
     */
    protected function calculateRuleTax(
        float $baseAmount,
        TaxRule $rule
    ): float {
        if ($rule->type === 'percentage') {

            return round(
                $baseAmount * ((float) $rule->rate / 100),
                2
            );
        }

        if ($rule->type === 'fixed') {

            return round(
                (float) $rule->rate,
                2
            );
        }

        return 0;
    }
}