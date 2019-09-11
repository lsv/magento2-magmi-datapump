<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product\ValidationTraits;

use Symfony\Component\OptionsResolver\OptionsResolver;

trait DescriptionTrait
{
    public function setValidationDescription(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['description', 'short_description']);
        $resolver->setAllowedTypes('description', 'string');
        $resolver->setAllowedTypes('short_description', 'string');
    }
}
