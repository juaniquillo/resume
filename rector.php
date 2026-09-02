<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use RectorLaravel\Set\LaravelLevelSetList;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/config',
        __DIR__.'/database',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ])
    ->withSkip([
        __DIR__.'/bootstrap/cache',
        __DIR__.'/storage',
    ])
    // These still resolve perfectly using driftingly/rector-laravel
    ->withComposerBased(laravel: true)
    ->withSets([
        LaravelLevelSetList::UP_TO_LARAVEL_110, // Set your target version
        LaravelSetList::LARAVEL_CODE_QUALITY,
    ]);
