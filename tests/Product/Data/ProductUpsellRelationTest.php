<?php

namespace Lsv\DatapumpTest\Product\Data;

use Lsv\Datapump\Product\Data\ProductUpsellRelation;
use PHPUnit\Framework\TestCase;

class ProductUpsellRelationTest extends TestCase
{
    /**
     * @test
     */
    public function get_the_correct_key(): void
    {
        $this->assertSame('us_skus', (new ProductUpsellRelation([]))->getKey());
    }
}
