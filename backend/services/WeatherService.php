<?php
declare(strict_types=1);

namespace EnduroTrack\Services;

use RuntimeException;

final class WeatherService
{
    public function __construct(
        private string $baseUrl,
        private int $timeoutSeconds = 5
    ) {
    }

    public function forecast(float $latitude, float $longitude, string $date): array
    {
        if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
            throw new ApiException('Coordinates are outside the valid range.');
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            throw new ApiException('Date must use YYYY-MM-DD.');
        }

        $query = http_build_query([
            'latitude' => $latitude,
            'longitude' => $longitude,
            'start_date' => $date,
            'end_date' => $date,
            'daily' => 'temperature_2m_max,temperature_2m_min,precipitation_probability_max,wind_speed_10m_max',
            'timezone' => 'auto',
        ]);
        $url = $this->baseUrl . '?' . $query;
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => $this->timeoutSeconds,
                'ignore_errors' => true,
                'header' => "Accept: application/json\r\n",
            ],
        ]);
        $response = @file_get_contents($url, false, $context);
        $status = $this->responseStatus($http_response_header ?? []);
        if ($response === false || $status < 200 || $status >= 300) {
            throw new RuntimeException('Weather provider is unavailable.');
        }

        $payload = json_decode($response, true);
        if (!is_array($payload) || !isset($payload['daily'])) {
            throw new RuntimeException('Weather provider returned an invalid response.');
        }

        $daily = $payload['daily'];
        $precipitation = (float) ($daily['precipitation_probability_max'][0] ?? 0);
        $wind = (float) ($daily['wind_speed_10m_max'][0] ?? 0);

        return [
            'date' => $date,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'temperature_max_c' => $daily['temperature_2m_max'][0] ?? null,
            'temperature_min_c' => $daily['temperature_2m_min'][0] ?? null,
            'precipitation_probability_percent' => $precipitation,
            'wind_speed_max_kmh' => $wind,
            'recommendation' => ($precipitation >= 70 || $wind >= 50)
                ? 'Conditions may be unsuitable for outdoor training.'
                : 'Conditions look suitable for outdoor training.',
        ];
    }

    private function responseStatus(array $headers): int
    {
        if (isset($headers[0]) && preg_match('/\s(\d{3})\s/', $headers[0], $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }
}
