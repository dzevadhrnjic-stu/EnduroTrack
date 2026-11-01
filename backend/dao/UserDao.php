<?php
declare(strict_types=1);

namespace EnduroTrack\Dao;

use PDO;

final class UserDao extends BaseDao
{
    public function __construct(PDO $connection)
    {
        parent::__construct($connection, 'users', [
            'role_id', 'first_name', 'last_name', 'email', 'password_hash',
        ]);
    }
}
