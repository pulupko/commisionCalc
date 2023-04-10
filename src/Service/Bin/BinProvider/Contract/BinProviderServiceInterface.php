<?php 

declare(strict_types=1);

namespace App\CommissionCalculator\Service\Bin\BinProvider\Contract;

interface BinProviderServiceInterface
{
    public function getInfoByBinNumber(int $binNumber): array;
}
