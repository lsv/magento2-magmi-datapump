<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Product\Data;

use Lsv\Datapump\Product\Data\GalleryImage;

class GalleryImageTest extends AbstractImageTest
{
    /**
     * @test
     */
    public function can_create_gallery_image_with_label(): void
    {
        $base = new GalleryImage($this->file, 'label');
        $this->assertSame(';', $base->arrayMergeString());
        $this->assertTrue($base->allowMultiple());
        $this->assertSame('gallery', $base->getKey());
        $this->assertSame($this->file->getPathname().'::label', $base->getData());
    }

    /**
     * @test
     */
    public function can_create_gallery_image_without_label(): void
    {
        $base = new GalleryImage($this->file);
        $this->assertTrue($base->allowMultiple());
        $this->assertSame('gallery', $base->getKey());
        $this->assertSame($this->file->getPathname(), $base->getData());
    }
}
