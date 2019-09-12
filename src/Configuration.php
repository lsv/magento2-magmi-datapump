<?php

declare(strict_types=1);

namespace Lsv\Datapump;

class Configuration
{
    /**
     * @var string
     */
    private $magentoDirectory;

    /**
     * @var string
     */
    private $databaseName;

    /**
     * @var string
     */
    private $databaseHost;

    /**
     * @var string
     */
    private $databaseUsername;

    /**
     * @var string
     */
    private $databasePassword;

    public function __construct(string $magentoDirectory, string $databaseName, string $databaseHost, string $databaseUsername, string $databasePassword)
    {
        $this->magentoDirectory = $magentoDirectory;
        $this->databaseName = $databaseName;
        $this->databaseHost = $databaseHost;
        $this->databaseUsername = $databaseUsername;
        $this->databasePassword = $databasePassword;
    }

    public function getMagentoDirectory(): string
    {
        return $this->magentoDirectory;
    }

    public function getDatabaseName(): string
    {
        return $this->databaseName;
    }

    public function getDatabaseHost(): string
    {
        return $this->databaseHost;
    }

    public function getDatabaseUsername(): string
    {
        return $this->databaseUsername;
    }

    public function getDatabasePassword(): string
    {
        return $this->databasePassword;
    }
}
