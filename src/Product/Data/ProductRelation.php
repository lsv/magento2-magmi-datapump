<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product\Data;

use Lsv\Datapump\Product\ProductInterface;

class ProductRelation implements DataInterface
{
    private $relations;

    /**
     * @var array[string|AbstractProduct] An array of sku numbers as string or as AbstractProduct
     */
    public function __construct(array $products)
    {
        foreach ($products as $product) {
            if ($product instanceof ProductInterface) {
                $this->relations[] = $product->getSku();
            } else {
                $this->relations[] = $product;
            }
        }
    }

    /**
     * They key to magmi.
     */
    public function getKey(): string
    {
        return 're_skus';
    }

    /**
     * The generated data.
     */
    public function getData(): string
    {
        return implode(',', $this->relations);
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
