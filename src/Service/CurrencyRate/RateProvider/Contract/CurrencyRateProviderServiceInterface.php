<?php

declare(strict_types=1);

namespace App\CommissionCalculator\Service\CurrencyRate\RateProvider\Contract;

interface CurrencyRateProviderServiceInterface
{
    public function getCurrencyRates(): array;
}
