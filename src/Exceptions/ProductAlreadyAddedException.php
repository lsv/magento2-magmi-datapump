<?php

declare(strict_types=1);

namespace Lsv\Datapump\Exceptions;

class ProductAlreadyAddedException extends Exception
{
    /**
     * @var string|null
     */
    private $sku;
    /**
     * @var string|null
     */
    private $store;

    public function __construct(?string $sku, ?string $store)
    {
        parent::__construct();
        $this->sku = $sku;
        $this->store = $store;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function getStore(): ?string
    {
        return $this->store;
    }

    public function __toString()
    {
        return sprintf('Product with SKU: "%s" and store: "%s" is already added', $this->getSku(), $this->getStore());
    }
}
