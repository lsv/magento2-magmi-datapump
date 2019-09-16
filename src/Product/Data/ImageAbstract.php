<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product\Data;

use Symfony\Component\HttpFoundation\File\File;

abstract class ImageAbstract implements DataInterface
{
    /**
     * @var File
     */
    protected $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function allowMultiple(): bool
    {
        return false;
    }

    public function getData(): string
    {
        return $this->file->getPathname();
    }

    public function arrayMergeString(): ?string
    {
        return null;
    }
}
