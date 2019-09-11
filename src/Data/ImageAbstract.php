<?php

declare(strict_types=1);

namespace Lsv\Datapump\Data;

use Lsv\Datapump\Configuration;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

abstract class ImageAbstract implements DataInterface
{
    /**
     * @var File
     */
    protected $file;

    /**
     * @var bool
     */
    private $renameIfFileAlreadyExists;

    /**
     * @var string
     */
    private $magentoDirectory;

    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(File $file, Configuration $configuration, bool $renameIfFileAlreadyExists = false)
    {
        $this->file = $file;
        $this->renameIfFileAlreadyExists = $renameIfFileAlreadyExists;
        $this->configuration = $configuration;
    }

    public function allowMultiple(): bool
    {
        return false;
    }

    public function getData(): string
    {
        return $this->copyImageToMagento();
    }

    protected function copyImageToMagento(): string
    {
        $fs = new Filesystem();
        $dir1 = substr($this->file->getFilename(), 0, 1);
        $dir2 = substr($this->file->getFilename(), 1, 1);
        $filepath = sprintf('%s/%s/%s/%s', $this->configuration->getMagentoDirectory(), $dir1, $dir2, $this->file->getFilename());

        $name = $this->file->getFilename();
        if ($this->renameIfFileAlreadyExists && $fs->exists($filepath)) {
            $name = $this->randomName().'.'.$this->file->guessExtension();
            $dir1 = substr($name, 0, 1);
            $dir2 = substr($name, 1, 1);
            $filepath = sprintf('%s/%s/%s/%s', $this->configuration->getMagentoDirectory(), $dir1, $dir2, $name);
        }

        $fs->copy($this->file->getPathname(), $filepath);

        return sprintf('%s/%s/%s', $dir1, $dir2, $name);
    }

    private function randomName($length = 8): string
    {
        $i = 0; //Reset the counter.
        $possible_keys = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $keys_length = strlen($possible_keys);
        $str = ''; //Let's declare the string, to add later.
        while ($i < $length) {
            $rand = random_int(1, $keys_length - 1);
            $str .= $possible_keys[$rand];
            ++$i;
        }

        return $str;
    }

    public function arrayMergeString(): ?string
    {
        return null;
    }
}
