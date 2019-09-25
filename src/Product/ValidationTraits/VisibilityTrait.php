<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product\ValidationTraits;

use Lsv\Datapump\Product\AbstractProduct;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait VisibilityTrait
{
    public function setValidationVisibility(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['visibility']);
        $resolver->setAllowedValues(
            'visibility',
            [
                AbstractProduct::VISIBILITY_NOTVISIBLE,
                AbstractProduct::VISIBILITY_CATALOG,
                AbstractProduct::VISIBILITY_SEARCH,
                AbstractProduct::VISIBILITY_CATALOG_SEARCH,
            ]
        );
    }
}
