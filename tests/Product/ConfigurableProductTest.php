<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Product;

use Lsv\Datapump\Exceptions\SimpleProductMissingKeyException;
use Lsv\Datapump\Product\AbstractProduct;
use Lsv\Datapump\Product\UpdateProduct;
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
        $product4->setProducts([$product2, $product3]);
    }

    /**
     * @test
     */
    public function will_set_simple_skus_key(): void
    {
        $simple1 = self::createValidSimpleProduct('2')->set('color', 'blue');
        $simple2 = self::createValidSimpleProduct('3')->set('color', 'green');

        $config = self::createValidConfigurableProduct();
        $config->setProducts([$simple1, $simple2]);

        $config->validateProduct();
        $this->assertSame('2,3', $config->getMergedData()['simples_skus']);
        $this->assertSame(['color'], $config->getConfigurableAttributeKeys());
    }

    /**
     * @test
     */
    public function allow_update_product_in_configurable_product(): void
    {
        $update = (new UpdateProduct())->setSku('2')->setType(AbstractProduct::TYPE_SIMPLE)->set('color', 'blue');
        $config = self::createValidConfigurableProduct()
            ->addProduct($update);

        $config->validateProduct();

        $this->assertSame('2', $config->getMergedData()['simples_skus']);
    }

    /**
     * @test
     */
    public function allow_to_manual_set_price_on_configurable_product(): void
    {
        $simple1 = self::createValidSimpleProduct('2')->set('color', 'blue')->setPrice(15.30);
        $simple2 = self::createValidSimpleProduct('3')->set('color', 'green')->setPrice(9.80);

        $config = self::createValidConfigurableProduct();
        $config->setPrice(11.2);
        $config->setProducts([$simple1, $simple2]);

        $config->validateProduct();
        $this->assertSame(11.2, $config->getMergedData()['price']);
    }
}
