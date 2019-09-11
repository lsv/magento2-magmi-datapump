<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product\ValidationTraits;

use Symfony\Component\OptionsResolver\OptionsResolver;

trait StoreTrait
{
    public function setValidationStore(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['store']);
        $resolver->setAllowedTypes('store', 'string');
    }
}
