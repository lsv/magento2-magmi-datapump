<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Product\Data;

use Lsv\Datapump\Product\AbstractProduct;
use Lsv\Datapump\Product\Data\ProductCrossRelation;
use Lsv\Datapump\Product\UpdateProduct;
use PHPUnit\Framework\TestCase;

class ProductCrossRelationTest extends TestCase
{
    /**
     * @test
     */
    public function can_add_relations_to_a_product(): void
    {
        $relation = new ProductCrossRelation([1, 2, 'my-sku', (new UpdateProduct())->setSku('another-sku')]);

        $this->assertSame('xre_skus', $relation->getKey());
        $this->assertFalse($relation->allowMultiple());
        $this->assertNull($relation->arrayMergeString());

        $product = (new UpdateProduct())
            ->setSku('foo')
            ->setType(AbstractProduct::TYPE_SIMPLE)
            ->addData($relation);
        $product->validateProduct();
        $this->assertSame('1,2,my-sku,another-sku', $product->getMergedData()['xre_skus']);
    }
}
