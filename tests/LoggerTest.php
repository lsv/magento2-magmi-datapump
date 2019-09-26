<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest;

use Generator;
use Lsv\Datapump\Logger;
use Monolog\Handler\StreamHandler;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
    /**
     * @var resource
     */
    private $stream;

    /**
     * @var Logger
     */
    private $logger;

    public function debugProvider(): Generator
    {
        yield ['debug'];
        yield ['skip'];
        yield ['end'];
        yield ['step'];
        yield ['columns'];
        yield ['title'];
        yield ['lookup'];
        yield ['warning', false];
    }

    /**
     * @dataProvider debugProvider
     * @test
     *
     * @param string $type
     * @param bool   $willWrite
     */
    public function will_write_to_debug_log(string $type, $willWrite = true): void
    {
        $this->writetest($type, $willWrite, 'DEBUG');
    }

    public function noticeProvider(): Generator
    {
        yield ['startup'];
        yield ['ltime'];
        yield ['dbtime'];
        yield ['warning', false];
    }

    /**
     * @dataProvider noticeProvider
     * @test
     *
     * @param string $type
     * @param bool   $willWrite
     */
    public function will_write_to_notice(string $type, $willWrite = true): void
    {
        $this->writetest($type, $willWrite, 'NOTICE');
    }

    /**
     * @test
     */
    public function will_write_warning(): void
    {
        $this->logger->log('logged', 'warning');
        rewind($this->stream);
        $content = stream_get_contents($this->stream);
        $this->assertNotFalse(strpos($content, 'log.WARNING: logged'));
    }

    /**
     * @test
     */
    public function will_write_error(): void
    {
        $this->logger->log('logged', 'error');
        rewind($this->stream);
        $content = stream_get_contents($this->stream);
        $this->assertNotFalse(strpos($content, 'log.ERROR: logged'));
    }

    /**
     * @test
     */
    public function will_write_info(): void
    {
        $this->logger->log('logged', 'info');
        rewind($this->stream);
        $content = stream_get_contents($this->stream);
        $this->assertNotFalse(strpos($content, 'log.INFO: logged'));
    }

    protected function setUp(): void
    {
        $this->stream = fopen('php://memory', 'wb');
        $handler = new StreamHandler($this->stream);
        $logger = new \Monolog\Logger('log');
        $logger->pushHandler($handler);

        $this->logger = new Logger($logger, true);
    }

    private function writetest(string $type, bool $willWrite, string $realType): void
    {
        $this->logger->log('logged', $type);
        rewind($this->stream);
        $content = stream_get_contents($this->stream);

        if ($willWrite) {
            $this->assertNotFalse(strpos($content, "log.{$realType}: {$type}"));
        } else {
            $this->assertFalse(strpos($content, "log.{$realType}: {$type}"));
        }
    }
}
