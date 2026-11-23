<?php
declare(strict_types=1);

use EnduroTrack\Services\ApiException;
use EnduroTrack\Services\WeatherService;

function registerApiRoutes(array $services, array $config): void
{
    $jsonBody = static function (): array {
        $body = Flight::request()->getBody();
        $data = json_decode($body, true);
        if ($body === '' || !is_array($data)) {
            throw new ApiException('Request body must be a JSON object.');
        }

        return $data;
    };

    $respondError = static function (Throwable $exception): void {
        $status = $exception instanceof ApiException ? $exception->statusCode() : 500;
        if (!$exception instanceof ApiException) {
            error_log(sprintf('[%s] %s', date('c'), $exception->getMessage()), 3, __DIR__ . '/../logs/error.log');
        }
        Flight::json(['error' => $status === 500 ? 'Internal server error.' : $exception->getMessage()], $status);
    };

    Flight::route('GET /api', static function (): void {
        Flight::json(['name' => 'EnduroTrack API', 'version' => '0.2.0']);
    });

    Flight::route('GET /api/weather', static function () use ($config, $respondError): void {
        try {
            $query = Flight::request()->query;
            $latitude = $query->latitude;
            $longitude = $query->longitude;
            $date = $query->date;
            if (!is_numeric($latitude) || !is_numeric($longitude) || !is_string($date)) {
                throw new ApiException('latitude, longitude and date query parameters are required.');
            }
            $weather = new WeatherService($config['open_meteo_base_url']);
            Flight::json(['data' => $weather->forecast((float) $latitude, (float) $longitude, $date)]);
        } catch (Throwable $exception) {
            $respondError($exception);
        }
    });

    Flight::route('GET /api/@resource', static function (string $resource) use ($services, $respondError): void {
        try {
            if (!isset($services[$resource])) {
                throw new ApiException('Resource not found.', 404);
            }
            Flight::json(['data' => $services[$resource]->list()]);
        } catch (Throwable $exception) {
            $respondError($exception);
        }
    });

    Flight::route('GET /api/@resource/@id:[0-9]+', static function (string $resource, string $id) use ($services, $respondError): void {
        try {
            if (!isset($services[$resource])) {
                throw new ApiException('Resource not found.', 404);
            }
            Flight::json(['data' => $services[$resource]->get((int) $id)]);
        } catch (Throwable $exception) {
            $respondError($exception);
        }
    });

    foreach (['POST' => 'create', 'PUT' => 'update', 'PATCH' => 'update'] as $method => $operation) {
        Flight::route($method . ' /api/@resource' . ($operation === 'update' ? '/@id:[0-9]+' : ''), static function (string $resource, ?string $id = null) use ($services, $operation, $jsonBody, $respondError): void {
            try {
                if (!isset($services[$resource])) {
                    throw new ApiException('Resource not found.', 404);
                }
                $result = $operation === 'create'
                    ? $services[$resource]->create($jsonBody())
                    : $services[$resource]->update((int) $id, $jsonBody());
                Flight::json(['data' => $result], $operation === 'create' ? 201 : 200);
            } catch (Throwable $exception) {
                $respondError($exception);
            }
        });
    }

    Flight::route('DELETE /api/@resource/@id:[0-9]+', static function (string $resource, string $id) use ($services, $respondError): void {
        try {
            if (!isset($services[$resource])) {
                throw new ApiException('Resource not found.', 404);
            }
            $services[$resource]->delete((int) $id);
            Flight::json(['message' => 'Resource deleted.']);
        } catch (Throwable $exception) {
            $respondError($exception);
        }
    });
}
