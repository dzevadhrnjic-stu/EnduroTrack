<?php
declare(strict_types=1);

namespace EnduroTrack\Dao;

use PDO;

final class AthleteProfileDao extends BaseDao
{
    public function __construct(PDO $connection)
    {
        parent::__construct($connection, 'athlete_profiles', [
            'user_id', 'discipline', 'experience_level', 'season_goal',
        ]);
    }
}
