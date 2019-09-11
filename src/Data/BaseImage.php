<?php

declare(strict_types=1);

namespace Lsv\Datapump\Data;

use Lsv\Datapump\Configuration;
use Symfony\Component\HttpFoundation\File\File;

class BaseImage extends ImageAbstract
{
    /**
     * @var bool
     */
    private $addToGallery;

    public function __construct(File $file, Configuration $configuration, bool $addToGallery = true, $renameIfFileAlreadyExists = false)
    {
        parent::__construct($file, $configuration, $renameIfFileAlreadyExists);
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
