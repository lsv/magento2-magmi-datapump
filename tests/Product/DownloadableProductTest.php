<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Product;

use Lsv\Datapump\Product\DownloadableProduct;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;

class DownloadableProductTest extends TestCase
{
    /**
     * @var DownloadableProduct
     */
    private $product;

    /**
     * @test
     */
    public function can_set_file(): void
    {
        $file = new File(__DIR__.'/DownloadableProductTest.php');
        $sample = new File(__DIR__.'/UpdateProductTest.php');

        $this->product
            ->setFile($file)
            ->setSample($sample)
            ->setNumberOfDownloads(20)
            ->setSharing(true);

        $this->product->validateProduct();

        $this->assertStringEndsWith('DownloadableProductTest.php', $this->product->getMergedData()['file']);
        $this->assertStringEndsWith('UpdateProductTest.php', $this->product->getMergedData()['sample']);
        $this->assertSame(20, $this->product->getMergedData()['number_of_downloads']);
        $this->assertTrue($this->product->getMergedData()['is_shareable']);
    }

    protected function setUp()
    {
        $this->product = (new DownloadableProduct())
            ->setSku('1')
            ->setName('1')
            ->setDescription('1')
            ->setPrice(10)
            ->setQuantity(10);
    }
}
