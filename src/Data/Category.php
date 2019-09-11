<?php

declare(strict_types=1);

namespace Lsv\Datapump\Data;

class Category implements DataInterface
{
    private const DEFAULT_CATEGORY_SEPARATOR = '/';

    /**
     * @var string
     */
    private $categoryName;

    /**
     * @var bool
     */
    private $isActive;

    /**
     * @var bool
     */
    private $isAnchor;

    /**
     * @var bool
     */
    private $includeInMenu;

    /**
     * @var string
     */
    private $levelDelimiter;

    public function __construct(
        string $categoryName,
        $isActive = true,
        $isAnchor = true,
        $includeInMenu = true,
        $levelDelimiter = self::DEFAULT_CATEGORY_SEPARATOR
    ) {
        $this->categoryName = $categoryName;
        $this->isActive = $isActive;
        $this->isAnchor = $isAnchor;
        $this->includeInMenu = $includeInMenu;
        $this->levelDelimiter = $levelDelimiter;
    }

    public function getKey(): string
    {
        return 'categories';
    }

    public function getData(): string
    {
        if (false !== strpos($this->categoryName, $this->levelDelimiter)) {
            $levels = explode($this->levelDelimiter, $this->categoryName);
        } else {
            $levels = [$this->categoryName];
        }

        $levels = array_map(
            static function ($level) {
                if (false !== strpos($level, self::DEFAULT_CATEGORY_SEPARATOR)) {
                    return str_replace(self::DEFAULT_CATEGORY_SEPARATOR, '\\'.self::DEFAULT_CATEGORY_SEPARATOR, $level);
                }

                return $level;
            },
            (array) $levels
        );

        return sprintf(
            '%s::%s::%s::%s',
            implode(self::DEFAULT_CATEGORY_SEPARATOR, $levels),
            (int) $this->isActive,
            (int) $this->isAnchor,
            (int) $this->includeInMenu
        );
    }

    public function allowMultiple(): bool
    {
        return true;
    }

    public function arrayMergeString(): ?string
    {
        return ';;';
    }
}
