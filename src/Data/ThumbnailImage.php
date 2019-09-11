<?php

declare(strict_types=1);

namespace Lsv\Datapump\Data;

class ThumbnailImage extends ImageAbstract
{
    public function getKey(): string
    {
        return 'thumbnail';
    }
}
