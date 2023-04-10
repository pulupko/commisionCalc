<?php

declare(strict_types=1);

namespace App\CommissionCalculator\Service\Bin\BinProvider;

use App\CommissionCalculator\Service\Bin\BinProvider\Contract\BinProviderServiceInterface;
use Symfony\Component\HttpClient\HttpClient;

class BinlistBinProvider implements BinProviderServiceInterface
{
    private const BIN_INFO_URL = 'https://lookup.binlist.net/';

    private array $binInfo = [];

    public function getInfoByBinNumber(int $binNumber): array
    {
        if (true === empty($this->binInfo[$binNumber])) {
            $client = HttpClient::create();

            $this->binInfo[$binNumber] = $client->request(
                'GET', 
                sprintf('%s%d', self::BIN_INFO_URL, $binNumber)
            )->toArray();
        }
        return $this->binInfo[$binNumber];
    }
}
