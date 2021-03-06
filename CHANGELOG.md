### v3.0

* Adding possibility to add magmi indexer, is now turned off by default (breaking change)
* Adding magmi with a new pack, so its not needed to add the extra composer repository anymore
* Removed composer.lock
* Removed deprecations from v2.0.4

### v2.0.5

* Allow multiple values in AbstractProduct::set(..., array)
* New product type - [Downloadable product](documentation/downloadableproduct.md)
* Added [Cross sell and Up sell are now possible]((documentation/relations.md))
* Added [Group tier pricing](documentation/tierpricing.md)
* Added [Quantity tier pricing](documentation/tierpricing.md)

### v2.0.4

* Deprecated `ConfigurableProduct::addSimpleProduct` use `ConfigurableProduct::addProduct` instead
* Deprecated `ConfigurableProduct::getSimpleProducts` use `ConfigurableProduct::getProducts` instead
* Deprecated `ConfigurableProduct::setSimpleProducts` use `ConfigurableProduct::setProducts` instead
* Deprecated `ConfigurableProduct::countSimpleProducts` use `ConfigurableProduct::countProducts` instead
* Allow `ConfigurableProduct::addProduct` use use other product type than just `SimpleProduct`

### v2.0.3

* Added [delete product](documentation/deleteproduct.md)

### v2.0.2

* Add [Product relations](documentation/relations.md)

### v2.0.1

* Allow set simple skus just with sku and not a whole product

### v2.0

* Removed manual copying of images, so magmi will copy the files correct
