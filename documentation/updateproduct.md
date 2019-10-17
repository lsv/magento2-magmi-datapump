### Update product

Sometimes you just need to update some attributes on a product, then this can be useful.

```php
use Lsv\Datapump\Product\AbstractProduct;use Lsv\Datapump\Product\UpdateProduct;

$product = new UpdateProduct();
$product->setSku('sku');
$product->setType(AbstractProduct::TYPE_SIMPLE);
// Both SKU and product type is the only required elements on a update product.
// And now you can add the only the attributes you want to update

$product->setPrice(100);
```
