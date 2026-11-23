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

    public function findAll(): array
    {
        $statement = $this->connection->query(
            'SELECT id, role_id, first_name, last_name, email, created_at FROM `users` ORDER BY id DESC'
        );

        return $statement->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $statement = $this->connection->prepare(
            'SELECT id, role_id, first_name, last_name, email, created_at FROM `users` WHERE id = :id'
        );
        $statement->execute(['id' => $id]);
        $record = $statement->fetch();

        return $record === false ? null : $record;
    }
}
