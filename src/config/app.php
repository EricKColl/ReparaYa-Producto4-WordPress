<?php

$httpHost = $_SERVER['HTTP_HOST'] ?? '';

$isLocal =
    strpos($httpHost, 'localhost') !== false ||
    strpos($httpHost, '127.0.0.1') !== false;

return [
    'name' => 'ReparaYa',
    'base_url' => $isLocal ? '/public' : '/~uocx3/Producto2/public'
];