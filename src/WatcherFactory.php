<?php

namespace Spatie\PhpUnitWatcher;

use Symfony\Component\Finder\Finder;

class WatcherFactory
{
    public static function create(array $options = []): array
    {
        $options = static::mergeWithDefaultOptions($options);

        $finder = (new Finder())
            ->ignoreDotFiles(false)
            ->ignoreVCS(false)
            ->name($options['watch']['fileMask'])
            ->files()
            ->in($options['watch']['directories']);

        $watcher = new Watcher($finder);

        if (isset($options['phpunitArguments'])) {
            $watcher->usePhpunitArguments($options['phpunitArguments']);
        }

        return [$watcher, $options];
    }

    protected static function mergeWithDefaultOptions(array $options): array
    {
        $options = array_merge([
            'watch' => [
                'directories' => [
                    'src',
                    'tests',
                ],
                'fileMask' => '*.php',
            ],
        ], $options);

        foreach ($options['watch']['directories'] as $index => $directory) {
            $options['watch']['directories'][$index] = getcwd()."/{$directory}";
        }

        return $options;
    }
}
