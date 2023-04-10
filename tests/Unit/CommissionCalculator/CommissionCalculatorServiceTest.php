<?php

declare(strict_types = 1);

namespace Unit\CommissionCalculator;

use App\CommissionCalculator\DTO\TransactionDataDTO;
use App\CommissionCalculator\Service\Bin\BinProvider\BinlistBinProvider;
use App\CommissionCalculator\Service\CommissionCalculator\CommissionCalculatorService;
use App\CommissionCalculator\Service\CurrencyConverter\CurrencyConverterService;
use App\CommissionCalculator\Service\CurrencyRate\RateProvider\ApiLayerCurrencyRateProviderService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorServiceTest extends TestCase
{
    /**
     * @dataProvider commissionCalculationCases
     */
    public function testCommissionIsProperlyCalculatedForEuCountry($case): void
    {
        //Arrange
        $currencyConverterServiceMock = $this->createMock(CurrencyConverterService::class);
        $binProviderServiceMock = $this->createMock(BinlistBinProvider::class);
        $transactionDataMock = new TransactionDataDTO($case['bin'], $case['amount'], $case['currency']);

        $currencyConverterServiceMock
            ->expects($this->once())
            ->method('convert')
            ->with($transactionDataMock->getAmount(), $transactionDataMock->getCurrency())
            ->willReturn($case['amount']);

        $binProviderServiceMock
            ->expects($this->once())
            ->method('getInfoByBinNumber')
            ->with($transactionDataMock->getBin())
            ->willReturn($case['binArray']);

        $commissionCalculatorService = new CommissionCalculatorService(
            $currencyConverterServiceMock,
            $binProviderServiceMock
        );

        //Act
        $result = $commissionCalculatorService->calculateCommission($transactionDataMock);
        $expectedResult = ceil(($transactionDataMock->getAmount() * $case['commissionForCountry']) * 100) / 100;

        //Assert
        $this->assertSame($expectedResult, $result, 'Error in commission calculation');
    }

    public static function commissionCalculationCases(): array
    {
        return [
            [
                'eu country' => [
                    'bin' => 45717360,
                    'binArray' => [
                        "number" => [
                            "length" => 16,
                            "luhn" => true
                        ],
                        "scheme" => "visa",
                        "type" => "debit",
                        "brand" => "Visa/Dankort",
                        "prepaid" => false,
                        "country" => [
                            "numeric" => "208",
                            "alpha2" => "DK",
                            "name" => "Denmark",
                            "emoji" => "🇩🇰",
                            "currency" => "DKK",
                            "latitude" => 56,
                            "longitude" => 10
                        ],
                        "bank" => [
                            "name" => "Jyske Bank",
                            "url" => "www.jyskebank.dk",
                            "phone" => "+4589893300",
                            "city" => "Hjørring"
                        ]
                    ],
                    'commissionForCountry' => 0.01,
                    'amount' => 100.00,
                    'convertedAmount' => 100.00,
                    'currency' => 'EUR'
                ],
                'non eu country' => [
                    'binArray' => [
                        "number" => [
                        ],
                        "scheme" => "visa",
                        "country" => [
                            "numeric" => "840",
                            "alpha2" => "US",
                            "name" => "United States of America",
                            "emoji" => "🇺🇸",
                            "currency" => "USD",
                            "latitude" => 38,
                            "longitude" => -97
                        ],
                        "bank" => [
                            "name" => "VERMONT NATIONAL BANK",
                            "url" => "www.communitynationalbank.com",
                            "phone" => "(802) 744-2287"
                        ]
                    ],
                    'commissionForCountry' => 0.02,
                    'amount' => 100.00,
                    'convertedAmount' => 92.7,
                    'currency' => 'USD'
                ]
            ]
        ];
    }
}
