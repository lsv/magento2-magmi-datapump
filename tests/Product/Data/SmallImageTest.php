<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Product\Data;

use Lsv\Datapump\Product\Data\SmallImage;

class SmallImageTest extends AbstractImageTest
{
    /**
     * @test
     */
    public function can_create_small_image(): void
    {
        $base = new SmallImage($this->file, $this->configuration);
        $this->assertFalse($base->allowMultiple());
        $this->assertSame('small_image', $base->getKey());
        $this->assertSame('i/m/image.png', $base->getData());
        $this->assertFileExists($this->configuration->getMagentoDirectory().'/i/m/image.png');
    }
}
