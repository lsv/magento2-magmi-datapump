<?php

declare(strict_types=1);

namespace Lsv\Datapump;

class Configuration
{
    /**
     * @var string
     */
    private $magentoDirectory;

    public function __construct(string $magentoDirectory)
    {
        $this->magentoDirectory = $magentoDirectory;
    }

    public function getMagentoDirectory(): string
    {
        return $this->magentoDirectory;
    }
}
