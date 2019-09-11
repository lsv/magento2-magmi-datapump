<?php

declare(strict_types=1);

namespace Lsv\Datapump\Data;

interface DataInterface
{
    /**
     * They key to magmi.
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * The generated data.
     *
     * @return string
     */
    public function getData(): string;

    /**
     * Allow multiple of this object on a product.
     *
     * @return bool
     */
    public function allowMultiple(): bool;

    /**
     * Will only be used if allow multiple is true.
     *
     * @return string|null
     */
    public function arrayMergeString(): ?string;
}
