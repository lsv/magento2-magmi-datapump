<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product\Data;

class ThumbnailImage extends ImageAbstract
{
    public function getKey(): string
    {
        return 'thumbnail';
    }
}
