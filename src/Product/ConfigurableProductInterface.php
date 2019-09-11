<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product;

interface ConfigurableProductInterface
{
    /**
     * @return AbstractProduct[]
     */
    public function getSimpleProducts(): array;

    public function countSimpleProducts(): int;
}
