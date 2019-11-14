<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product\Data;

use Lsv\Datapump\Exceptions\Exception;
use Lsv\Datapump\Exceptions\MissingDataException;

class QuantityTierPrice implements DataInterface
{
    public const ALL_CUSTOMER_GROUP = '_all_';

    /**
     * @var string
     */
    private $customerGroupName;

    private $tiers = [];

    public function __construct(string $customerGroupName = self::ALL_CUSTOMER_GROUP)
    {
        $this->customerGroupName = $customerGroupName;

        throw new Exception('Tierpricing is not available at this moment');
    }

    public function addTier(float $quantity, float $price): self
    {
        $this->tiers[] = $quantity.':'.$price;

        return $this;
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
     *
     * @throws MissingDataException
     */
    public function getData(): string
    {
        if (!$this->tiers) {
            throw new MissingDataException('Quantity tier pricing requires at least 1 tier');
        }

        return implode(';', $this->tiers);
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
