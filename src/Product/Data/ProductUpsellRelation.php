<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product\Data;

class ProductUpsellRelation extends ProductRelation
{
    public function getKey(): string
    {
        return 'us_skus';
    }
}
