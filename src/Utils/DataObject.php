<?php

declare(strict_types=1);

namespace Lsv\Datapump\Utils;

class DataObject
{
    private $data = [];

    public function __construct(array $initial = [])
    {
        $this->setData($initial);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        if ($this->has($key)) {
            return $this->data[$key];
        }

        return null;
    }

    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }
}
