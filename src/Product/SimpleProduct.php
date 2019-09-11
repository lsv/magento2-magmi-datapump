<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product;

use Lsv\Datapump\Product\ValidationTraits\DescriptionTrait;
use Lsv\Datapump\Product\ValidationTraits\QuantityTrait;
use Lsv\Datapump\Product\ValidationTraits\SkuTrait;
use Lsv\Datapump\Product\ValidationTraits\StoreTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SimpleProduct extends AbstractProduct
{
    use DescriptionTrait;
    use QuantityTrait;
    use SkuTrait;
    use StoreTrait;

    protected function validate(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            'type', 'visibility', 'name', 'weight', 'status', 'price', 'tax_class_id', 'attribute_set',
        ]);
        $resolver->setAllowedValues('type', self::TYPE_SIMPLE);
        $resolver->setAllowedValues('visibility', [self::VISIBILITY_NOTVISIBLE, self::VISIBILITY_CATALOG, self::VISIBILITY_SEARCH, self::VISIBILITY_CATALOG_SEARCH]);
        $resolver->setAllowedTypes('description', 'string');
        $resolver->setAllowedTypes('short_description', 'string');
        $resolver->setAllowedTypes('weight', ['int', 'float']);
        $resolver->setAllowedValues('status', [1, 2]);
        $resolver->setAllowedTypes('price', 'float');
        $resolver->setAllowedTypes('tax_class_id', 'string');
        $resolver->setAllowedTypes('attribute_set', 'string');
    }

    protected function getProductType(): ?string
    {
        return self::TYPE_SIMPLE;
    }
}
