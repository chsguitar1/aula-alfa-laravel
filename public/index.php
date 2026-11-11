<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
$baseDir = dirname(__DIR__);
if (file_exists($baseDir.'/vendor/autoload.php')) {
    require $baseDir.'/vendor/autoload.php';
} else {
    $fallbackPaths = [
        dirname($baseDir).'/trabalho2/vendor/autoload.php',
        'C:/Users/thiago/Herd/trabalho2/vendor/autoload.php',
        'C:/Users/thiago/Desktop/trabalho2/vendor/autoload.php',
    ];
    foreach ($fallbackPaths as $path) {
        if (file_exists($path)) {
            $loader = require $path;
            if (is_object($loader) && method_exists($loader, 'setPsr4')) {
                $loader->setPsr4('App\\', [$baseDir.'/app']);
                $loader->setPsr4('Database\\Factories\\', [$baseDir.'/database/factories']);
                $loader->setPsr4('Database\\Seeders\\', [$baseDir.'/database/seeders']);
            }
            break;
        }
    }
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
