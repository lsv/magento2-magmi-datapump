<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest;

use Lsv\Datapump\Configuration;
use Lsv\Datapump\Exceptions\NotSupportedMagmiModeException;
use Lsv\Datapump\Exceptions\ProductAlreadyAddedException;
use Lsv\Datapump\ItemHolder;
use Lsv\Datapump\Logger;
use Lsv\Datapump\Product\AbstractProduct;
use Lsv\Datapump\Product\SimpleProduct;
use Lsv\Datapump\Product\UpdateProduct;
use Magmi_ProductImport_DataPump;
use Monolog\Handler\StreamHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;

class ItemHolderTest extends TestCase
{
    use CreateSimpleProductTrait;

    /**
     * @var \Monolog\Logger
     */
    private $monolog;

    /**
     * @var ItemHolder
     */
    private $holder;

    /**
     * @var StreamHandler
     */
    private $monologHandler;

    /**
     * @var BufferedOutput
     */
    private $consoleOutput;

    /**
     * @test
     */
    public function will_throw_error_if_magmi_mode_does_not_exists(): void
    {
        $this->expectException(NotSupportedMagmiModeException::class);
        $this->holder->setMagmiMode('no_mode');
    }

    /**
     * @test
     */
    public function will_set_magmi_mode_if_exists(): void
    {
        $this->holder->setMagmiMode(ItemHolder::MAGMI_CREATE);
        $this->assertSame('xcreate', $this->holder->getMagmiMode());
    }

    /**
     * @test
     */
    public function can_not_add_same_product_to_queue(): void
    {
        $this->expectException(ProductAlreadyAddedException::class);

        $product1 = $this->createMock(SimpleProduct::class);
        $product1->method('getSku')->willReturn('1');

        $product2 = $this->createMock(SimpleProduct::class);
        $product2->method('getSku')->willReturn('1');

        $this->holder
            ->addProduct($product1)
            ->addProduct($product2);
    }

    /**
     * @test
     */
    public function can_add_product_with_same_sku_but_different_store(): void
    {
        $product1 = $this->createMock(SimpleProduct::class);
        $product1->method('getSku')->willReturn('1');
        $product1->method('getStore')->willReturn('1');

        $product2 = $this->createMock(SimpleProduct::class);
        $product2->method('getSku')->willReturn('1');
        $product2->method('getStore')->willReturn('2');

        $this->holder
            ->addProduct($product1)
            ->addProduct($product2);
        $this->assertSame(2, $this->holder->countProducts());
    }

    /**
     * @test
     */
    public function will_count_products_and_configurable_simple_products(): void
    {
        $product1 = self::createValidSimpleProduct();
        $product2 = self::createValidSimpleProduct('2')->set('color', 'green');
        $product3 = self::createValidSimpleProduct('3')->set('color', 'blue');

        $product4 = self::createValidConfigurableProduct('4');
        $product4->setProducts([$product2, $product3]);

        $product5 = (new UpdateProduct())
            ->setType(AbstractProduct::TYPE_SIMPLE)
            ->setQuantity(10)
            ->setSku('update_sku')
            ->setStoreViewName('store');

        $this->holder
            ->addProduct($product1)
            ->addProduct($product4)
            ->addProduct($product5);

        $this->assertSame(5, $this->holder->countProducts());
    }

    /**
     * @test
     */
    public function can_import_products(): void
    {
        $product1 = self::createValidSimpleProduct();
        $product2 = self::createValidSimpleProduct('2')->set('color', 'green');
        $product3 = self::createValidSimpleProduct('3')->set('color', 'blue');

        $product4 = self::createValidConfigurableProduct('4');
        $product4->setProducts([$product2, $product3]);

        $this->holder
            ->addProduct($product1)
            ->addProduct($product4);

        $this->assertCount(4, explode("\n", $this->holder->import(false, '%current%/%max%')));
        $this->assertStringContainsString('4/4', $this->consoleOutput->fetch());
    }

    /**
     * @test
     */
    public function can_add_products_to_queue(): void
    {
        $product1 = $this->createMock(SimpleProduct::class);
        $product1->method('getSku')->willReturn('1');

        $product2 = $this->createMock(SimpleProduct::class);
        $product2->method('getSku')->willReturn('2');

        $this->holder
            ->addProduct($product1)
            ->addProduct($product2);

        $this->assertCount(2, $this->holder->getProducts());
    }

    /**
     * @test
     */
    public function will_reset_products_if_set_products_is_used(): void
    {
        $product1 = $this->createMock(SimpleProduct::class);
        $product1->method('getSku')->willReturn('1');

        $product2 = $this->createMock(SimpleProduct::class);
        $product2->method('getSku')->willReturn('2');

        $this->holder
            ->addProduct($product1)
            ->addProduct($product2);

        $this->holder->setProducts([$product1]);

        $this->assertCount(1, $this->holder->getProducts());
    }

    /**
     * @test
     */
    public function will_copy_files_to_magmi_directory(): void
    {
        $configuration = new Configuration(
            __DIR__,
            'my_database_name',
            'host',
            'username',
            'password'
        );

        $magmi = $this->createMock(Magmi_ProductImport_DataPump::class);
        $logger = $this->createMock(Logger::class);

        $files = [
            'ImageAttributeItemProcessor.conf',
            'ItemIndexer.conf',
            'magmi.ini',
            'Magmi_ConfigurableItemProcessor.conf',
            'Magmi_CSVDataSource.conf',
            'Magmi_ReindexingPlugin.conf',
            'plugins.conf',
        ];

        $dir = __DIR__.'/../vendor/macopedia/magmi2/magmi/conf';
        foreach ($files as $file) {
            @unlink($dir.'/'.$file);
            $this->assertFileNotExists($dir.'/'.$file);
        }

        new ItemHolder($configuration, $logger, $magmi);

        foreach ($files as $file) {
            $this->assertFileExists($dir.'/'.$file);
        }

        $this->assertStringContainsString('my_database_name', file_get_contents($dir.'/magmi.ini'));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->monolog = new \Monolog\Logger('log');
        $this->monologHandler = new StreamHandler(fopen('php://memory', 'wb'));
        $this->monolog->pushHandler($this->monologHandler);
        $logger = new Logger($this->monolog);

        $magmi = $this->createMock(Magmi_ProductImport_DataPump::class);
        $magmi->method('ingest')->willReturn(null);
        $magmi->method('beginImportSession')->willReturn(null);
        $magmi->method('endImportSession')->willReturn(null);

        $configuration = new Configuration(
            __DIR__,
            'name',
            'host',
            'username',
            'password'
        );

        $this->consoleOutput = new BufferedOutput();

        $this->holder = new ItemHolder($configuration, $logger, $magmi, $this->consoleOutput);
    }
}
