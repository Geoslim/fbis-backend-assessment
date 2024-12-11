<?php

namespace App\Services;

use InvalidArgumentException;

class CommissionService
{
    /**
     * Calculate commission based on the configured mode.
     *
     * @param float $amount
     * @return float
     */
    public function calculateCommission(float $amount): float
    {
        $mode = config('commission.mode');
        $rate = config('commission.rate');
        $flatRate = config('commission.flat_rate');

        return match ($mode) {
            'percentage' => $this->calculatePercentageCommission($amount, $rate),
            'flat' => $this->calculateFlatOrPercentageCommission($amount, $flatRate, $rate),
            default => throw new InvalidArgumentException('Unsupported commission mode: ' . $mode)
        };
    }

    /**
     * Calculate percentage-based commission.
     *
     * @param float $amount
     * @param float $rate
     * @return float
     */
    private function calculatePercentageCommission(float $amount, float $rate): float
    {
        return ($rate / 100) * $amount;
    }

    /**
     * Calculate commission by comparing flat fee and percentage commission.
     *
     * @param float $amount
     * @param float $flatRate
     * @param float $rate
     * @return float
     */
    private function calculateFlatOrPercentageCommission(float $amount, float $flatRate, float $rate): float
    {
        $percentageCommission = $this->calculatePercentageCommission($amount, $rate);

        // Returns the smaller of the flat rate or the percentage-based commission
        return min($flatRate, $percentageCommission);
    }
}
