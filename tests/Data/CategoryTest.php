<?php

declare(strict_types=1);

namespace Lsv\DatapumpTest\Data;

use Lsv\Datapump\Data\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    /**
     * @test
     */
    public function can_create_category(): void
    {
        $category = new Category('categoryName');

        $this->assertSame(';;', $category->arrayMergeString());
        $this->assertTrue($category->allowMultiple());
        $this->assertSame('categories', $category->getKey());

        $result = 'categoryName::1::1::1';

        $this->assertSame($result, $category->getData());
    }

    /**
     * @test
     */
    public function can_create_category_but_not_as_active(): void
    {
        $category = new Category('categoryName', false);

        $result = 'categoryName::0::1::1';

        $this->assertSame($result, $category->getData());
    }

    /**
     * @test
     */
    public function can_create_category_but_not_as_anchor(): void
    {
        $category = new Category('categoryName', true, false);
        $result = 'categoryName::1::0::1';
        $this->assertSame($result, $category->getData());
    }

    /**
     * @test
     */
    public function can_create_category_but_not_included_in_menu(): void
    {
        $category = new Category('categoryName', true, true, false);
        $result = 'categoryName::1::1::0';
        $this->assertSame($result, $category->getData());
    }

    /**
     * @test
     */
    public function can_create_category_levels(): void
    {
        $category = new Category('level1/level2/level3');
        $result = 'level1/level2/level3::1::1::1';
        $this->assertSame($result, $category->getData());
    }

    /**
     * @test
     */
    public function can_create_category_levels_with_different_seperator(): void
    {
        $category = new Category('level1.level2.level/3', true, true, true, '.');
        $result = 'level1/level2/level\/3::1::1::1';
        $this->assertSame($result, $category->getData());
    }
}
