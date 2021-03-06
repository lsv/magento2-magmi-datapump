<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Product;

use Generator;
use Lsv\Datapump\Product\AbstractProduct;
use Lsv\Datapump\Product\Data\BaseImage;
use Lsv\Datapump\Product\Data\Category;
use Lsv\Datapump\Product\UpdateProduct;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbstractProductTest extends TestCase
{
    /**
     * @var AbstractProduct
     */
    private $product;

    /**
     * @test
     */
    public function store(): void
    {
        $this->getters_setters('store');
    }

    /**
     * @test
     */
    public function storeview(): void
    {
        $this->getters_setters('storeViewName', 'de', 'store');
    }

    /**
     * @test
     */
    public function attributeset(): void
    {
        $this->getters_setters('attributeSet', 'value', 'attribute_set');
    }

    /**
     * @test
     */
    public function type(): void
    {
        $this->getters_setters('type');
    }

    /**
     * @test
     */
    public function description(): void
    {
        $this->getters_setters('description');
    }

    /**
     * @test
     */
    public function shortDescription(): void
    {
        $this->getters_setters('shortDescription', 'value', 'short_description');
    }

    /**
     * @test
     */
    public function name(): void
    {
        $this->getters_setters('name');
    }

    /**
     * @test
     */
    public function weight(): void
    {
        $this->getters_setters('weight', 15.3);
    }

    /**
     * @test
     */
    public function taxClass(): void
    {
        $this->getters_setters('taxClass', 'tax', 'tax_class_id');
    }

    /**
     * @test
     */
    public function quantity(): void
    {
        $this->getters_setters('quantity', 11.3, 'qty');
    }

    /**
     * @test
     */
    public function product(): void
    {
        $product = (new UpdateProduct())
            ->setStore('store')
            ->setAttributeSet('set')
            ->setShortDescription('short')
            ->setTaxClass('tax')
            ->setQuantity(2);

        $this->assertSame('store', $product->getStoreViewName());
        $this->assertSame('set', $product->getAttributeSet());
        $this->assertSame('tax', $product->getTaxClass());
        $this->assertSame(2, $product->getQuantity());
    }

    protected function getters_setters(string $method, $value = 'value', string $fieldName = null): void
    {
        $setter = 'set'.ucfirst($method);
        $getter = 'get'.ucfirst($method);
        $this->assertTrue(method_exists($this->product, $setter));
        $this->assertTrue(method_exists($this->product, $getter));

        $this->product->{$setter}($value);
        $this->assertSame($value, $this->product->{$getter}());

        if (!$fieldName) {
            $fieldName = $method;
        }

        $this->assertTrue($this->product->has($fieldName), $method);
        $this->assertSame($value, $this->product->getData()[$fieldName]);
    }

    /**
     * @test
     */
    public function will_return_null_if_trying_to_get_data_that_not_exists(): void
    {
        $this->assertNull($this->product->get('does_not_exists'));
    }

    /**
     * @test
     */
    public function product_status(): void
    {
        $this->product->setEnabled();
        $this->assertSame(1, $this->product->getData()['status']);
        $this->assertTrue($this->product->getStatus(true));
        $this->assertSame(1, $this->product->getStatus());

        $this->product->setDisabled();
        $this->assertSame(2, $this->product->getData()['status']);
        $this->assertFalse($this->product->getStatus(true));
        $this->assertSame(2, $this->product->getStatus());
    }

    /**
     * @test
     */
    public function product_stock(): void
    {
        $this->product->setQuantity(0);
        $this->assertSame(0, $this->product->getData()['is_in_stock']);

        $this->product->setQuantity(0, true);
        $this->assertSame(1, $this->product->getData()['is_in_stock']);
    }

    /**
     * @test
     */
    public function product_weight(): void
    {
        $this->assertNull($this->product->getWeight());
        $this->assertFalse($this->product->hasWeight());

        $this->product->setWeight(1);
        $this->assertSame(1.0, $this->product->getWeight());
        $this->assertTrue($this->product->hasWeight());
    }

    public function dataProvider(): Generator
    {
        yield ['store', 'admin', true];
        yield ['foo', 'foo', false];
    }

    /**
     * @dataProvider dataProvider
     * @test
     *
     * @param mixed $value
     */
    public function values(string $method, $value, bool $inDataObject): void
    {
        $this->product->set($method, $value);
        $this->assertSame($value, $this->product->get($method));
        if ($inDataObject) {
            $this->assertArrayHasKey($method, $this->product->getData());
            $this->assertSame($value, $this->product->getData()[$method]);
        } else {
            $this->assertArrayNotHasKey($method, $this->product->getData());

            $this->assertArrayHasKey($method, $this->product->getExtraData());
            $this->assertSame($value, $this->product->getExtraData()[$method]);
        }
    }

    public function visibilityProvider(): Generator
    {
        yield [true, true, AbstractProduct::VISIBILITY_CATALOG_SEARCH];
        yield [false, true, AbstractProduct::VISIBILITY_SEARCH];
        yield [true, false, AbstractProduct::VISIBILITY_CATALOG];
        yield [false, false, AbstractProduct::VISIBILITY_NOTVISIBLE];
    }

    /**
     * @dataProvider visibilityProvider
     * @test
     */
    public function set_visibility(bool $catalog, bool $searchable, int $result): void
    {
        $this->product->setVisibility($catalog, $searchable);
        $this->assertSame($result, $this->product->getData()['visibility']);
        $this->assertSame($result, $this->product->getVisibility());
    }

    /**
     * @test
     */
    public function can_add_multiple_data(): void
    {
        $data = new Category('categoryName');
        $this->product->addData($data);

        $data2 = new Category('categoryName2');
        $this->product->addData($data2);

        $this->assertNotContains($data->getKey(), $this->product->getExtraData());
        [$cat1, $cat2] = explode($data->arrayMergeString(), $this->product->getExtraData()[$data->getKey()]);
        $this->assertSame($cat1, 'categoryName::1::1::1');
        $this->assertSame($cat2, 'categoryName2::1::1::1');
    }

    /**
     * @test
     */
    public function can_add_single_data(): void
    {
        $file = new File(__DIR__.'/../_image/image.png');
        $data = new BaseImage($file);
        $this->product->addData($data);
        $this->assertNotContains($data->getKey(), $this->product->getExtraData());
        $this->assertSame('+'.$file->getPathname(), $this->product->getExtraData()[$data->getKey()]);
    }

    protected function setUp(): void
    {
        $this->product = new class() extends AbstractProduct {
            protected function validate(OptionsResolver $resolver): void
            {
            }

            protected function getProductType(): ?string
            {
                return null;
            }
        };
    }
}
