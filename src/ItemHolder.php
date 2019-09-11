<?php

declare(strict_types=1);

namespace Lsv\Datapump;

use Lsv\Datapump\Exceptions\NotSupportedMagmiModeException;
use Lsv\Datapump\Exceptions\ProductAlreadyAddedException;
use Lsv\Datapump\Product\AbstractProduct;
use Lsv\Datapump\Product\ConfigurableProductInterface;
use Magmi_ProductImport_DataPump;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class ItemHolder
{
    /**
     * Creates and update.
     */
    public const MAGMI_CREATE_UPDATE = 'create';

    /**
     * Update only.
     */
    public const MAGMI_UPDATE = 'update';

    /**
     * Create only.
     */
    public const MAGMI_CREATE = 'xcreate';

    /**
     * @var AbstractProduct[]
     */
    private $products = [];

    /**
     * @var Magmi_ProductImport_DataPump
     */
    private $magmi;

    /**
     * @var string
     */
    private $magmiProfile;

    /**
     * @var string
     */
    private $magmiMode = self::MAGMI_CREATE_UPDATE;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct(
        Logger $logger,
        Magmi_ProductImport_DataPump $magmi,
        string $magmiProfile,
        OutputInterface $output = null
    ) {
        $this->magmi = $magmi;
        $this->magmiProfile = $magmiProfile;
        $this->logger = $logger;
        $this->output = $output ?: new NullOutput();
    }

    public function getMagmiMode(): string
    {
        return $this->magmiMode;
    }

    /**
     * @param string $mode
     *
     * @throws NotSupportedMagmiModeException
     */
    public function setMagmiMode(string $mode = self::MAGMI_CREATE_UPDATE): void
    {
        $modes = [self::MAGMI_CREATE_UPDATE, self::MAGMI_CREATE, self::MAGMI_UPDATE];
        if (!in_array($mode, $modes, true)) {
            throw new NotSupportedMagmiModeException($mode, $modes);
        }

        $this->magmiMode = $mode;
    }

    public function beforeImport(): void
    {
    }

    public function afterImport(array $debug): void
    {
    }

    /**
     * @param AbstractProduct $product
     *
     * @return self
     *
     * @throws ProductAlreadyAddedException
     */
    public function addProduct(AbstractProduct $product): self
    {
        $product->validateProduct();

        // Is the SKU already added?
        if ($this->findProductBySku($product->getSku())) {
            throw new ProductAlreadyAddedException($product->getSku());
        }

        $this->products[] = $product;

        return $this;
    }

    /**
     * @return AbstractProduct[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param AbstractProduct[] $products
     *
     * @return self
     *
     * @throws ProductAlreadyAddedException
     */
    public function setProducts(array $products): self
    {
        $this->reset();
        foreach ($products as $product) {
            $this->addProduct($product);
        }

        return $this;
    }

    public function countProducts(): int
    {
        $counter = 0;
        $configurableProducts = array_filter(
            $this->getProducts(),
            static function (AbstractProduct $product) {
                return $product instanceof ConfigurableProductInterface;
            }
        );

        /** @var ConfigurableProductInterface $configurableProduct */
        foreach ($configurableProducts as $configurableProduct) {
            $counter += $configurableProduct->countSimpleProducts();
        }

        return count($this->getProducts()) + $counter;
    }

    public function reset(): void
    {
        $this->products = [];
    }

    /**
     * @param bool $dryRun
     *
     * @return string
     */
    public function import(bool $dryRun = false): string
    {
        $this->beforeImport();

        $debug = [];

        // Progress
        $progress = new ProgressBar($this->output);
        $progress->start($this->countProducts());

        if (!$dryRun) {
            $this->magmi->beginImportSession($this->magmiProfile, $this->magmiMode, $this->logger);
        }

        foreach ($this->products as $product) {
            if ($product instanceof ConfigurableProductInterface) {
                foreach ($product->getSimpleProducts() as $simpleProduct) {
                    $debug[] = $this->importProduct($simpleProduct, $dryRun);
                    $progress->advance();
                }
            }

            $debug[] = $this->importProduct($product, $dryRun);
            $progress->advance();
        }

        $progress->finish();

        if (!$dryRun) {
            $this->magmi->endImportSession();
        }

        foreach ($debug as $item) {
            $this->logger->log($item, 'debug');
        }

        $this->afterImport($debug);

        return implode("\n", $debug);
    }

    protected function findProductBySku(?string $sku): bool
    {
        foreach ($this->products as $product) {
            if ($product->getSku() === $sku) {
                return true;
            }
        }

        return false;
    }

    protected function importProduct(AbstractProduct $product, bool $dryRun): string
    {
        $output = $product->getDebug();
        $product->beforeImport();
        if (!$dryRun) {
            $this->magmi->ingest($product->getMergedData());
        }
        $product->afterImport();

        return $output;
    }
}
