<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product;

interface ConfigurableProductInterface
{
    public function setConfigurableAttributeKeys(array $keys);

    public function getConfigurableAttributeKeys(): array;

    public function addProduct(AbstractProduct $product);

    /**
     * @param AbstractProduct[] $products
     */
    public function setProducts(array $products);

    /**
     * @return AbstractProduct[]
     */
    public function getProducts(): array;

    public function countProducts(): int;

    /**
     * @deprecated
     */
    public function addSimpleProduct(AbstractProduct $product);

    /**
     * @deprecated
     *
     * @return AbstractProduct[]
     */
    public function getSimpleProducts(): array;

    /**
     * @deprecated
     *
     * @param AbstractProduct[] $products
     */
    public function setSimpleProducts(array $products);

    /**
     * @deprecated
     */
    public function countSimpleProducts(): int;
}
