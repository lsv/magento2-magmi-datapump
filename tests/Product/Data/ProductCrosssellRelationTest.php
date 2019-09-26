<?php

namespace Lsv\DatapumpTest\Product\Data;

use Lsv\Datapump\Product\Data\ProductCrosssellRelation;
use PHPUnit\Framework\TestCase;

class ProductCrosssellRelationTest extends TestCase
{
    /**
     * @test
     */
    public function get_the_correct_key(): void
    {
        $this->assertSame('cs_skus', (new ProductCrosssellRelation([]))->getKey());
    }

}
