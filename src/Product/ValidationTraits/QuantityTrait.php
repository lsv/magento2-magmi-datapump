<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product\ValidationTraits;

use Symfony\Component\OptionsResolver\OptionsResolver;

trait QuantityTrait
{
    public function setValidationQuantity(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['qty', 'use_config_manage_stock', 'manage_stock', 'is_in_stock']);
        $resolver->setAllowedTypes('qty', ['null', 'int', 'float']);
        $resolver->setAllowedValues('use_config_manage_stock', [1, 0]);
        $resolver->setAllowedValues('manage_stock', [1, 0]);
        $resolver->setAllowedValues('is_in_stock', [1, 0]);
    }
}
