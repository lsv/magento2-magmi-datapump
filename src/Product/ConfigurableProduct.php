<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product;

use Lsv\Datapump\Exceptions\SimpleProductMissingKeyException;
use Lsv\Datapump\Product\ValidationTraits\DescriptionTrait;
use Lsv\Datapump\Product\ValidationTraits\QuantityTrait;
use Lsv\Datapump\Product\ValidationTraits\SkuTrait;
use Lsv\Datapump\Product\ValidationTraits\StoreTrait;
use Lsv\Datapump\Utils\DataObject;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigurableProduct extends AbstractProduct implements ConfigurableProductInterface
{
    use DescriptionTrait;
    use QuantityTrait;
    use SkuTrait;
    use StoreTrait;

    /**
     * @var SimpleProduct[]
     */
    private $simpleProducts = [];

    /**
     * @var array
     */
    private $configurableAttributeKeys;

    public function __construct(array $configurableAttributeKeys)
    {
        parent::__construct();
        $this->configurableAttributeKeys = $configurableAttributeKeys;

        $this->data = new DataObject([
            'type' => $this->getProductType(),
            'sku' => null,
            'name' => null,
            'description' => null,
            'short_description' => null,
            'tax_class_id' => null,
            'price' => null,
            'attribute_set' => 'Default',
            'visibility' => self::VISIBILITY_CATALOG_SEARCH,
            'status' => 1,
            'store' => 'admin',
            'use_config_manage_stock' => null,
            'manage_stock' => null,
            'is_in_stock' => null,
            'qty' => null,
        ]);
    }

    /**
     * @param SimpleProduct $product
     *
     * @return ConfigurableProduct
     *
     * @throws SimpleProductMissingKeyException
     */
    public function addSimpleProduct(SimpleProduct $product): self
    {
        foreach ($this->configurableAttributeKeys as $key) {
            if (!$product->has($key)) {
                throw new SimpleProductMissingKeyException($product->getSku(), $key);
            }
        }

        $product->validateProduct();

        $this->simpleProducts[] = $product;

        return $this;
    }

    public function getSimpleProducts(): array
    {
        return $this->simpleProducts;
    }

    /**
     * @param array $products
     *
     * @return ConfigurableProduct
     *
     * @throws SimpleProductMissingKeyException
     */
    public function setSimpleProducts(array $products): self
    {
        $this->simpleProducts = [];
        foreach ($products as $product) {
            $this->addSimpleProduct($product);
        }

        return $this;
    }

    public function countSimpleProducts(): int
    {
        return count($this->getSimpleProducts());
    }

    public function beforeValidate(): void
    {
        // Find lowest price
        $price = 0;
        $simpleSkus = [];
        foreach ($this->simpleProducts as $simpleProduct) {
            $simpleSkus[] = $simpleProduct->getSku();
            if (0 === $price || $price > $simpleProduct->getPrice()) {
                $price = $simpleProduct->getPrice();
            }
        }

        $this->set('configurable_attributes_array', $this->configurableAttributeKeys);
        $this->set('simple_skus', implode(',', $simpleSkus));
        $this->setPrice($price);
        $this->setQuantity(null, true);

        parent::beforeValidate();
    }

    protected function validate(OptionsResolver $resolver): void
    {
        $resolver->setRequired(
            [
                'type',
                'visibility',
                'name',
                'status',
                'price',
                'tax_class_id',
                'attribute_set',
            ]
        );
        $resolver->setAllowedValues('type', self::TYPE_CONFIGURABLE);
        $resolver->setAllowedValues(
            'visibility',
            [self::VISIBILITY_NOTVISIBLE,
                self::VISIBILITY_CATALOG,
                self::VISIBILITY_SEARCH,
                self::VISIBILITY_CATALOG_SEARCH, ]
        );
        $resolver->setAllowedTypes('name', 'string');
        $resolver->setAllowedValues('status', [1, 2]);
        $resolver->setAllowedTypes('price', 'float');
        $resolver->setAllowedTypes('tax_class_id', 'string');
    }

    protected function getProductType(): ?string
    {
        return self::TYPE_CONFIGURABLE;
    }
}
