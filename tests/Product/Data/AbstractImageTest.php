<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Product\Data;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;

abstract class AbstractImageTest extends TestCase
{
    /**
     * @var File
     */
    protected $file;

    protected function setUp(): void
    {
        $this->file = new File(__DIR__.'/../../_image/image.png');
    }
}
