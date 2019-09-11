<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Product;

use Lsv\Datapump\Exceptions\SimpleProductMissingKeyException;
use Lsv\DatapumpTest\CreateSimpleProductTrait;
use PHPUnit\Framework\TestCase;

class ConfigurableProductTest extends TestCase
{
    use CreateSimpleProductTrait;

    /**
     * @test
     */
    public function can_not_add_simple_product_if_key_is_missing(): void
    {
        $this->expectException(SimpleProductMissingKeyException::class);

        $product2 = self::createValidSimpleProduct('2')->set('color', 'green');
        $product3 = self::createValidSimpleProduct('3');

        $product4 = self::createValidConfigurableProduct('4');
        $product4->setSimpleProducts([$product2, $product3]);
    }
}
