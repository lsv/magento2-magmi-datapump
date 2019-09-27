<?php

declare(strict_types=1);

namespace Lsv\Datapump\Exceptions;

class MissingDataException extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
