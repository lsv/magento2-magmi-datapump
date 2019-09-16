### How to add images to a product

We can also add images to a product.

```php
use Lsv\Datapump\Product\ConfigurableProduct;
use Lsv\Datapump\Product\Data\BaseImage;
use Symfony\Component\HttpFoundation\File\File;

// First we need a product, can be any product types
$product = new ConfigurableProduct(['color', 'size']);

// Then we need a file
$file = new File('path/to/your/image.png');
$baseImage = new BaseImage(
    $file, // Our file
    true // Do we want to add the image to the gallery? (default true)
);

// Now we just add the image to our product
$product->addData($baseImage);
```

#### Adding small image and thumbnail

```php
use Lsv\Datapump\Product\ConfigurableProduct;
use Lsv\Datapump\Product\Data\SmallImage;
use Symfony\Component\HttpFoundation\File\File;

$product = new ConfigurableProduct(['color', 'size']);
$file = new File('path/to/your/image.png');

$smallImage = new SmallImage($file);
// No other configuration can be done

// Now we just add the image to our product
$product->addData($smallImage);
```

The thumbnail is the same, we just need to change the `new SmallImage` to `new ThumbnailImage`

#### Adding labels to images

Due to some restrictions in the API we need to add a new object

```php
use Lsv\Datapump\Product\ConfigurableProduct;
use Lsv\Datapump\Product\Data\SmallImageLabel;

$product = new ConfigurableProduct(['color', 'size']);
$label = new SmallImageLabel('image label');
$product->addData($label);
```

To add labels to base image and thumbnail image, just change the `new SmallImageLabel` to `new BaseImageLabel` or `new ThumbnailImageLabel`

#### Gallery

We can also create a gallery of images

```php
use Lsv\Datapump\Product\Data\GalleryImage;
use Lsv\Datapump\Product\SimpleProduct;
use Symfony\Component\HttpFoundation\File\File;

$product = new SimpleProduct();
$file = new File('path/to/your/image.png');

$galleryImage = new GalleryImage(
    $file, // Our file
    'image label' // Label for the image, can be omitted
);

// Now we just add the image to our product
$product->addData($galleryImage);

// And we can add more images to the gallery
$file = new File('path/to/another/image.png');
$galleryImage2 = new GalleryImage(
    $file, // Our file
    'label to another image' // Label for the image, can be omitted
);
$product->addData($galleryImage2);
```
