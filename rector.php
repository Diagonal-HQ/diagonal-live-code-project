<?php

use Rector\Config\RectorConfig;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
use Utils\Rector\Rector\ConvertTestMethodPrefixToAttributeRector;

return RectorConfig::configure()
    ->withPhpSets()
    ->withAttributesSets()
    ->withImportNames(
        removeUnusedImports: true
    )
    ->withRules([
        ConvertTestMethodPrefixToAttributeRector::class,
    ])
    ->withSkip([
        ClosureToArrowFunctionRector::class,
    ])
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/database',
        __DIR__.'/config',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ]);
