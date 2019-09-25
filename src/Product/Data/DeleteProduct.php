<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product\Data;

class DeleteProduct implements DataInterface
{
    /**
     * They key to magmi.
     *
     * @return string
     */
    public function getKey(): string
    {
        return 'magmi:delete';
    }

    /**
     * The generated data.
     *
     * @return string
     */
    public function getData(): string
    {
        return '1';
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
