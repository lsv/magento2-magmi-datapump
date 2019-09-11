<?php

declare(strict_types=1);

namespace Lsv\Datapump\Exceptions;

class NotSupportedMagmiModeException extends Exception
{
    /**
     * @var string
     */
    private $selectedMode;
    /**
     * @var array
     */
    private $availableModes;

    public function __construct(string $selectedMode, array $availableModes)
    {
        parent::__construct();
        $this->selectedMode = $selectedMode;
        $this->availableModes = $availableModes;
    }

    public function getSelectedMode(): string
    {
        return $this->selectedMode;
    }

    public function getAvailableModes(): array
    {
        return $this->availableModes;
    }
}
