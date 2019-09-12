### ItemHolder

#### Arguments

```php
use Lsv\Datapump\ItemHolder;
use Symfony\Component\Console\Output\ConsoleOutput;

$output = new ConsoleOutput();
// The output can be anything that implements Symfony/Console OutputInterface
// This output will ONLY have a progressbar
// The info/error/debug will be in the $logger

$itemHolder = new ItemHolder(
    $configuration, // See usages
    $logger, // See usages
    $magmi, // See usages
    $output // Instance of a OutputInterface
);
```

#### Modes

```php
use Lsv\Datapump\ItemHolder;

$itemHolder = new ItemHolder(...);
$itemHolder->setMagmiMode('mode');
```

* Default mode is `ItemHolder::MAGMI_CREATE_UPDATE` which will create a product if not found, and if found it will update it.
* There are also `ItemHolder::MAGMI_UPDATE` which will only update products, meaning it will skip if product is not found (good for fx quantity updates)
* And the last mode is `ItemHolder::MAGMI_CREATE` which will only create products, and if a product exists it will skip it, meaning it will NOT update it

#### Count of products

For debugging purposes, when you have added all your products, you can get a count of how many products that are in the ItemHolder

```php
use Lsv\Datapump\ItemHolder;

$itemHolder = new ItemHolder(...);
echo $itemHolder->countProducts();
```

#### Reset

If you want, you can also reset the products

```php
use Lsv\Datapump\ItemHolder;

$itemHolder = new ItemHolder(...);
$itemHolder->reset();
```

#### Adding multiple products

You can add many products at once ***be aware this WILL reset the products before using it!***

```php
use Lsv\Datapump\ItemHolder;use Lsv\Datapump\Product\SimpleProduct;

$products = [
    new SimpleProduct(),
    new SimpleProduct(),
    new SimpleProduct(),
];

$itemHolder = new ItemHolder(...);
$itemHolder->setProducts($products);
```

