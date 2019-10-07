<?php

declare(strict_types=1);

namespace Lsv\Datapump;

use Lsv\Datapump\Exceptions\NotSupportedMagmiModeException;
use Lsv\Datapump\Exceptions\ProductAlreadyAddedException;
use Lsv\Datapump\Product\AbstractProduct;
use Lsv\Datapump\Product\ConfigurableProductInterface;
use Magmi_ProductImport_DataPump;
use PDO;
use RuntimeException;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

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

    private const MAGMI_PROFILE = 'default';

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
    private $magmiMode = self::MAGMI_CREATE_UPDATE;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var array
     */
    private $productsAdded = [];

    /**
     * @var PDO|null
     */
    private static $pdo;

    public function __construct(
        Configuration $configuration,
        Logger $logger,
        Magmi_ProductImport_DataPump $magmi,
        OutputInterface $output = null
    ) {
        $this->magmi = $magmi;
        $this->logger = $logger;
        $this->output = $output ?: new NullOutput();
        $this->configuration = $configuration;

        $this->copyMagmiPluginFiles($configuration);
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
        if ($this->findProductBySku($product->getSku(), $product->getStore())) {
            throw new ProductAlreadyAddedException($product->getSku(), $product->getStore());
        }

        $this->products[] = $product;
        $this->productsAdded[$product->getSku()][$product->getStore()] = true;

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
            $counter += $configurableProduct->countProducts();
        }

        return count($this->getProducts()) + $counter;
    }

    public function reset(): void
    {
        $this->productsAdded = [];
        $this->products = [];
    }

    /**
     * @param bool        $dryRun
     * @param string|null $progressBarFormat
     *
     * @return string
     */
    public function import(bool $dryRun = false, ?string $progressBarFormat = null): string
    {
        $this->beforeImport();

        $debug = [];

        // Progress
        $progress = new ProgressBar($this->output);
        if ($progressBarFormat) {
            $progress->setFormat($progressBarFormat);
        }
        $progress->start($this->countProducts());

        if (!$dryRun) {
            $this->magmi->beginImportSession(self::MAGMI_PROFILE, $this->magmiMode, $this->logger);
        }

        foreach ($this->products as $product) {
            if ($product instanceof ConfigurableProductInterface) {
                foreach ($product->getProducts() as $simpleProduct) {
                    $progress->setMessage('Importing: '.$simpleProduct->getSku());
                    $importedLog = $this->importProduct($simpleProduct, $dryRun);
                    $this->logger->log($importedLog, 'debug');
                    $debug[] = $importedLog;

                    $progress->advance();
                }
            }

            $progress->setMessage('Importing: '.$product->getSku());
            $importedLog = $this->importProduct($product, $dryRun);
            $this->logger->log($importedLog, 'debug');
            $debug[] = $importedLog;

            $progress->advance();
        }

        $progress->finish();

        if (!$dryRun) {
            $this->magmi->endImportSession();
        }

        $this->afterImport($debug);

        return implode("\n", $debug);
    }

    public function rawSql(string $statement, array $parameters): bool
    {
        if (!self::$pdo) {
            $dsn = sprintf(
                'mysql:dbname=%s;host=%s',
                $this->configuration->getDatabaseName(),
                $this->configuration->getDatabaseHost()
            );
            self::$pdo = new PDO(
                $dsn,
                $this->configuration->getDatabaseUsername(),
                $this->configuration->getDatabasePassword()
            );
        }

        $stmt = self::$pdo->prepare($statement);

        return $stmt->execute($parameters);
    }

    protected function findProductBySku(?string $sku, ?string $store): bool
    {
        return isset($this->productsAdded[$sku][$store]);
    }

    protected function importProduct(AbstractProduct $product, bool $dryRun): string
    {
        $product->beforeImport();
        if (!$dryRun) {
            $this->magmi->ingest($product->getMergedData());
        }

        $output = $product->getDebug();
        $product->afterImport();

        return $output;
    }

    public function getMagmiDir(): string
    {
        $fs = new Filesystem();
        $dirs = [
            __DIR__.'/../vendor',
            __DIR__.'/../../../vendor',
            __DIR__.'/../../../../vendor',
        ];

        foreach ($dirs as $dir) {
            if ($fs->exists($dir.'/macopedia/magmi2/modman')) {
                return $dir.'/macopedia/magmi2/magmi/conf';
            }
        }

        // @codeCoverageIgnoreStart
        throw new RuntimeException('Could not find magmi directory');
        // @codeCoverageIgnoreEnd
    }

    private function copyMagmiPluginFiles(Configuration $configuration): void
    {
        $magmiDir = $this->getMagmiDir();

        $fs = new Filesystem();
        if ($files = glob(__DIR__.'/../magmifiles/*')) {
            foreach ($files as $file) {
                if ('plugins.conf' !== basename($file) && $fs->exists($magmiDir.'/'.basename($file))) {
                    break;
                }

                $fs->copy($file, $magmiDir.'/'.basename($file), true);
                if ('magmi.ini' === basename($file)) {
                    $replace = [
                        '<<DB_NAME>>' => $configuration->getDatabaseName(),
                        '<<DB_HOST>>' => $configuration->getDatabaseHost(),
                        '<<DB_USERNAME>>' => $configuration->getDatabaseUsername(),
                        '<<DB_PASSWORD>>' => $configuration->getDatabasePassword(),
                        '<<MAGENTO_BASEDIR>>' => $configuration->getMagentoDirectory(),
                    ];

                    if ($content = file_get_contents($magmiDir.'/'.basename($file))) {
                        file_put_contents(
                            $magmiDir.'/'.basename($file),
                            str_replace(array_keys($replace), array_values($replace), $content)
                        );
                    }
                }
            }
        }
    }
}
