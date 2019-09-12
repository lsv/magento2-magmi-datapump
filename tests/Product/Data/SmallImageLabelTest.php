<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Product\Data;

use Lsv\Datapump\Product\Data\SmallImageLabel;
use Lsv\Datapump\Product\SimpleProduct;
use PHPUnit\Framework\TestCase;

class SmallImageLabelTest extends TestCase
{
    /**
     * @test
     */
    public function can_get_label(): void
    {
        $label = new SmallImageLabel('my_label');
        $this->assertSame('small_image_label', $label->getKey());
        $this->assertSame('my_label', $label->getData());

        $product = new SimpleProduct();
        $product->addData($label);
        $this->assertSame('my_label', $product->getExtraData()['small_image_label']);
    }
}
