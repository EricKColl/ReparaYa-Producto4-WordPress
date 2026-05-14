<?php

session_start();

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Router.php';

$appConfig = require __DIR__ . '/../config/app.php';

if (!function_exists('base_url')) {
    function base_url(string $path = ''): string
    {
        $config = require __DIR__ . '/../config/app.php';
        $base = rtrim($config['base_url'] ?? '/public', '/');
        $path = ltrim($path, '/');

        if ($path === '') {
            return $base;
        }

        return $base . '/' . $path;
    }
}

if (!function_exists('redirect_to')) {
    function redirect_to(string $path = ''): void
    {
        $config = require __DIR__ . '/../config/app.php';
        $base = rtrim($config['base_url'] ?? '/public', '/');

        if ($path === '') {
            header('Location: ' . $base);
            exit;
        }

        if (strpos($path, '/public') === 0) {
            $path = $base . substr($path, strlen('/public'));
        } elseif (!preg_match('#^https?://#', $path)) {
            $path = $base . '/' . ltrim($path, '/');
        }

        header('Location: ' . $path);
        exit;
    }
}

try {
    Database::connect();

    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $baseUrl = rtrim($appConfig['base_url'] ?? '/public', '/');

    if ($baseUrl !== '/public' && strpos($path, $baseUrl) === 0) {
        $path = '/public' . substr($path, strlen($baseUrl));

        if ($path === '') {
            $path = '/public';
        }
    }

    $router = new Router();
    $router->dispatch($path);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}