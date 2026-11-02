<?php
declare(strict_types=1);

namespace EnduroTrack\Dao;

use PDO;
use InvalidArgumentException;

abstract class BaseDao
{
    public function __construct(
        protected PDO $connection,
        protected string $table,
        protected array $columns
    ) {
    }

    public function findAll(): array
    {
        $statement = $this->connection->query(
            sprintf('SELECT %s FROM `%s` ORDER BY id DESC', $this->columnList(), $this->table)
        );

        return $statement->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $statement = $this->connection->prepare(
            sprintf('SELECT %s FROM `%s` WHERE id = :id', $this->columnList(), $this->table)
        );
        $statement->execute(['id' => $id]);
        $record = $statement->fetch();

        return $record === false ? null : $record;
    }

    public function create(array $data): int
    {
        $values = $this->validatedData($data);
        if ($values === []) {
            throw new InvalidArgumentException('At least one writable field is required.');
        }

        $fields = array_keys($values);
        $placeholders = array_map(static fn (string $field): string => ':' . $field, $fields);
        $statement = $this->connection->prepare(sprintf(
            'INSERT INTO `%s` (`%s`) VALUES (%s)',
            $this->table,
            implode('`, `', $fields),
            implode(', ', $placeholders)
        ));
        $statement->execute($values);

        return (int) $this->connection->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $values = $this->validatedData($data);
        if ($values === []) {
            throw new InvalidArgumentException('At least one writable field is required.');
        }

        $assignments = array_map(
            static fn (string $field): string => sprintf('`%s` = :%s', $field, $field),
            array_keys($values)
        );
        $values['id'] = $id;
        $statement = $this->connection->prepare(sprintf(
            'UPDATE `%s` SET %s WHERE id = :id',
            $this->table,
            implode(', ', $assignments)
        ));
        $statement->execute($values);

        return $statement->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $statement = $this->connection->prepare(
            sprintf('DELETE FROM `%s` WHERE id = :id', $this->table)
        );
        $statement->execute(['id' => $id]);

        return $statement->rowCount() > 0;
    }

    protected function columnList(): string
    {
        return implode(', ', array_map(
            static fn (string $column): string => sprintf('`%s`', $column),
            array_merge(['id'], $this->columns)
        ));
    }

    private function validatedData(array $data): array
    {
        return array_intersect_key($data, array_flip($this->columns));
    }
}
