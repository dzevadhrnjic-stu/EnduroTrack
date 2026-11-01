<?php
declare(strict_types=1);

namespace EnduroTrack\Dao;

use PDO;

final class RegistrationDao extends BaseDao
{
    public function __construct(PDO $connection)
    {
        parent::__construct($connection, 'registrations', [
            'competition_id', 'athlete_id',
        ]);
    }
}
