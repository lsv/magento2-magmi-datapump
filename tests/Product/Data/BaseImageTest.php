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
        $base = new BaseImage($this->file, $this->configuration);
        $this->assertNull($base->arrayMergeString());
        $this->assertFalse($base->allowMultiple());
        $this->assertSame('image', $base->getKey());
        $this->assertSame('+i/m/image.png', $base->getData());
        $this->assertFileExists($this->configuration->getMagentoDirectory().'/i/m/image.png');
    }

    /**
     * @test
     */
    public function can_create_base_image_and_not_add_it_to_gallery(): void
    {
        $base = new BaseImage($this->file, $this->configuration, false);
        $this->assertSame('-i/m/image.png', $base->getData());
    }

    /**
     * @test
     */
    public function will_rename_file_if_already_exists(): void
    {
        copy($this->file->getPathname(), $this->configuration->getMagentoDirectory().'/i/m/image.png');

        $base = new BaseImage($this->file, $this->configuration, false, true);
        $this->assertNotSame('-i/m/image.png', $base->getData());
    }
}
