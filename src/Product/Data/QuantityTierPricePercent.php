<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product\Data;

use Lsv\Datapump\Exceptions\MissingDataException;

class QuantityTierPricePercent implements DataInterface
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
    }

    public function addTier(float $quantity, float $percent): self
    {
        $this->tiers[] = $quantity.':'.$percent.'%';

        return $this;
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