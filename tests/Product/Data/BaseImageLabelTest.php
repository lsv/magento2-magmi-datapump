<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Product\Data;

use Lsv\Datapump\Product\Data\BaseImageLabel;
use Lsv\Datapump\Product\SimpleProduct;
use PHPUnit\Framework\TestCase;

class BaseImageLabelTest extends TestCase
{
    /**
     * @test
     */
    public function can_get_label(): void
    {
        $label = new BaseImageLabel('my_label');
        $this->assertSame('image_label', $label->getKey());
        $this->assertSame('my_label', $label->getData());
        $this->assertFalse($label->allowMultiple());
        $this->assertNull($label->arrayMergeString());

        $product = new SimpleProduct();
        $product->addData($label);
        $this->assertSame('my_label', $product->getExtraData()['image_label']);
    }
}
