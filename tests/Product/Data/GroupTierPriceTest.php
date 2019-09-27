<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Product\Data;

use Lsv\Datapump\Product\Data\GroupTierPrice;
use PHPUnit\Framework\TestCase;

class GroupTierPriceTest extends TestCase
{
    /**
     * @test
     */
    public function will_set_correct_data(): void
    {
        $tierprice = new GroupTierPrice('group1', 15);
        $this->assertSame('tier_price:group1', $tierprice->getKey());
        $this->assertSame('15', $tierprice->getData());
        $this->assertFalse($tierprice->allowMultiple());
        $this->assertNull($tierprice->arrayMergeString());
    }
}
