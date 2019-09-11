<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest;

use Lsv\Datapump\Product\ConfigurableProduct;
use Lsv\Datapump\Product\SimpleProduct;

trait CreateSimpleProductTrait
{
    public static function createValidSimpleProduct($sku = '1'): SimpleProduct
    {
        $product = new SimpleProduct();
        $product->setName('name');
        $product->setSku($sku);
        $product->setDescription('description');
        $product->setTaxClass('tax_class_id');
        $product->setPrice(10.00);
        $product->setQuantity(1);
        $product->setWeight(1);

        return $product;
    }

    public static function createValidConfigurableProduct($sku = '1', array $simpleKeys = ['color']): ConfigurableProduct
    {
        $product = new ConfigurableProduct($simpleKeys);
        $product->setName('name');
        $product->setSku($sku);
        $product->setDescription('description');
        $product->setTaxClass('tax_class_id');

        return $product;
    }
}
