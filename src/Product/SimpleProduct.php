<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product;

use Lsv\Datapump\Product\ValidationTraits\DescriptionTrait;
use Lsv\Datapump\Product\ValidationTraits\QuantityTrait;
use Lsv\Datapump\Product\ValidationTraits\SkuTrait;
use Lsv\Datapump\Product\ValidationTraits\StoreTrait;
use Lsv\Datapump\Product\ValidationTraits\VisibilityTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SimpleProduct extends AbstractProduct
{
    use DescriptionTrait;
    use QuantityTrait;
    use SkuTrait;
    use StoreTrait;
    use VisibilityTrait;

    protected function validate(OptionsResolver $resolver): void
    {
        $resolver->setRequired(
            [
                'type',
                'name',
                'weight',
                'product_has_weight',
                'status',
                'price',
                'tax_class_id',
                'attribute_set',
            ]
        );
        $resolver->setAllowedValues('type', self::TYPE_SIMPLE);
        $resolver->setAllowedTypes('description', 'string');
        $resolver->setAllowedTypes('short_description', 'string');
        $resolver->setAllowedTypes('weight', ['int', 'float', 'null']);
        $resolver->setAllowedValues('product_has_weight', [true, false]);
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
