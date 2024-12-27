<?php

namespace App\Providers;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;


class ApiRouteInjector
{
    public static function injectRoutes($apiRoutePath) {
        $apiRouteFiles = self::getApiRouteFiles($apiRoutePath);

        foreach ($apiRouteFiles as $routeFile) {
            require($routeFile);
        }
    }

    private static function getApiRouteFiles($path) {
        $routeFiles = [];

        if (is_dir($path)) {
            $directory = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
            $iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::LEAVES_ONLY);

            foreach ($iterator as $fileInfo) {
                if ($fileInfo->isFile() && $fileInfo->getExtension() === 'php') {
                    $routeFiles[] = $fileInfo->getPathname();
                }
            }
        }

        return $routeFiles;
    }
}
