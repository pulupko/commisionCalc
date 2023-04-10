<?php

declare(strict_types=1);

namespace App\CommissionCalculator\Service\CurrencyConverter\Contract;

interface CurrencyConverterServiceInterface
{
    public function convert(float $amount, string $currency): float;
}
