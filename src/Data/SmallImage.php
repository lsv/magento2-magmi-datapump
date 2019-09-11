<?php

declare(strict_types=1);

namespace Lsv\Datapump\Data;

class SmallImage extends ImageAbstract
{
    public function getKey(): string
    {
        return 'small_image';
    }
}
