<?php

declare(strict_types=1);

$packageAutoload = __DIR__.'/../vendor/autoload.php';
require is_file($packageAutoload) ? $packageAutoload : __DIR__.'/../../../../vendor/autoload.php';

// Test this checkout rather than the broker's installed SDK version.
spl_autoload_register(static function (string $class): void {
    $prefix = 'MmtRiskSdk\\';
    if (! str_starts_with($class, $prefix)) {
        return;
    }

    $path = __DIR__.'/../src/'.str_replace('\\', '/', substr($class, strlen($prefix))).'.php';
    if (is_file($path)) {
        require $path;
    }
}, true, true);
