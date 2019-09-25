# Magento 2 - Magmi data dump

Using object oriented methods to use with [Magmi](https://github.com/macopedia/magmi-m2) for [Magento 2](https://magento.com/)

### Install

`composer require lsv/magento2-magmi-dump`

At the moment I have made some changes to the magmi repositories, which has not been accepted yet, so you will need to add

```json
"repositories": [
    {
        "type": "git",
        "url": "git@github.com:lsv/magmi-m2.git"
    }
]
``` 

to your composer.json

### Usage

You can see a working magento2 module [here](https://github.com/lsv/magento2-magmi-datapump-magento-module).

```php
use Lsv\Datapump\Configuration;
use Lsv\Datapump\ItemHolder;
use Lsv\Datapump\Logger;
use Lsv\Datapump\Product\ConfigurableProduct;use Monolog\Handler\StreamHandler;
use Lsv\Datapump\Product\SimpleProduct;

// First we need to create a logger, due to Magmi is using a non standardized method, we need to make a little of a work around
$stream = __DIR__ . '/logfile.log';
$monologHandler = new StreamHandler($stream);
$monolog = new Monolog\Logger('default', [$monologHandler]);
$logger = new Logger($monolog);

// Next we need a configuration
$configuration = new Configuration(
    'path/to/magento/root', // Path to the root of your magento installation
    'databse name', // The name of the database
    'database host', // The host of the database
    'database username', // The username to the database
    'database password' // The password to the database
);

// Now we need a instance of Magmi

// Now we can create our ItemHolder which will hold the product we gonna import
$magmi = \Magmi_DataPumpFactory::getDataPumpInstance('productimport');

$holder = new ItemHolder($configuration, $logger, $magmi);

// Now we can start by adding products to the item holder
$simpleProduct = (new SimpleProduct())
    ->setName('Simple product')
    ->setSku('sku')
    ->setDescription('Product description')
    ->setPrice(15.99)
    ->setTaxClass('tax name')
    ->setQuantity(10);

// This is the minimum data required for a simple product
// We can ofcourse add other attributes to the product
// But first you will need to manually add the attribute to the attribute set in magento backend, before it can be used

$simpleProduct->set('name_of_your_attribute', 'the_value');

// Now we have our simple product, we can now add it to our ItemHolder

$holder->addProduct($simpleProduct);

// And we can now import it
$holder->import();

// We have the option to create a "dry-run" which will not write to the database, but it will send the data to the console
// $holder->import(true) 

// Configurable product

// A configurable product must have a list of attributes to create the configurable product from the simple products
// Here we use both color and size as attributes we will create the configurable product out of

$configurable = new ConfigurableProduct(['color', 'size']);
// A configurable product also need some magento data
$configurable
    ->setName('Configurable product')
    ->setSku('configurable-product')
    ->setDescription('description')
    ->setQuantity(0, true); // With 0 and true, we will set the configurable product to always be in stock

// The configurable product price will automatically be generated out of the prices on the simple products

// Now we need to add simple products to our configurable product
$simple1 = new SimpleProduct();
$simple1->setName('simple1');
$simple1->setSku('simple1');
$simple1->setDescription('description');
$simple1->setPrice(14.99);
$simple1->setTaxClass('tax name');
$simple1->set('color', 'blue');
$simple1->set('size', 'L');
// We need to set color and size on the simple product, because it is required by the configurable product
$configurable->addSimpleProduct($simple1);

// And add another simple product to our configurable
$simple2 = new SimpleProduct();
$simple2->setName('simple2');
$simple2->setSku('simple2');
$simple2->setDescription('description');
$simple2->setPrice(13.99);
$simple2->setTaxClass('tax name');
$simple2->set('color', 'green');
$simple2->set('size', 'M');
$configurable->addSimpleProduct($simple2);

// If you already have created the simple products, you can also add them to the configurable product by using
$configurable->addSimpleSku('already-created-sku-1');
$configurable->addSimpleSku('already-created-sku-2');

// And lets add our configurable product to our itemHolder
$holder->addProduct($configurable);
// Only the configurable product should be added to the itemHolder as the simple products will automatically be imported and checked for missing attributes
```

* [How to add categories to a product](documentation/categories.md)
* [How to add images to a product](documentation/images.md)
* [More about the itemHolder](documentation/itemholder.md)
* [Product relations](documentation/relations.md)
* [Delete product](documentation/deleteproduct.md)

### TODO

* [ ] Grouped products
* [ ] Bundle products
* [ ] Warehouse inventory
* [x] Use Magento to copy media files, so its not hardcoded to media folder, and maybe can use correct storage
* [ ] Add video to product
* [ ] Delete products
* [ ] Other things?
