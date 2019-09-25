<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Product;

use Lsv\Datapump\Product\AbstractProduct;
use Lsv\Datapump\Product\Data\DeleteProduct;
use Lsv\Datapump\Product\UpdateProduct;
use PHPUnit\Framework\TestCase;

class DeleteProductTest extends TestCase
{
    /**
     * @test
     */
    public function can_set_product_as_deleted(): void
    {
        $p = (new UpdateProduct())
            ->setSku('product-to-delete')
            ->setType(AbstractProduct::TYPE_SIMPLE)
            ->addData(new DeleteProduct());

        $p->validateProduct();
        $this->assertSame('1', $p->getMergedData()['magmi:delete']);
    }
}
