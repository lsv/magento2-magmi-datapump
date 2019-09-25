<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Product\Data;

use Lsv\Datapump\Product\Data\DeleteProduct;
use PHPUnit\Framework\TestCase;

class DeleteProductTest extends TestCase
{
    public function test_delete_product_class(): void
    {
        $delete = new DeleteProduct();
        $this->assertSame('magmi:delete', $delete->getKey());
        $this->assertSame('1', $delete->getData());
        $this->assertFalse($delete->allowMultiple());
        $this->assertNull($delete->arrayMergeString());
    }
}
