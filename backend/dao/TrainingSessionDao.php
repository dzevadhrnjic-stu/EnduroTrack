<?php
declare(strict_types=1);

namespace EnduroTrack\Dao;

use PDO;

final class TrainingSessionDao extends BaseDao
{
    public function __construct(PDO $connection)
    {
        parent::__construct($connection, 'training_sessions', [
            'athlete_id', 'route_id', 'discipline', 'session_date', 'distance_km',
            'duration_minutes', 'average_speed', 'notes',
        ]);
    }
}
