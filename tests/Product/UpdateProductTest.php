<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Product;

use Lsv\Datapump\Configuration;
use Lsv\Datapump\ItemHolder;
use Lsv\Datapump\Logger;
use Lsv\Datapump\Product\AbstractProduct;
use Lsv\Datapump\Product\UpdateProduct;
use Magmi_ProductImport_DataPump;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

class UpdateProductTest extends TestCase
{
    /**
     * @var ItemHolder
     */
    private $holder;

    /**
     * @test
     */
    public function will_fail_if_type_is_not_set(): void
    {
        $this->expectException(InvalidOptionsException::class);

        $product = (new UpdateProduct())
            ->setSku('update_product')
            ->setQuantity(10);
        $this->holder->addProduct($product);
    }

    /**
     * @test
     */
    public function will_fail_if_sku_is_not_set(): void
    {
        $this->expectException(InvalidOptionsException::class);

        $product = (new UpdateProduct())
            ->setType(AbstractProduct::TYPE_SIMPLE)
            ->setQuantity(10);
        $this->holder->addProduct($product);
    }

    protected function setUp(): void
    {
        $configuration = $this->createMock(Configuration::class);
        $logger = $this->createMock(Logger::class);
        $magmi = $this->createMock(Magmi_ProductImport_DataPump::class);

        $this->holder = new ItemHolder($configuration, $logger, $magmi);
    }
}
