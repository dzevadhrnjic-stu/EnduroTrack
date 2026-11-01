<?php
declare(strict_types=1);

namespace EnduroTrack\Dao;

use PDO;

final class RouteDao extends BaseDao
{
    public function __construct(PDO $connection)
    {
        parent::__construct($connection, 'routes', [
            'created_by', 'name', 'discipline', 'distance_km', 'elevation_gain_m',
            'city', 'latitude', 'longitude',
        ]);
    }
}
