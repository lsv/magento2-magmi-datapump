### How to add images to a product

We can also add images to a product.

```php
use Lsv\Datapump\Configuration;
use Lsv\Datapump\Product\ConfigurableProduct;
use Lsv\Datapump\Product\Data\BaseImage;
use Symfony\Component\HttpFoundation\File\File;

// First we need a product, can be any product types
$product = new ConfigurableProduct(['color', 'size']);

// Then we need a configuration so we know where the root magento directory is, so we can copy the files to the media folder
$configuration = new Configuration('path/to/your/root/magento/directory');

// Then we need a file
$file = new File('path/to/your/image.png');
$baseImage = new BaseImage(
    $file, // Our file
    $configuration, // Our configuration
    true, // Do we want to add the image to the gallery? (default true)
    false // Do we want to rename the file to a random name if the file already exists (default false)
);

// Now we just add the image to our product
$product->addData($baseImage);
```

#### Adding small image and thumbnail

```php
use Lsv\Datapump\Configuration;
use Lsv\Datapump\Product\ConfigurableProduct;
use Lsv\Datapump\Product\Data\SmallImage;
use Symfony\Component\HttpFoundation\File\File;

$product = new ConfigurableProduct(['color', 'size']);
$configuration = new Configuration('path/to/your/root/magento/directory');
$file = new File('path/to/your/image.png');

$smallImage = new SmallImage(
    $file,
    $configuration
);
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
use Lsv\Datapump\Configuration;
use Lsv\Datapump\Product\Data\GalleryImage;
use Lsv\Datapump\Product\SimpleProduct;
use Symfony\Component\HttpFoundation\File\File;

$product = new SimpleProduct();
$configuration = new Configuration('path/to/your/root/magento/directory');
$file = new File('path/to/your/image.png');

$galleryImage = new GalleryImage(
    $file, // Our file
    $configuration, // Our configuration
    'image label'
);

// Now we just add the image to our product
$product->addData($galleryImage);

// And we can add more images to the gallery
$file = new File('path/to/another/image.png');
$galleryImage2 = new GalleryImage(
    $file, // Our file
    $configuration, // Our configuration
    'label to another image'
);
$product->addData($galleryImage2);
```
