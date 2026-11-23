<?php
declare(strict_types=1);

namespace EnduroTrack\Services;

use EnduroTrack\Dao\BaseDao;

abstract class BaseService
{
    public function __construct(
        protected BaseDao $dao,
        private array $requiredFields = [],
        private array $rules = []
    ) {
    }

    public function list(): array
    {
        return $this->dao->findAll();
    }

    public function get(int $id): array
    {
        $this->ensurePositiveId($id);
        $record = $this->dao->findById($id);
        if ($record === null) {
            throw new ApiException('Resource not found.', 404);
        }

        return $record;
    }

    public function create(array $input): array
    {
        $data = $this->validate($input, true);
        $data = $this->prepareInput($data);

        try {
            return $this->get($this->dao->create($data));
        } catch (\PDOException $exception) {
            throw $this->createDatabaseException($exception, 'create');
        }
    }

    public function update(int $id, array $input): array
    {
        $this->ensurePositiveId($id);
        $this->get($id);
        $data = $this->validate($input, false);
        $data = $this->prepareInput($data);

        try {
            $this->dao->update($id, $data);
            return $this->get($id);
        } catch (\PDOException $exception) {
            throw $this->createDatabaseException($exception, 'update');
        }
    }

    public function delete(int $id): void
    {
        $this->ensurePositiveId($id);
        $this->get($id);

        try {
            $this->dao->delete($id);
        } catch (\PDOException $exception) {
            throw $this->createDatabaseException($exception, 'delete');
        }
    }

    protected function prepareInput(array $data): array
    {
        return $data;
    }

    private function validate(array $input, bool $isCreate): array
    {
        $data = array_intersect_key($input, array_flip(array_keys($this->rules)));
        if ($isCreate) {
            foreach ($this->requiredFields as $field) {
                if (!array_key_exists($field, $data) || $data[$field] === '') {
                    throw new ApiException(sprintf('Field "%s" is required.', $field));
                }
            }
        } elseif ($data === []) {
            throw new ApiException('At least one writable field is required.');
        }

        foreach ($data as $field => $value) {
            $rule = $this->rules[$field];
            if ($value === null && ($rule['nullable'] ?? false)) {
                continue;
            }
            if (($rule['type'] ?? null) === 'string' && (!is_string($value) || trim($value) === '')) {
                throw new ApiException(sprintf('Field "%s" must be a non-empty string.', $field));
            }
            if (($rule['type'] ?? null) === 'password' && (!is_string($value) || strlen($value) < 8)) {
                throw new ApiException(sprintf('Field "%s" must be at least 8 characters.', $field));
            }
            if (($rule['type'] ?? null) === 'email' && (!is_string($value) || !filter_var($value, FILTER_VALIDATE_EMAIL))) {
                throw new ApiException(sprintf('Field "%s" must be a valid email.', $field));
            }
            if (($rule['type'] ?? null) === 'number' && !is_numeric($value)) {
                throw new ApiException(sprintf('Field "%s" must be numeric.', $field));
            }
            if (($rule['type'] ?? null) === 'positive_int' && ((!is_int($value) && !ctype_digit((string) $value)) || (int) $value < 1)) {
                throw new ApiException(sprintf('Field "%s" must be a positive integer.', $field));
            }
            if (($rule['type'] ?? null) === 'positive_number' && (!is_numeric($value) || (float) $value <= 0)) {
                throw new ApiException(sprintf('Field "%s" must be greater than zero.', $field));
            }
            if (($rule['type'] ?? null) === 'nonnegative_number' && (!is_numeric($value) || (float) $value < 0)) {
                throw new ApiException(sprintf('Field "%s" must not be negative.', $field));
            }
            if (($rule['type'] ?? null) === 'enum' && !in_array($value, $rule['values'], true)) {
                throw new ApiException(sprintf('Field "%s" has an invalid value.', $field));
            }
            if (($rule['type'] ?? null) === 'date' && (!is_string($value) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $value))) {
                throw new ApiException(sprintf('Field "%s" must use YYYY-MM-DD.', $field));
            }
        }

        return $data;
    }

    private function ensurePositiveId(int $id): void
    {
        if ($id < 1) {
            throw new ApiException('ID must be a positive integer.');
        }
    }

    private function createDatabaseException(\PDOException $exception, string $operation): ApiException
    {
        $driverCode = (int) ($exception->errorInfo[1] ?? 0);

        if ($driverCode === 1062) {
            return new ApiException('A resource with these unique values already exists.', 400, $exception);
        }

        if ($driverCode === 1451) {
            return new ApiException('The resource is referenced by other records and cannot be deleted.', 400, $exception);
        }

        if ($driverCode === 1452) {
            return new ApiException('One or more referenced records do not exist.', 400, $exception);
        }

        return new ApiException(sprintf('Unable to %s resource.', $operation), 400, $exception);
    }
}
