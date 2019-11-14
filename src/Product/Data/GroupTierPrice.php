<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product\Data;

use Lsv\Datapump\Exceptions\Exception;

class GroupTierPrice implements DataInterface
{
    /**
     * @var string
     */
    private $customerGroupName;

    /**
     * @var float
     */
    private $price;

    public function __construct(string $customerGroupName, float $price)
    {
        $this->customerGroupName = $customerGroupName;
        $this->price = $price;

        throw new Exception('Tierpricing is not available at this moment');
    }

    /**
     * They key to magmi.
     */
    public function getKey(): string
    {
        return 'tier_price:'.$this->customerGroupName;
    }

    /**
     * The generated data.
     */
    public function getData(): string
    {
        return (string) $this->price;
    }

    /**
     * Allow multiple of this object on a product.
     */
    public function allowMultiple(): bool
    {
        return false;
    }

    /**
     * Will only be used if allow multiple is true.
     */
    public function arrayMergeString(): ?string
    {
        return null;
    }
}
