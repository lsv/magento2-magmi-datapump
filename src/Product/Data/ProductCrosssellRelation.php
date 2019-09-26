<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product\Data;

class ProductCrosssellRelation extends ProductRelation
{
    public function getKey(): string
    {
        return 'cs_skus';
    }
}
