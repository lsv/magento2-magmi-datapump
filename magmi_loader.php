<?php

declare(strict_types=1);

$files = [
    'lsv/magmi2/magmi/inc/magmi_loggers.php',
    'lsv/magmi2/magmi/engines/magmi_productimportengine.php',
    'lsv/magmi2/magmi/integration/inc/productimport_datapump.php',
    'lsv/magmi2/magmi/integration/inc/magmi_datapump.php',
];

$dirs = [
    __DIR__.'/../../../vendor',
    __DIR__.'/../../vendor',
    __DIR__.'/../vendor',
    __DIR__.'/vendor',
];

function requireFiles(string $dir, array $files)
{
    foreach ($files as $file) {
        require_once $dir.'/'.$file;
    }
}

$isFound = false;
foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        requireFiles($dir, $files);
        $isFound = true;
        break;
    }
}

if (!$isFound) {
    throw new RuntimeException('magmi2 files was not found searched in ('.implode(', ', $dirs).')');
}
