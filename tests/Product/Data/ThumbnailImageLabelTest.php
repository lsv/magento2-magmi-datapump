<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Product\Data;

use Lsv\Datapump\Product\Data\ThumbnailImageLabel;
use Lsv\Datapump\Product\SimpleProduct;
use PHPUnit\Framework\TestCase;

class ThumbnailImageLabelTest extends TestCase
{
    /**
     * @test
     */
    public function can_get_label(): void
    {
        $label = new ThumbnailImageLabel('my_label');
        $this->assertSame('thumbnail_label', $label->getKey());
        $this->assertSame('my_label', $label->getData());

        $product = new SimpleProduct();
        $product->addData($label);
        $this->assertSame('my_label', $product->getExtraData()['thumbnail_label']);
    }
}
