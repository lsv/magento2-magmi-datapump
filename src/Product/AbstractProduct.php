<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product;

use Lsv\Datapump\Product\Data\DataInterface;
use Lsv\Datapump\Utils\DataObject;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractProduct implements ProductInterface
{
    /**
     * Visibility catalog and search.
     */
    public const VISIBILITY_CATALOG_SEARCH = 4;
    /**
     * Visibility catalog only.
     */
    public const VISIBILITY_CATALOG = 2;
    /**
     * Visibility search only.
     */
    public const VISIBILITY_SEARCH = 3;
    /**
     * Visibility not visible.
     */
    public const VISIBILITY_NOTVISIBLE = 1;

    /**
     * Magento type configurable.
     */
    public const TYPE_CONFIGURABLE = 'configurable';
    /**
     * Magento type simple.
     */
    public const TYPE_SIMPLE = 'simple';
    /**
     * Magento type simple.
     */
    public const TYPE_DOWNLOADABLE = 'downloadable';

    /**
     * @var DataObject
     */
    protected $data;

    /**
     * @var DataObject
     */
    protected $extraData;

    public function __construct()
    {
        $this->extraData = new DataObject();
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
            ]
        );
    }

    public function beforeValidate(): void
    {
        $this->setType($this->getProductType());
        if (!$this->data->get('short_description')) {
            $this->data->set('short_description', $this->data->get('description'));
        }
    }

    public function afterValidate(): void
    {
    }

    public function beforeImport(): void
    {
    }

    public function afterImport(): void
    {
    }

    /**
     * Needs to be the NAME of the store VIEW! - NOT the code!
     */
    public function setStore(string $store): self
    {
        return $this->set('store', $store);
    }

    public function getStore()
    {
        return $this->get('store');
    }

    /**
     * Needs to be the NAME of the store VIEW! - NOT the code!
     */
    public function setStoreViewName(string $store): self
    {
        return $this->set('store', $store);
    }

    public function getStoreViewName()
    {
        return $this->getStore();
    }

    public function setAttributeSet(string $set): self
    {
        return $this->set('attribute_set', $set);
    }

    public function getAttributeSet()
    {
        return $this->get('attribute_set');
    }

    public function setType(?string $type): self
    {
        return $this->set('type', $type);
    }

    public function getType()
    {
        return $this->get('type');
    }

    public function setSku(string $sku): self
    {
        return $this->set('sku', $sku);
    }

    public function getSku(): ?string
    {
        return $this->get('sku');
    }

    public function setVisibility(bool $visibleInCatalog = true, bool $searchAble = true): self
    {
        if ($visibleInCatalog && $searchAble) {
            return $this->set('visibility', self::VISIBILITY_CATALOG_SEARCH);
        }

        if ($visibleInCatalog) {
            return $this->set('visibility', self::VISIBILITY_CATALOG);
        }

        if ($searchAble) {
            return $this->set('visibility', self::VISIBILITY_SEARCH);
        }

        return $this->set('visibility', self::VISIBILITY_NOTVISIBLE);
    }

    public function getVisibility()
    {
        return $this->get('visibility');
    }

    public function setDescription(string $description): self
    {
        return $this->set('description', $description);
    }

    public function getDescription()
    {
        return $this->get('description');
    }

    public function setShortDescription(string $description): self
    {
        return $this->set('short_description', $description);
    }

    public function getShortDescription()
    {
        return $this->get('short_description');
    }

    public function setName(string $name): self
    {
        return $this->set('name', $name);
    }

    public function getName()
    {
        return $this->get('name');
    }

    public function setWeight(float $weight): self
    {
        $this->set('weight', $weight);
        $this->set('product_has_weight', true);

        return $this;
    }

    public function getWeight()
    {
        return $this->get('weight');
    }

    public function hasWeight()
    {
        return  $this->get('product_has_weight');
    }

    public function setStatus(bool $enabled): self
    {
        return $this->set('status', $enabled ? 1 : 2);
    }

    /**
     * @return bool|int
     */
    public function getStatus(bool $asBool = false)
    {
        $status = $this->get('status');
        if ($asBool) {
            return 1 === $status;
        }

        return $status;
    }

    public function setEnabled(): self
    {
        return $this->setStatus(true);
    }

    public function setDisabled(): self
    {
        return $this->setStatus(false);
    }

    public function setPrice(float $price): self
    {
        return $this->set('price', $price);
    }

    public function getPrice()
    {
        return $this->get('price');
    }

    public function setTaxClass(string $taxName): self
    {
        return $this->set('tax_class_id', $taxName);
    }

    public function getTaxClass()
    {
        return $this->get('tax_class_id');
    }

    /**
     * @param int|float|null $quantity
     */
    public function setQuantity($quantity, bool $alwaysInStock = false): self
    {
        if (null === $quantity) {
            $this->set('use_config_manage_stock', 0);
            $this->set('manage_stock', 0);
            $this->set('is_in_stock', 1);
            $this->set('qty', 0);

            return $this;
        }

        $this->set('use_config_manage_stock', 1);
        $this->set('is_in_stock', 1);
        $this->set('manage_stock', 1);
        if ($quantity <= 0) {
            $this->set('is_in_stock', 0);
            if ($alwaysInStock) {
                $this->set('is_in_stock', 1);
            }
        }

        $this->set('qty', $quantity);

        return $this;
    }

    /**
     * @return int|float|null
     */
    public function getQuantity()
    {
        return $this->get('qty');
    }

    public function getData(): array
    {
        return $this->data->getData();
    }

    public function getExtraData(): array
    {
        return $this->extraData->getData();
    }

    public function getMergedData(): array
    {
        return array_merge($this->data->getData(), $this->extraData->getData());
    }

    /**
     * @throws NoSuchOptionException
     */
    public function validateProduct(): void
    {
        $this->beforeValidate();
        $resolver = new OptionsResolver();
        try {
            $reflection = new ReflectionClass($this);
            $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
            foreach ($methods as $method) {
                if (0 === strpos($method->getName(), 'setValidation')) {
                    $this->{$method->getName()}($resolver);
                }
            }
            // @codeCoverageIgnoreStart
        } catch (ReflectionException $exception) {
        }
        // @codeCoverageIgnoreEnd

        $this->validate($resolver);
        $this->data->setData($resolver->resolve($this->data->getData()));
        $this->afterValidate();
    }

    public function getDebug(): string
    {
        $data = $this->getMergedData();

        $string = implode(
            ', ',
            array_map(
                static function ($key, $value) {
                    return "{$key}={$value}";
                },
                array_keys($data),
                $data
            )
        );

        return "SKU: '{$this->getSku()}': {$string}";
    }

    public function set(string $key, $value): self
    {
        if ($this->data->has($key)) {
            $this->data->set($key, $value);
        } else {
            $this->extraData->set($key, $value);
        }

        return $this;
    }

    public function has(string $key): bool
    {
        if ($this->data->has($key)) {
            return true;
        }

        if ($this->extraData->has($key)) {
            return true;
        }

        return false;
    }

    public function get(string $key)
    {
        if ($this->data->has($key)) {
            return $this->data->get($key);
        }

        if ($this->extraData->has($key)) {
            return $this->extraData->get($key);
        }

        return null;
    }

    public function addData(DataInterface $data): self
    {
        if ($data->allowMultiple() && $this->extraData->has($data->getKey())) {
            $current = $this->extraData->get($data->getKey());
            $this->extraData->set($data->getKey(), sprintf('%s%s%s', $current, $data->arrayMergeString(), $data->getData()));
        } else {
            $this->extraData->set($data->getKey(), $data->getData());
        }

        return $this;
    }

    abstract protected function validate(OptionsResolver $resolver): void;

    abstract protected function getProductType(): ?string;
}
