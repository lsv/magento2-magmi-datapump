<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest;

use Lsv\Datapump\Utils\DataObject;
use PHPUnit\Framework\TestCase;

class DataObjectTest extends TestCase
{
    /**
     * @test
     */
    public function will_return_null_if_key_not_found(): void
    {
        $obj = new DataObject();
        $this->assertNull($obj->get('key'));
    }
}
