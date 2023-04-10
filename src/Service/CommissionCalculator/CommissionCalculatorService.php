<?php

declare(strict_types=1);

namespace App\CommissionCalculator\Service\CommissionCalculator;

use App\CommissionCalculator\DTO\TransactionDataDTO;
use App\CommissionCalculator\Helper\CountryHelper;
use App\CommissionCalculator\Service\Bin\BinProvider\Contract\BinProviderServiceInterface;
use App\CommissionCalculator\Service\CurrencyConverter\Contract\CurrencyConverterServiceInterface;

readonly class CommissionCalculatorService
{
    public function __construct(
        private CurrencyConverterServiceInterface $currencyConverterService,
        private BinProviderServiceInterface       $binProviderService
    ) {
    }

    public function calculateCommission(TransactionDataDTO $transactionDataDTO): float
    {
        $binInfo = $this->binProviderService->getInfoByBinNumber($transactionDataDTO->getBin());

        $amountFixed = $this->currencyConverterService->convert(
            $transactionDataDTO->getAmount(),
            $transactionDataDTO->getCurrency()
        );

        $commission = $amountFixed * (CountryHelper::isCountryEU($binInfo['country']['alpha2']) ? 0.01 : 0.02);

        return ceil($commission * 100) / 100;
    }
}
