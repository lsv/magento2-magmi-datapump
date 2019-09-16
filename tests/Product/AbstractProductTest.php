<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Product;

use Generator;
use Lsv\Datapump\Product\AbstractProduct;
use Lsv\Datapump\Product\Data\BaseImage;
use Lsv\Datapump\Product\Data\Category;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbstractProductTest extends TestCase
{
    /**
     * @var AbstractProduct
     */
    private $product;

    public function gettersDataProvider(): Generator
    {
        yield ['store'];
        yield ['storeViewName', 'de', 'store'];
        yield ['attributeSet', 'value', 'attribute_set'];
        yield ['type'];
        yield ['description'];
        yield ['shortDescription', 'stort description', 'short_description'];
        yield ['name'];
        yield ['weight', 15.3];
        yield ['taxClass', 'tax', 'tax_class_id'];
        yield ['quantity', 11.3, 'qty'];
    }

    /**
     * @dataProvider gettersDataProvider
     * @test
     *
     * @param string      $method
     * @param mixed       $value
     * @param string|null $fieldName
     */
    public function getters_setters(string $method, $value = 'value', string $fieldName = null): void
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
     * @param string $method
     * @param mixed  $value
     * @param bool   $inDataObject
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
     *
     * @param bool $catalog
     * @param bool $searchable
     * @param int  $result
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

        $this->assertStringContainsString($data->arrayMergeString(), $this->product->getExtraData()[$data->getKey()]);
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
        $this->assertIsNotArray($this->product->getExtraData()[$data->getKey()]);
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
