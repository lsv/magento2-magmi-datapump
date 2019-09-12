<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product\Data;

class SmallImage extends ImageAbstract
{
    public function getKey(): string
    {
        return 'small_image';
    }
}
