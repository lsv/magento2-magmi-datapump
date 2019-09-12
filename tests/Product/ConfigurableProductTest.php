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

    /**
     * @test
     */
    public function will_set_simple_skus_key(): void
    {
        $simple1 = self::createValidSimpleProduct('2')->set('color', 'blue');
        $simple2 = self::createValidSimpleProduct('3')->set('color', 'green');

        $config = self::createValidConfigurableProduct('1');
        $config->setSimpleProducts([$simple1, $simple2]);

        $config->validateProduct();
        $this->assertSame('2,3', $config->getMergedData()['simple_skus']);
    }
}
