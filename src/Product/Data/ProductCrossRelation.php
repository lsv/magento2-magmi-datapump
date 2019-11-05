<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product\Data;

class ProductCrossRelation extends ProductRelation
{
    /**
     * They key to magmi.
     */
    public function getKey(): string
    {
        return 'xre_skus';
    }
}
