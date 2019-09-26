<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product;

use Lsv\Datapump\Exceptions\SimpleProductMissingKeyException;
use Lsv\Datapump\Product\ValidationTraits\DescriptionTrait;
use Lsv\Datapump\Product\ValidationTraits\QuantityTrait;
use Lsv\Datapump\Product\ValidationTraits\SkuTrait;
use Lsv\Datapump\Product\ValidationTraits\StoreTrait;
use Lsv\Datapump\Product\ValidationTraits\VisibilityTrait;
use Lsv\Datapump\Utils\DataObject;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigurableProduct extends AbstractProduct implements ConfigurableProductInterface
{
    use DescriptionTrait;
    use QuantityTrait;
    use SkuTrait;
    use StoreTrait;
    use VisibilityTrait;

    /**
     * @var AbstractProduct[]
     */
    private $products = [];

    /**
     * @var array
     */
    private $configurableAttributeKeys;

    public function __construct(array $configurableAttributeKeys)
    {
        parent::__construct();
        $this->setConfigurableAttributeKeys($configurableAttributeKeys);

        $this->data = new DataObject(
            [
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
            ]
        );
    }

    public function setConfigurableAttributeKeys(array $keys): self
    {
        $this->configurableAttributeKeys = $keys;

        return $this;
    }

    public function getConfigurableAttributeKeys(): array
    {
        return $this->configurableAttributeKeys;
    }

    /**
     * @param AbstractProduct $product
     *
     * @return ConfigurableProduct
     *
     * @throws SimpleProductMissingKeyException
     */
    public function addProduct(AbstractProduct $product): self
    {
        foreach ($this->configurableAttributeKeys as $key) {
            if (!$product->has($key)) {
                throw new SimpleProductMissingKeyException($product->getSku(), $key);
            }
        }

        $product->validateProduct();

        $this->products[] = $product;

        return $this;
    }

    /**
     * @return AbstractProduct[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param AbstractProduct[] $products
     *
     * @return $this
     *
     * @throws SimpleProductMissingKeyException
     */
    public function setProducts(array $products): self
    {
        $this->products = [];
        foreach ($products as $product) {
            $this->addProduct($product);
        }

        return $this;
    }

    public function countProducts(): int
    {
        return count($this->getProducts());
    }

    /**
     * @deprecated Use `addProduct` instead
     *
     * @param AbstractProduct $product
     *
     * @return $this
     *
     * @throws SimpleProductMissingKeyException
     */
    public function addSimpleProduct(AbstractProduct $product): self
    {
        @trigger_error('Use `addProduct` instead', E_USER_DEPRECATED);

        $this->addProduct($product);

        return $this;
    }

    /**
     * @deprecated Use `getProducts` instead
     *
     * @return AbstractProduct[]
     */
    public function getSimpleProducts(): array
    {
        @trigger_error('Use `getProducts` instead', E_USER_DEPRECATED);

        return $this->getProducts();
    }

    /**
     * @deprecated Use `setProducts` instead
     *
     * @param AbstractProduct[] $products
     *
     * @return ConfigurableProduct
     *
     * @throws SimpleProductMissingKeyException
     */
    public function setSimpleProducts(array $products): self
    {
        @trigger_error('Use `setProducts` instead', E_USER_DEPRECATED);

        return $this->setProducts($products);
    }

    /**
     * @deprecated Use `countProducts` instead
     *
     * @return int
     */
    public function countSimpleProducts(): int
    {
        @trigger_error('Use `countProducts` instead', E_USER_DEPRECATED);

        return $this->countProducts();
    }

    public function beforeValidate(): void
    {
        // Find lowest price
        $price = 0;
        $simpleSkus = [];
        foreach ($this->products as $simpleProduct) {
            $simpleSkus[] = $simpleProduct->getSku();
            if (null !== $simpleProduct->getPrice()) {
                if (0 === $price || $price > $simpleProduct->getPrice()) {
                    $price = $simpleProduct->getPrice();
                }
            }
        }

        $this->set('configurable_attributes', implode(',', $this->configurableAttributeKeys));
        $this->set('simple_skus', implode(',', $simpleSkus));
        if (null === $this->getPrice()) {
            $this->setPrice($price);
        }

        $this->setQuantity(null, true);

        parent::beforeValidate();
    }

    protected function validate(OptionsResolver $resolver): void
    {
        $resolver->setRequired(
            [
                'type',
                'name',
                'status',
                'price',
                'tax_class_id',
                'attribute_set',
            ]
        );
        $resolver->setAllowedValues('type', self::TYPE_CONFIGURABLE);
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
