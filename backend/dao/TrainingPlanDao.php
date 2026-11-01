<?php
declare(strict_types=1);

namespace EnduroTrack\Dao;

use PDO;

final class TrainingPlanDao extends BaseDao
{
    public function __construct(PDO $connection)
    {
        parent::__construct($connection, 'training_plans', [
            'coach_id', 'athlete_id', 'route_id', 'title', 'planned_date',
            'target_distance_km', 'status',
        ]);
    }
}
