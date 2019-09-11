<?php

declare(strict_types=1);

namespace Lsv\Datapump\Data;

use Lsv\Datapump\Configuration;
use Symfony\Component\HttpFoundation\File\File;

class GalleryImage extends ImageAbstract
{
    /**
     * @var string
     */
    private $label;

    public function __construct(File $file, Configuration $configuration, string $label = '', bool $renameIfFileAlreadyExists = false)
    {
        parent::__construct($file, $configuration, $renameIfFileAlreadyExists);
        $this->label = $label;
    }

    public function getData(): string
    {
        if ($this->label) {
            return sprintf('%s::%s', parent::getData(), $this->label);
        }

        return parent::getData();
    }

    public function getKey(): string
    {
        return 'gallery';
    }

    public function allowMultiple(): bool
    {
        return true;
    }

    public function arrayMergeString(): ?string
    {
        return ';';
    }
}
