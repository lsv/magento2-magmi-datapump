<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product\Data;

interface DataInterface
{
    /**
     * They key to magmi.
     */
    public function getKey(): string;

    /**
     * The generated data.
     */
    public function getData(): string;

    /**
     * Allow multiple of this object on a product.
     */
    public function allowMultiple(): bool;

    /**
     * Will only be used if allow multiple is true.
     */
    public function arrayMergeString(): ?string;
}
