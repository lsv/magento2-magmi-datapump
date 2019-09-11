<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Data;

use Lsv\Datapump\Configuration;
use Lsv\Datapump\Data\BaseImage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;

class BaseImageTest extends TestCase
{
    /**
     * @test
     */
    public function can_create_base_image_and_add_it_to_gallery(): void
    {
        $file = new File(__DIR__.'/imagestubs/image.png');
        $configuration = new Configuration(__DIR__.'/temp');

        $base = new BaseImage($file, $configuration);
        $this->assertNull($base->arrayMergeString());
        $this->assertFalse($base->allowMultiple());
        $this->assertSame('image', $base->getKey());
        $this->assertSame('+i/m/image.png', $base->getData());
        $this->assertFileExists(__DIR__.'/temp/i/m/image.png');
        unlink(__DIR__.'/temp/'.str_replace('+', '', $base->getData()));
    }

    /**
     * @test
     */
    public function can_create_base_image_and_not_add_it_to_gallery(): void
    {
        $file = new File(__DIR__.'/imagestubs/image.png');
        $configuration = new Configuration(__DIR__.'/temp');

        $base = new BaseImage($file, $configuration, false);
        $this->assertSame('-i/m/image.png', $base->getData());
        unlink(__DIR__.'/temp/'.str_replace('-', '', $base->getData()));
    }

    /**
     * @test
     */
    public function will_rename_file_if_already_exists(): void
    {
        $file = new File(__DIR__.'/imagestubs/image.png');
        $configuration = new Configuration(__DIR__.'/temp');
        copy($file->getPathname(), __DIR__.'/temp/i/m/image.png');

        $base = new BaseImage($file, $configuration, false, true);
        $this->assertNotSame('-i/m/image.png', $base->getData());

        unlink(__DIR__.'/temp/i/m/image.png');
    }
}
