<?php

declare(strict_types=1);

namespace Lsv\Datapump\Product;

use Lsv\Datapump\Product\Data\DataInterface;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;

interface ProductInterface
{
    public function beforeValidate(): void;

    public function afterValidate(): void;

    public function beforeImport(): void;

    public function afterImport(): void;

    public function getData(): array;

    public function getExtraData(): array;

    public function getMergedData(): array;

    /**
     * @throws NoSuchOptionException
     */
    public function validateProduct(): void;

    public function getDebug(): string;

    public function set(string $key, $value);

    public function has(string $key): bool;

    public function get(string $key);

    public function addData(DataInterface $data);
}
