<?php

declare(strict_types=1);

namespace App\CommissionCalculator\Service\CurrencyRate\RateProvider;

use App\CommissionCalculator\Service\CurrencyRate\RateProvider\Contract\CurrencyRateProviderServiceInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ApiLayerCurrencyRateProviderService implements CurrencyRateProviderServiceInterface
{
    private array $ratesResponse = [];

    private const EXCHANGE_RATES_URL = 'https://api.apilayer.com/exchangerates_data/latest';

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getCurrencyRates(): array
    {
        if (true === empty($this->ratesResponse)) {
            $client = HttpClient::create();

            $this->ratesResponse = $client->request('GET', self::EXCHANGE_RATES_URL, [
                'headers' => [
                    'apikey' => '4FXA0xigdF2t9UadD2OhIPCxLQI1Pa3Q',
                ],
            ])->toArray();
        }

        return $this->ratesResponse['rates'];
    }
}
