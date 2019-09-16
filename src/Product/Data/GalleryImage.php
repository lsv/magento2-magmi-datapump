<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product\Data;

use Symfony\Component\HttpFoundation\File\File;

class GalleryImage extends ImageAbstract
{
    /**
     * @var string
     */
    private $label;

    public function __construct(File $file, string $label = '')
    {
        parent::__construct($file);
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
