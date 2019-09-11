<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Data;

use Lsv\Datapump\Configuration;
use Lsv\Datapump\Data\ThumbnailImage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;

class ThumbnailImageTest extends TestCase
{
    /**
     * @test
     */
    public function can_create_small_image(): void
    {
        $file = new File(__DIR__.'/imagestubs/image.png');
        $configuration = new Configuration(__DIR__.'/temp');

        $base = new ThumbnailImage($file, $configuration);
        $this->assertFalse($base->allowMultiple());
        $this->assertSame('thumbnail', $base->getKey());
        $this->assertSame('i/m/image.png', $base->getData());
        $this->assertFileExists(__DIR__.'/temp/i/m/image.png');
        @unlink(__DIR__.'/temp/'.$base->getData());
    }
}
