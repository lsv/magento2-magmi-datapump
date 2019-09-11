<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Exceptions;

use Lsv\Datapump\Exceptions\NotSupportedMagmiModeException;
use PHPUnit\Framework\TestCase;

class NotSupportedMagmiModeExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function getters(): void
    {
        $exception = new NotSupportedMagmiModeException('mode', ['mode1', 'mode2']);
        $this->assertSame('mode', $exception->getSelectedMode());
        $this->assertSame(['mode1', 'mode2'], $exception->getAvailableModes());
    }
}
