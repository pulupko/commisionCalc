<?php

declare(strict_types = 1);

namespace Unit\CurrencyConverter;

use App\CommissionCalculator\Service\CurrencyConverter\CurrencyConverterService;
use App\CommissionCalculator\Service\CurrencyRate\RateProvider\ApiLayerCurrencyRateProviderService;
use PHPUnit\Framework\TestCase;

class CurrencyConverterServiceTest extends TestCase
{
    private const MOCKED_RATES = [
        "AED" => 4.038473,
        "AFN" => 94.608915,
        "ALL" => 112.806783,
        "AMD" => 427.049965,
        "ANG" => 1.968069,
        "AOA" => 558.062118,
        "ARS" => 230.27302,
        "AUD" => 1.648113,
        "AWG" => 1.979318,
        "AZN" => 1.873721,
        "BAM" => 1.955571,
        "BBD" => 2.204942,
        "BDT" => 115.596456,
        "BGN" => 1.968462,
        "BHD" => 0.410952,
        "BIF" => 2272.733715,
        "BMD" => 1.099621,
        "BND" => 1.45213,
        "BOB" => 7.546116,
        "BRL" => 5.560542,
        "BSD" => 1.091972,
        "BTC" => 3.9154993E-5,
        "BTN" => 89.27954,
        "BWP" => 14.369316,
        "BYN" => 2.756877,
        "BYR" => 21552.574796,
        "BZD" => 2.201142,
        "CAD" => 1.490812,
        "CDF" => 2252.578263,
        "CHF" => 0.995776,
        "CLF" => 0.032429,
        "CLP" => 894.80516,
        "CNY" => 7.555172,
        "COP" => 4999.414244,
        "CRC" => 587.331185,
        "CUC" => 1.099621,
        "CUP" => 29.139961,
        "CVE" => 110.247083,
        "CZK" => 23.511115,
        "DJF" => 194.437219,
        "DKK" => 7.511077,
        "DOP" => 59.982972,
        "DZD" => 148.145643,
        "EGP" => 33.745046,
        "ERN" => 16.494317,
        "ETB" => 59.345147,
        "EUR" => 1,
        "FJD" => 2.42483,
        "FKP" => 0.885374,
        "GBP" => 0.883833,
        "GEL" => 2.776588,
        "GGP" => 0.885374,
        "GHS" => 11.902605,
        "GIP" => 0.885374,
        "GMD" => 68.400674,
        "GNF" => 9385.900302,
        "GTQ" => 8.515002,
        "GYD" => 230.962939,
        "HKD" => 8.631752,
        "HNL" => 26.862853,
        "HRK" => 7.717443,
        "HTG" => 169.260169,
        "HUF" => 378.676966,
        "IDR" => 16429.384816,
        "ILS" => 3.959791,
        "IMP" => 0.885374,
        "INR" => 89.99355,
        "IQD" => 1430.832357,
        "IRR" => 46458.994526,
        "ISK" => 150.956418,
        "JEP" => 0.885374,
        "JMD" => 166.050545,
        "JOD" => 0.780076,
        "JPY" => 145.309482,
        "KES" => 144.143111,
        "KGS" => 96.129305,
        "KHR" => 4430.480903,
        "KMF" => 496.204472,
        "KPW" => 989.611142,
        "KRW" => 1447.585706,
        "KWD" => 0.337353,
        "KYD" => 0.909993,
        "KZT" => 487.032937,
        "LAK" => 18701.808805,
        "LBP" => 16391.279518,
        "LKR" => 349.172089,
        "LRD" => 178.74384,
        "LSL" => 20.112491,
        "LTL" => 3.246896,
        "LVL" => 0.66515,
        "LYD" => 5.20339,
        "MAD" => 11.114498,
        "MDL" => 19.820678,
        "MGA" => 4771.440955,
        "MKD" => 61.617781,
        "MMK" => 2293.231314,
        "MNT" => 3863.282457,
        "MOP" => 8.829366,
        "MRO" => 392.564566,
        "MUR" => 49.868236,
        "MVR" => 16.890597,
        "MWK" => 1120.868674,
        "MXN" => 19.931518,
        "MYR" => 4.841675,
        "MZN" => 69.551454,
        "NAD" => 20.112486,
        "NGN" => 511.324219,
        "NIO" => 39.94532,
        "NOK" => 11.540567,
        "NPR" => 142.847263,
        "NZD" => 1.760662,
        "OMR" => 0.420421,
        "PAB" => 1.091972,
        "PEN" => 4.110418,
        "PGK" => 3.847549,
        "PHP" => 59.918769,
        "PKR" => 306.858347,
        "PLN" => 4.716037,
        "PYG" => 7829.082707,
        "QAR" => 4.003175,
        "RON" => 4.971612,
        "RSD" => 117.321254,
        "RUB" => 89.183334,
        "RWF" => 1204.958821,
        "SAR" => 4.12481,
        "SBD" => 9.134887,
        "SCR" => 15.266211,
        "SDG" => 658.127311,
        "SEK" => 11.515567,
        "SGD" => 1.462281,
        "SHP" => 1.337964,
        "SLE" => 23.67643,
        "SLL" => 21717.518342,
        "SOS" => 625.138661,
        "SRD" => 40.09714,
        "STD" => 22759.937949,
        "SVC" => 9.554881,
        "SYP" => 2762.731734,
        "SZL" => 19.904668,
        "THB" => 37.489426,
        "TJS" => 11.908505,
        "TMT" => 3.85967,
        "TND" => 3.3511,
        "TOP" => 2.578667,
        "TRY" => 21.166062,
        "TTD" => 7.414131,
        "TWD" => 33.437395,
        "TZS" => 2556.40048,
        "UAH" => 40.146496,
        "UGX" => 4086.521203,
        "USD" => 1.099621,
        "UYU" => 42.275047,
        "UZS" => 12445.54182,
        "VEF" => 2686478.728723,
        "VES" => 26.893773,
        "VND" => 25782.81741,
        "VUV" => 129.823128,
        "WST" => 2.977601,
        "XAF" => 655.923149,
        "XAG" => 0.044004,
        "XAU" => 0.000548,
        "XCD" => 2.971782,
        "XDR" => 0.810205,
        "XOF" => 655.923149,
        "XPF" => 120.782788,
        "YER" => 275.290558,
        "ZAR" => 19.875334,
        "ZMK" => 9897.913959,
        "ZMW" => 21.622467,
        "ZWL" => 354.077566
    ];

    protected CurrencyConverterService $service;

    public function setUp(): void
    {
        $currencyRateProviderMock = $this->createMock(ApiLayerCurrencyRateProviderService::class);
        $currencyRateProviderMock
            ->method('getCurrencyRates')
            ->willReturn(static::MOCKED_RATES);
        $this->service = new CurrencyConverterService($currencyRateProviderMock);
    }

    public function testConvertForEURCurrency(): void
    {
        //Act
        $result = $this->service->convert(100.00, 'EUR');

        //Assert
        $this->assertSame(100.00, $result);
    }

    public function testConvertForUSDCurrency(): void
    {
        //Arrange
        $expectedResult = 100 / (float)static::MOCKED_RATES['USD'];

        //Act
        $result = $this->service->convert(100.00, 'USD');

        //Assert
        $this->assertSame($expectedResult, $result);
    }

    public function testConvertForNotExistingCurrency(): void
    {
        //Arrange
        $currency = 'FOO';
        $this->expectException(\RuntimeException::class);

        //Act
        $this->service->convert(100.00, $currency);

        //Assert
        $this->expectExceptionMessage(sprintf('There is no currency rate for %s.', $currency));
    }
}
