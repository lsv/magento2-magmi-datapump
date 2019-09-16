<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Product\Data;

use Lsv\Datapump\Product\Data\BaseImage;

class BaseImageTest extends AbstractImageTest
{
    /**
     * @test
     */
    public function can_create_base_image_and_add_it_to_gallery(): void
    {
        $base = new BaseImage($this->file);
        $this->assertNull($base->arrayMergeString());
        $this->assertFalse($base->allowMultiple());
        $this->assertSame('image', $base->getKey());
        $this->assertSame('+'.$this->file->getPathname(), $base->getData());
    }

    /**
     * @test
     */
    public function can_create_base_image_and_not_add_it_to_gallery(): void
    {
        $base = new BaseImage($this->file, false);
        $this->assertSame('-'.$this->file->getPathname(), $base->getData());
    }
}
