<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Exceptions;

use Lsv\Datapump\Exceptions\SimpleProductMissingKeyException;
use PHPUnit\Framework\TestCase;

class SimpleProductMissingKeyExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function getters(): void
    {
        $e = new SimpleProductMissingKeyException('key', 'sku');
        $this->assertSame('key', $e->getKey());
        $this->assertSame('sku', $e->getSku());
    }
}
