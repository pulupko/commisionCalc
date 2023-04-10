<?php

declare(strict_types=1);

namespace App\CommissionCalculator\Service\Reader;

use Traversable;

class FileReaderService
{
    /**
     * @var null|resource
     */
    private $fileResource;

    public function open(string $resource)
    {
        $this->fileResource = fopen($resource, "r");
    }

    public function readOneLine(): Traversable
    {
        while (false !== $line = fgets($this->fileResource)) {
            yield $line;
        }
    }

    public function close()
    { 
        if (false === is_resource($this->fileResource)) {
            throw new \LogicException('You should call the method "open" before "close".');
        }

        fclose($this->fileResource);

        return $this;
    }
}
