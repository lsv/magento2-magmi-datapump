<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product\Data;

use Symfony\Component\HttpFoundation\File\File;

class BaseImage extends ImageAbstract
{
    /**
     * @var bool
     */
    private $addToGallery;

    public function __construct(File $file, bool $addToGallery = true)
    {
        parent::__construct($file);
        $this->addToGallery = $addToGallery;
    }

    public function getKey(): string
    {
        return 'image';
    }

    public function getData(): string
    {
        return sprintf('%s%s', $this->addToGallery ? '+' : '-', parent::getData());
    }
}
