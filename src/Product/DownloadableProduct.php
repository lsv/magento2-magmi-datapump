<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product;

use Lsv\Datapump\Product\ValidationTraits\DescriptionTrait;
use Lsv\Datapump\Product\ValidationTraits\QuantityTrait;
use Lsv\Datapump\Product\ValidationTraits\SkuTrait;
use Lsv\Datapump\Product\ValidationTraits\StoreTrait;
use Lsv\Datapump\Product\ValidationTraits\VisibilityTrait;
use Lsv\Datapump\Utils\DataObject;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DownloadableProduct extends AbstractProduct
{
    use QuantityTrait;
    use DescriptionTrait;
    use SkuTrait;
    use StoreTrait;
    use VisibilityTrait;

    public function __construct()
    {
        parent::__construct();

        $this->data = new DataObject(
            [
                'type' => $this->getProductType(),
                'sku' => null,
                'name' => null,
                'description' => null,
                'short_description' => null,
                'tax_class_id' => 'Taxable Goods',
                'price' => null,
                'use_config_manage_stock' => null,
                'manage_stock' => null,
                'is_in_stock' => null,
                'qty' => null,
                'attribute_set' => 'Default',
                'visibility' => self::VISIBILITY_CATALOG_SEARCH,
                'weight' => null,
                'product_has_weight' => false,
                'status' => 1,
                'store' => 'admin',
                'file' => null,
                'is_shareable' => false,
                'number_of_downloads' => 0,
            ]
        );
    }

    public function setFile(File $file): self
    {
        $this->set('file', $file);

        return $this;
    }

    public function setSample(File $file): self
    {
        $this->set('sample', $file);

        return $this;
    }

    public function setSharing(bool $allowSharing): self
    {
        $this->set('is_shareable', $allowSharing);

        return $this;
    }

    public function setNumberOfDownloads(int $numberOfDownloads): self
    {
        $this->set('number_of_downloads', $numberOfDownloads);

        return $this;
    }

    public function afterValidate(): void
    {
        /** @var File $file */
        $file = $this->get('file');
        $this->set('file', $file->getPathname());

        /** @var File $sample */
        if ($sample = $this->get('sample')) {
            $this->set('sample', $sample->getPathname());
        }
    }

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
                'file',
                'is_shareable',
                'number_of_downloads',
            ]
        );

        $resolver->setAllowedTypes('number_of_downloads', 'int');
        $resolver->setAllowedTypes('is_shareable', 'bool');
        $resolver->setAllowedTypes('file', File::class);
        $resolver->setAllowedValues('type', self::TYPE_DOWNLOADABLE);
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
        return self::TYPE_DOWNLOADABLE;
    }
}
