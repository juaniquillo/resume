<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use RectorLaravel\Set\LaravelSetProvider;

return RectorConfig::configure()
    ->withPaths([__DIR__ . '/app'])
    // Automatically detects your installed Laravel package versions
    ->withSetProviders([LaravelSetProvider::class])
    ->withComposerBased(laravel: true);