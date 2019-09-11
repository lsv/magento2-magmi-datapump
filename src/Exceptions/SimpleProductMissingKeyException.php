<?php

declare(strict_types=1);

namespace Lsv\Datapump\Exceptions;

class SimpleProductMissingKeyException extends Exception
{
    /**
     * @var string|null
     */
    private $key;

    /**
     * @var string|null
     */
    private $sku;

    public function __construct(?string $key, ?string $sku)
    {
        parent::__construct();
        $this->key = $key;
        $this->sku = $sku;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }
}
