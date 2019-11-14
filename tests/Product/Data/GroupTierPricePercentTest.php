<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Product\Data;

use Lsv\Datapump\Product\Data\GroupTierPricePercent;
use PHPUnit\Framework\TestCase;

class GroupTierPricePercentTest extends TestCase
{
    /**
     * @test
     */
    public function will_set_correct_data(): void
    {
        $this->markTestSkipped('Tierpricing is not available at this moment');

        $tierprice = new GroupTierPricePercent('group1', -15);
        $this->assertSame('tier_price:group1', $tierprice->getKey());
        $this->assertSame('-15%', $tierprice->getData());
        $this->assertFalse($tierprice->allowMultiple());
        $this->assertNull($tierprice->arrayMergeString());
    }
}
