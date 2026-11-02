<?php
declare(strict_types=1);

namespace EnduroTrack\Database;

use PDO;
use RuntimeException;

final class Connection
{
    public static function create(array $config): PDO
    {
        if (!isset($config['host'], $config['port'], $config['name'], $config['user'])) {
            throw new RuntimeException('Database configuration is incomplete.');
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $config['host'],
            (int) $config['port'],
            $config['name']
        );

        return new PDO($dsn, $config['user'], $config['password'] ?? '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
}
