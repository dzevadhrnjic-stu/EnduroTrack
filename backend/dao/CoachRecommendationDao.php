<?php
declare(strict_types=1);

namespace EnduroTrack\Dao;

use PDO;

final class CoachRecommendationDao extends BaseDao
{
    public function __construct(PDO $connection)
    {
        parent::__construct($connection, 'coach_recommendations', [
            'coach_id', 'athlete_id', 'title', 'content',
        ]);
    }
}
