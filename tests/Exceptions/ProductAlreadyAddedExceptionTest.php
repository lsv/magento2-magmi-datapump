<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Exceptions;

use Lsv\Datapump\Exceptions\ProductAlreadyAddedException;
use PHPUnit\Framework\TestCase;

class ProductAlreadyAddedExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function getters(): void
    {
        $e = new ProductAlreadyAddedException('sku', 'store');
        $this->assertSame('sku', $e->getSku());
        $this->assertSame('store', $e->getStore());
        $this->assertSame('Product with SKU: "sku" and store: "store" is already added', (string) $e);
    }
}
