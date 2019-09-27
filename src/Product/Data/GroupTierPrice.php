<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product\Data;

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
    }

    /**
     * They key to magmi.
     *
     * @return string
     */
    public function getKey(): string
    {
        return 'tier_price:'.$this->customerGroupName;
    }

    /**
     * The generated data.
     *
     * @return string
     */
    public function getData(): string
    {
        return (string) $this->price;
    }

    /**
     * Allow multiple of this object on a product.
     *
     * @return bool
     */
    public function allowMultiple(): bool
    {
        return false;
    }

    /**
     * Will only be used if allow multiple is true.
     *
     * @return string|null
     */
    public function arrayMergeString(): ?string
    {
        return null;
    }
}
