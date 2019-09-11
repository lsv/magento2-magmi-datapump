<?php

declare(strict_types=1);

namespace Lsv\Datapump\Exceptions;

class ProductAlreadyAddedException extends Exception
{
    /**
     * @var string|null
     */
    private $sku;

    public function __construct(?string $sku)
    {
        parent::__construct();
        $this->sku = $sku;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }
}
