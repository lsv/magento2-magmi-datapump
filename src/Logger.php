<?php

declare(strict_types=1);

namespace Lsv\Datapump;

class Logger extends \Magmi_Logger
{
    /**
     * @var \Monolog\Logger
     */
    private $logger;

    /**
     * @var bool
     */
    private $writeDebug;

    public function __construct(\Monolog\Logger $monolog, bool $writeDebug = false)
    {
        $this->logger = $monolog;
        $this->writeDebug = $writeDebug;
    }

    public function log($data, $type = null): void
    {
        switch ($type) {
            case 'startup':
            case 'itime':
            case 'dbtime':
            default:
                $this->logger->notice("{$type}: {$data}");
                break;
            case 'warning':
                $this->logger->warning($data);
                break;
            case 'debug':
            case 'skip':
            case 'end':
            case 'step':
            case 'columns':
            case 'title':
            case 'lookup':
                if ($this->writeDebug) {
                    $this->logger->debug("{$type}: {$data}");
                }
                break;
            case 'error':
                $this->logger->error($data);
                break;
            case 'info':
                $this->logger->info($data);
                break;
        }
    }
}
