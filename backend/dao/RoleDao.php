<?php
declare(strict_types=1);

namespace EnduroTrack\Dao;

use PDO;

final class RoleDao extends BaseDao
{
    public function __construct(PDO $connection)
    {
        parent::__construct($connection, 'roles', ['name']);
    }
}
