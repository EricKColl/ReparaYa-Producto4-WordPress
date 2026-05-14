<?php

$httpHost = $_SERVER['HTTP_HOST'] ?? '';

$isLocal =
    strpos($httpHost, 'localhost') !== false ||
    strpos($httpHost, '127.0.0.1') !== false;

if ($isLocal) {
    return [
        'host' => 'db',
        'dbname' => 'reparaya',
        'username' => 'root',
        'password' => 'rootpass',
        'charset' => 'utf8mb4'
    ];
}

return [
    'host' => 'localhost',
    'dbname' => 'wordpress3',
    'username' => 'wordpress3',
    'password' => 'ZIx1NteeY5NB2DW1',
    'charset' => 'utf8mb4'
];