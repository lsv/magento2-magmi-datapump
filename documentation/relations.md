### Relations and cross relations

To create relation between products we can use the

```php
use Lsv\Datapump\Product\Data\ProductCrossRelation;
use Lsv\Datapump\Product\Data\ProductRelation;

$relation = new ProductRelation($products);

// And/or we can use

$relation = new ProductCrossRelation($products);
```

The difference between them are that in the cross relation, also the `$products` in the will also have a reference to the product you add the relation to.

##### Product array

The `$products` needs to be an array of string of SKUs or `ProductInterface`, or they can be mixed.

```php
use Lsv\Datapump\Product\Data\ProductCrossRelation;
use Lsv\Datapump\Product\UpdateProduct;

$relation = new ProductCrossRelation([
    1,
    2,
    'my-sku',
    (new UpdateProduct())->setSku('product-another-sku')]
);
```

### Cross sell and Up sell

These are just like the relations

```php
use Lsv\Datapump\Product\Data\ProductUpsellRelation;
use Lsv\Datapump\Product\Data\ProductCrosssellRelation;

$crosssell = new ProductCrosssellRelation($products);
$upsell = new ProductUpsellRelation($products);
```

##### Limitations

The SKU in the relations needs to be added to the itemHolder BEFORE, otherwise the relation will not happen.
