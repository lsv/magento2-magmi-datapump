### Tier price

**Tier pricing is not available at this moment.**

**Do not use!**

#### Group tier price

To add a tier pricing for a customer group we can do this

```php
use Lsv\Datapump\Product\Data\GroupTierPrice;
use Lsv\Datapump\Product\SimpleProduct;

$product = new SimpleProduct();
$product->setPrice(21.99); // Default price

$customerGroupName = 'name of the customer group';
$price = 14.99; // The price for the product to that customer group 

$tier = new GroupTierPrice($customerGroupName, $price);

$product->addData($tier);
```  

#### Group tier price with percent

If you want to use percent you can use this

```php
use Lsv\Datapump\Product\Data\GroupTierPricePercent;
use Lsv\Datapump\Product\SimpleProduct;

$product = new SimpleProduct();
$product->setPrice(21.99); // Default price

$customerGroupName = 'name of the customer group';
$percent = -10.5;
// This will give a discount of 10.5% of the standard price, for that group
// You can also write 10.5, this will add 10.5% to the price for that group 

$tier = new GroupTierPricePercent($customerGroupName, $percent);

$product->addData($tier);
```  

#### Quantity tier price

To add quantity tier pricing we can do this

```php
use Lsv\Datapump\Product\Data\QuantityTierPrice;
use Lsv\Datapump\Product\SimpleProduct;

$product = new SimpleProduct();
$product->setPrice(18.99); // Default price

$customerGroupName = 'name of the customer group'; // Default = _all_ see below

$tier = new QuantityTierPrice($customerGroupName);
$tier->addTier(5, 14.99); // Quantity and price
$tier->addTier(10, 12.99);
$tier->addTier(15, 10.99);

$product->addData($tier);
```

The name of the customer group can also be `_all_` this means it will be for all customers and not just for a customer group

#### Quantity tier price percent

```php
use Lsv\Datapump\Product\Data\QuantityTierPricePercent;
use Lsv\Datapump\Product\SimpleProduct;

$product = new SimpleProduct();
$product->setPrice(18.99); // Default price

$customerGroupName = 'name of the customer group'; // Default = _all_ see below

$tier = new QuantityTierPricePercent($customerGroupName);
$tier->addTier(5, -5); // Quantity and percent discount
$tier->addTier(10, -10);
$tier->addTier(15, -15);

$product->addData($tier);
```
