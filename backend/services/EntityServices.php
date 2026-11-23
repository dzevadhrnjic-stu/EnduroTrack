<?php
declare(strict_types=1);

namespace EnduroTrack\Services;

use EnduroTrack\Dao\BaseDao;

final class RoleService extends BaseService
{
    public function __construct(BaseDao $dao)
    {
        parent::__construct($dao, ['name'], ['name' => ['type' => 'string']]);
    }
}

final class UserService extends BaseService
{
    public function __construct(BaseDao $dao)
    {
        parent::__construct($dao, ['role_id', 'first_name', 'last_name', 'email', 'password'], [
            'role_id' => ['type' => 'positive_int'], 'first_name' => ['type' => 'string'],
            'last_name' => ['type' => 'string'], 'email' => ['type' => 'email'],
            'password' => ['type' => 'password'],
        ]);
    }

    protected function prepareInput(array $data): array
    {
        if (isset($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            unset($data['password']);
        }

        return $data;
    }
}

final class AthleteProfileService extends BaseService
{
    public function __construct(BaseDao $dao)
    {
        parent::__construct($dao, ['user_id', 'discipline', 'experience_level'], [
            'user_id' => ['type' => 'positive_int'], 'discipline' => ['type' => 'enum', 'values' => ['running', 'cycling', 'both']],
            'experience_level' => ['type' => 'string'], 'season_goal' => ['type' => 'string', 'nullable' => true],
        ]);
    }
}

final class RouteService extends BaseService
{
    public function __construct(BaseDao $dao)
    {
        parent::__construct($dao, ['created_by', 'name', 'discipline', 'distance_km', 'city'], [
            'created_by' => ['type' => 'positive_int'], 'name' => ['type' => 'string'],
            'discipline' => ['type' => 'enum', 'values' => ['running', 'cycling']],
            'distance_km' => ['type' => 'positive_number'], 'elevation_gain_m' => ['type' => 'nonnegative_number'],
            'city' => ['type' => 'string'], 'latitude' => ['type' => 'number', 'nullable' => true],
            'longitude' => ['type' => 'number', 'nullable' => true],
        ]);
    }
}

final class TrainingSessionService extends BaseService
{
    public function __construct(BaseDao $dao)
    {
        parent::__construct($dao, ['athlete_id', 'discipline', 'session_date', 'distance_km', 'duration_minutes'], [
            'athlete_id' => ['type' => 'positive_int'], 'route_id' => ['type' => 'positive_int', 'nullable' => true],
            'discipline' => ['type' => 'enum', 'values' => ['running', 'cycling']], 'session_date' => ['type' => 'date'],
            'distance_km' => ['type' => 'positive_number'], 'duration_minutes' => ['type' => 'positive_int'],
            'average_speed' => ['type' => 'nonnegative_number', 'nullable' => true], 'notes' => ['type' => 'string', 'nullable' => true],
        ]);
    }
}

final class TrainingPlanService extends BaseService
{
    public function __construct(BaseDao $dao)
    {
        parent::__construct($dao, ['coach_id', 'athlete_id', 'title', 'planned_date'], [
            'coach_id' => ['type' => 'positive_int'], 'athlete_id' => ['type' => 'positive_int'],
            'route_id' => ['type' => 'positive_int', 'nullable' => true], 'title' => ['type' => 'string'],
            'planned_date' => ['type' => 'date'], 'target_distance_km' => ['type' => 'positive_number', 'nullable' => true],
            'status' => ['type' => 'enum', 'values' => ['planned', 'completed', 'skipped']],
        ]);
    }
}

final class CompetitionService extends BaseService
{
    public function __construct(BaseDao $dao)
    {
        parent::__construct($dao, ['created_by', 'name', 'discipline', 'event_date', 'location', 'distance_km', 'registration_deadline'], [
            'created_by' => ['type' => 'positive_int'], 'name' => ['type' => 'string'],
            'discipline' => ['type' => 'enum', 'values' => ['running', 'cycling']], 'event_date' => ['type' => 'date'],
            'location' => ['type' => 'string'], 'distance_km' => ['type' => 'positive_number'],
            'registration_deadline' => ['type' => 'date'],
        ]);
    }
}

final class RegistrationService extends BaseService
{
    public function __construct(BaseDao $dao)
    {
        parent::__construct($dao, ['competition_id', 'athlete_id'], [
            'competition_id' => ['type' => 'positive_int'], 'athlete_id' => ['type' => 'positive_int'],
        ]);
    }
}

final class CoachRecommendationService extends BaseService
{
    public function __construct(BaseDao $dao)
    {
        parent::__construct($dao, ['coach_id', 'athlete_id', 'title', 'content'], [
            'coach_id' => ['type' => 'positive_int'], 'athlete_id' => ['type' => 'positive_int'],
            'title' => ['type' => 'string'], 'content' => ['type' => 'string'],
        ]);
    }
}
