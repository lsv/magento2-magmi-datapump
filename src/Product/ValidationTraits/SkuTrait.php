<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product\ValidationTraits;

use Symfony\Component\OptionsResolver\OptionsResolver;

trait SkuTrait
{
    public function setValidationSku(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['sku']);
        $resolver->setAllowedTypes('sku', 'string');
    }
}
