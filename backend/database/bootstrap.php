<?php
declare(strict_types=1);

use EnduroTrack\Database\Connection;
require_once __DIR__ . '/Connection.php';

$configPath = __DIR__ . '/../config/config.php';
if (!is_file($configPath)) {
    throw new RuntimeException(
        'Missing backend/config/config.php. Copy config.example.php and configure the local database.'
    );
}

$config = require $configPath;
if (!is_array($config) || !isset($config['database'])) {
    throw new RuntimeException('Invalid backend configuration.');
}

return Connection::create($config['database']);
