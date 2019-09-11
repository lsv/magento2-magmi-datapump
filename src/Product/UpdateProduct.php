<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product;

use Lsv\Datapump\Product\ValidationTraits\SkuTrait;
use Lsv\Datapump\Utils\DataObject;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateProduct extends AbstractProduct
{
    use SkuTrait;

    public function __construct()
    {
        parent::__construct();

        $this->data = new DataObject(
            [
                'sku' => null,
                'type' => null,
            ]
        );
    }

    public function beforeValidate(): void
    {
    }

    protected function validate(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['type']);
        $resolver->setAllowedValues('type', [self::TYPE_SIMPLE, self::TYPE_CONFIGURABLE]);
    }

    protected function getProductType(): ?string
    {
        return null;
    }
}
