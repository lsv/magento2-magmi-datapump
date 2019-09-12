<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product\Data;

class SmallImageLabel extends BaseImageLabel
{
    public function getKey(): string
    {
        return 'small_image_label';
    }
}
