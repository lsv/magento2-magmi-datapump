### Delete product

To delete a product, we must have a product before hand.

We can use the `UpdateProduct` type

```php
use Lsv\Datapump\Product\AbstractProduct;
use Lsv\Datapump\Product\Data\DeleteProduct;
use Lsv\Datapump\Product\UpdateProduct;

$product = new UpdateProduct();
$product->setSku('sku-of-product-that-needs-to-be-deleted');
$product->setType(AbstractProduct::TYPE_SIMPLE); // The type of the product is needed
$product->addData(new DeleteProduct());
```

Now just inject the `$product` to the itemholder
