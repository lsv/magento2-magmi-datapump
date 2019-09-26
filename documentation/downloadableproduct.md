### Downloadable product

A downloadable product is just the same as a simple product, but with some extra arguments

* `setFile` (required)
* `setSample` (optional)
* `setNumberOfDownloads` (optional, default 0)
* `setSharing` Set allow the file to be shared (optional, default false)

```php
use Lsv\Datapump\Product\DownloadableProduct;
use Symfony\Component\HttpFoundation\File\File;

$file = new File('path/to/file');
$sample = new File('path/to/sample');

$product = (new DownloadableProduct())
    ->setSku('1')
    ->setName('1')
    ->setDescription('1')
    ->setPrice(10)
    ->setQuantity(10)
    ->setFile($file)
    ->setSample($sample)
    ->setNumberOfDownloads(10)
    ->setSharing(true);
```

And now just put the product in the [`itemHolder`](itemholder.md)
