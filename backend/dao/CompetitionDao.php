<?php
declare(strict_types=1);

namespace EnduroTrack\Dao;

use PDO;

final class CompetitionDao extends BaseDao
{
    public function __construct(PDO $connection)
    {
        parent::__construct($connection, 'competitions', [
            'created_by', 'name', 'discipline', 'event_date', 'location',
            'distance_km', 'registration_deadline',
        ]);
    }
}
