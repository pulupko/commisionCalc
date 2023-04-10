<?php

declare(strict_types=1);

namespace App\CommissionCalculator\Service\CurrencyConverter;

use App\CommissionCalculator\Service\CurrencyConverter\Contract\CurrencyConverterServiceInterface;
use App\CommissionCalculator\Service\CurrencyRate\RateProvider\Contract\CurrencyRateProviderServiceInterface;

class CurrencyConverterService implements CurrencyConverterServiceInterface
{
    protected const CURRENCY_EURO = 'EUR';

    public function __construct(
        private readonly CurrencyRateProviderServiceInterface $currencyRateProviderService
    ) {
    }

    public function convert(float $amount, string $currency): float
    {
        if (self::CURRENCY_EURO === $currency) {
            return $amount;
        }

        if (0 === $rate = $this->getExchangeRateByCurrency($currency)) {
            return $amount;
        }

        return $amount / $rate;
    }

    private function getExchangeRateByCurrency(string $currency)
    {
        if (array_key_exists($currency, $this->currencyRateProviderService->getCurrencyRates())) {
            return $this->currencyRateProviderService->getCurrencyRates()[$currency];
        }
        throw new \RuntimeException(sprintf('There is no currency rate for %s.', $currency));
    }
}
