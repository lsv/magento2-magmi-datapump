<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Product\Data;

use Lsv\Datapump\Product\Data\ThumbnailImage;

class ThumbnailImageTest extends AbstractImageTest
{
    /**
     * @test
     */
    public function can_create_small_image(): void
    {
        $base = new ThumbnailImage($this->file);
        $this->assertFalse($base->allowMultiple());
        $this->assertSame('thumbnail', $base->getKey());
        $this->assertSame($this->file->getPathname(), $base->getData());
    }
}
