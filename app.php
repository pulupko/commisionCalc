<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use App\CommissionCalculator\DTO\TransactionDataDTO;
use App\CommissionCalculator\Service\Bin\BinProvider\BinlistBinProvider;
use App\CommissionCalculator\Service\CommissionCalculator\CommissionCalculatorService;
use App\CommissionCalculator\Service\CurrencyConverter\CurrencyConverterService;
use App\CommissionCalculator\Service\CurrencyRate\RateProvider\ApiLayerCurrencyRateProviderService;
use App\CommissionCalculator\Service\Reader\FileReaderService;

$reader = new FileReaderService();
$reader->open($argv[1]);

$CommissionCalculator = new CommissionCalculatorService(
    new CurrencyConverterService(new ApiLayerCurrencyRateProviderService),
    new BinlistBinProvider()
);

foreach ($reader->readOneLine() as $line) {
    try {
        $transactionInfo = json_decode($line, false, 512, JSON_THROW_ON_ERROR);
        $transactionDataDTO = new TransactionDataDTO(
            bin: (int) $transactionInfo->bin,
            amount: (float) $transactionInfo->amount,
            currency: (string) $transactionInfo->currency
        );

        echo $CommissionCalculator->calculateCommission($transactionDataDTO);
        print "\n";
    } catch (\Throwable $e) {
        echo $e->getMessage();
        return 1;
    }
}

$reader->close();
