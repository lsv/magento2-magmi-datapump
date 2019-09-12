### How to add categories to a product

First we need a product, can be any product types

```php
use Lsv\Datapump\Product\Data\Category;
use Lsv\Datapump\Product\SimpleProduct;

$product = new SimpleProduct();

// Now we need a category

$category = new Category('level1/level2');

// And add the category to the product

$product->addData($category);
```

#### Category options

The category can take some options

```php
use Lsv\Datapump\Product\Data\Category;

$category = new Category(
    'categoryName', // The name of the category
    true, // Should the category be active (default true)
    true, // Should the category be anchor (default true)
    true, // Should the category be included in the menu (default true)
    '/' // Category level seperator
);
```

#### Levels

We can also build category levels

By using `/` in the category name we defines the levels `level1/level2/level3`.
This will create a menu structure like this

* level1
    * level2
        * level3
        
Now if we would use another seperator we can set the 4th argument in the constructor to something else than `/`

Fx

```php
use Lsv\Datapump\Product\Data\Category;

$category = new Category(
    'level1&level2&level/3',
    true,
    true,
    true,
    '&'
);
```

Now it will create a menu structure of

* level1
    * level2
        * level/3
