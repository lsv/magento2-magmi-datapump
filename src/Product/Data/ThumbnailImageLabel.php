<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product\Data;

class ThumbnailImageLabel extends BaseImageLabel
{
    public function getKey(): string
    {
        return 'thumbnail_label';
    }
}
