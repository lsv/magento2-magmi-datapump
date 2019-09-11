<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Data;

use Lsv\Datapump\Configuration;
use Lsv\Datapump\Data\GalleryImage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;

class GalleryImageTest extends TestCase
{
    /**
     * @test
     */
    public function can_create_gallery_image_with_label(): void
    {
        $file = new File(__DIR__.'/imagestubs/image.png');
        $configuration = new Configuration(__DIR__.'/temp');

        $base = new GalleryImage($file, $configuration, 'label');
        $this->assertSame(';', $base->arrayMergeString());
        $this->assertTrue($base->allowMultiple());
        $this->assertSame('gallery', $base->getKey());
        $this->assertSame('i/m/image.png::label', $base->getData());
    }

    /**
     * @test
     */
    public function can_create_gallery_image_without_label(): void
    {
        $file = new File(__DIR__.'/imagestubs/image.png');
        $configuration = new Configuration(__DIR__.'/temp');

        $base = new GalleryImage($file, $configuration);
        $this->assertTrue($base->allowMultiple());
        $this->assertSame('gallery', $base->getKey());
        $this->assertSame('i/m/image.png', $base->getData());
    }
}
