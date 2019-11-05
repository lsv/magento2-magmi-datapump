<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product\Data;

class BaseImageLabel implements DataInterface
{
    /**
     * @var string
     */
    private $label;

    public function __construct(string $label)
    {
        $this->label = $label;
    }

    /**
     * They key to magmi.
     */
    public function getKey(): string
    {
        return 'image_label';
    }

    /**
     * The generated data.
     */
    public function getData(): string
    {
        return $this->label;
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
