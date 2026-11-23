<?php
declare(strict_types=1);

use EnduroTrack\Dao\AthleteProfileDao;
use EnduroTrack\Dao\CoachRecommendationDao;
use EnduroTrack\Dao\CompetitionDao;
use EnduroTrack\Dao\RegistrationDao;
use EnduroTrack\Dao\RoleDao;
use EnduroTrack\Dao\RouteDao;
use EnduroTrack\Dao\TrainingPlanDao;
use EnduroTrack\Dao\TrainingSessionDao;
use EnduroTrack\Dao\UserDao;
use EnduroTrack\Services\AthleteProfileService;
use EnduroTrack\Services\CoachRecommendationService;
use EnduroTrack\Services\CompetitionService;
use EnduroTrack\Services\RegistrationService;
use EnduroTrack\Services\RoleService;
use EnduroTrack\Services\RouteService;
use EnduroTrack\Services\TrainingPlanService;
use EnduroTrack\Services\TrainingSessionService;
use EnduroTrack\Services\UserService;
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/services/ApiException.php';
require_once __DIR__ . '/services/BaseService.php';
require_once __DIR__ . '/services/EntityServices.php';
require_once __DIR__ . '/services/WeatherService.php';
require_once __DIR__ . '/routes/api.php';

try {
    $pdo = require __DIR__ . '/database/bootstrap.php';
    $config = require __DIR__ . '/config/config.php';
} catch (Throwable $exception) {
    error_log(sprintf('[%s] %s', date('c'), $exception->getMessage()), 3, __DIR__ . '/logs/error.log');
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'Database connection is unavailable.']);
    exit;
}

$services = [
    'roles' => new RoleService(new RoleDao($pdo)),
    'users' => new UserService(new UserDao($pdo)),
    'athlete-profiles' => new AthleteProfileService(new AthleteProfileDao($pdo)),
    'routes' => new RouteService(new RouteDao($pdo)),
    'training-sessions' => new TrainingSessionService(new TrainingSessionDao($pdo)),
    'training-plans' => new TrainingPlanService(new TrainingPlanDao($pdo)),
    'competitions' => new CompetitionService(new CompetitionDao($pdo)),
    'registrations' => new RegistrationService(new RegistrationDao($pdo)),
    'coach-recommendations' => new CoachRecommendationService(new CoachRecommendationDao($pdo)),
];

registerApiRoutes($services, $config);
Flight::start();
