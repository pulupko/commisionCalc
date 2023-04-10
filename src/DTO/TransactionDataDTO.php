<?php

declare(strict_types=1);

namespace App\CommissionCalculator\DTO;

readonly class TransactionDataDTO
{
     public function __construct(
        private int $bin,
        private float $amount,
        private string $currency,
    ) {    
    }

    public function getBin(): int
    {
        return $this->bin;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
