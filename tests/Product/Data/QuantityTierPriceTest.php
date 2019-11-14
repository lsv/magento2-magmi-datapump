<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Product\Data;

use Lsv\Datapump\Exceptions\MissingDataException;
use Lsv\Datapump\Product\Data\QuantityTierPrice;
use PHPUnit\Framework\TestCase;

class QuantityTierPriceTest extends TestCase
{
    /**
     * @test
     */
    public function will_set_correct_data(): void
    {
        $this->markTestSkipped('Tierpricing is not available at this moment');

        $tier = new QuantityTierPrice('group1');
        $tier->addTier(10, 15);
        $tier->addTier(15, 12);

        $this->assertNull($tier->arrayMergeString());
        $this->assertFalse($tier->allowMultiple());
        $this->assertSame('tier_price:group1', $tier->getKey());
        $this->assertSame('10:15;15:12', $tier->getData());
    }

    /**
     * @test
     */
    public function will_throw_exception_if_tier_are_not_added(): void
    {
        $this->markTestSkipped('Tierpricing is not available at this moment');

        $this->expectException(MissingDataException::class);

        $tier = new QuantityTierPrice('group1');
        $tier->getData();
    }
}
