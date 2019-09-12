<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Product\Data;

use Lsv\Datapump\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;

abstract class AbstractImageTest extends TestCase
{
    /**
     * @var File
     */
    protected $file;

    /**
     * @var Configuration
     */
    protected $configuration;

    protected function setUp(): void
    {
        $this->file = new File(__DIR__.'/../../_image/image.png');
        $this->configuration = new Configuration(__DIR__.'/../../_temp');
    }

    protected function tearDown(): void
    {
        $this->deleteTempFiles();
    }

    private function deleteTempFiles(): void
    {
        $files = glob($this->configuration->getMagentoDirectory().'/*.png');
        foreach ($files as $file) {
            unlink($file);
        }
    }
}
