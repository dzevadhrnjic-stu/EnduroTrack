# Milestone 3 API test cases

## Prerequisites

1. Start Apache and MySQL in XAMPP.
2. Confirm `backend/config/config.php` uses the active MySQL port and credentials.
3. Open PowerShell in the repository root:

```powershell
cd C:\xampp\htdocs\EnduroTrack
```

The base API URL is:

```powershell
$baseUrl = 'http://localhost/EnduroTrack/backend/api'
```

## TC-01: API health

```powershell
Invoke-RestMethod "$baseUrl"
```

Expected: HTTP `200`, API name and version are returned.

## TC-02: List every entity

Run each request:

```powershell
$resources = @(
  'roles',
  'users',
  'athlete-profiles',
  'routes',
  'training-sessions',
  'training-plans',
  'competitions',
  'registrations',
  'coach-recommendations'
)

foreach ($resource in $resources) {
  Write-Host "`nGET $resource"
  Invoke-RestMethod "$baseUrl/$resource"
}
```

Expected: HTTP `200` and a JSON object containing a `data` array.

## TC-03: Read one record

```powershell
Invoke-RestMethod "$baseUrl/routes/1"
Invoke-RestMethod "$baseUrl/users/3"
Invoke-RestMethod "$baseUrl/competitions/1"
```

Expected: HTTP `200` and a JSON object containing one `data` record.

## TC-04: Route CRUD

```powershell
$route = @{
  created_by = 3
  name = 'Milestone 3 manual test route'
  discipline = 'running'
  distance_km = 5.5
  elevation_gain_m = 30
  city = 'Sarajevo'
  latitude = 43.8563
  longitude = 18.4131
} | ConvertTo-Json

$created = Invoke-RestMethod "$baseUrl/routes" `
  -Method Post -ContentType 'application/json' -Body $route

$routeId = $created.data.id
Write-Host "Created route ID: $routeId"

Invoke-RestMethod "$baseUrl/routes/$routeId"

$update = @{
  name = 'Milestone 3 updated route'
  distance_km = 6.25
} | ConvertTo-Json

Invoke-RestMethod "$baseUrl/routes/$routeId" `
  -Method Patch -ContentType 'application/json' -Body $update

Invoke-RestMethod "$baseUrl/routes/$routeId" -Method Delete
```

Expected:

- POST: `201`
- GET: `200`
- PATCH: `200`
- DELETE: `200`
- The temporary route is removed at the end.

## TC-05: Create records for the remaining entities

For `users`, send the plain password in the `password` field. Do not send
`password_hash`; the backend creates it with `password_hash()` and excludes it
from API responses.

Use the following payloads with `Invoke-RestMethod -Method Post`.

```powershell
$payloads = @{
  roles = @{ name = 'test-role' }
  'athlete-profiles' = @{
    user_id = 3
    discipline = 'both'
    experience_level = 'intermediate'
    season_goal = 'Complete a test goal'
  }
  'training-sessions' = @{
    athlete_id = 3
    route_id = 1
    discipline = 'running'
    session_date = '2026-11-15'
    distance_km = 5.0
    duration_minutes = 30
    notes = 'Milestone 3 test session'
  }
  'training-plans' = @{
    coach_id = 2
    athlete_id = 3
    route_id = 1
    title = 'Milestone 3 test plan'
    planned_date = '2026-11-16'
    target_distance_km = 8.0
    status = 'planned'
  }
  competitions = @{
    created_by = 1
    name = 'Milestone 3 Test Competition'
    discipline = 'running'
    event_date = '2026-12-01'
    location = 'Sarajevo, BiH'
    distance_km = 10.0
    registration_deadline = '2026-11-25'
  }
  registrations = @{
    competition_id = 1
    athlete_id = 3
  }
  'coach-recommendations' = @{
    coach_id = 2
    athlete_id = 3
    title = 'Milestone 3 test recommendation'
    content = 'Keep the effort controlled.'
  }
}

foreach ($resource in $payloads.Keys) {
  $json = $payloads[$resource] | ConvertTo-Json
  Write-Host "`nPOST $resource"
  Invoke-RestMethod "$baseUrl/$resource" `
    -Method Post -ContentType 'application/json' -Body $json
}
```

Expected: HTTP `201` for valid payloads. Delete created test records afterward
or remove them manually in phpMyAdmin.

## TC-06: Validation errors

```powershell
$invalidRoute = @{
  created_by = 3
  name = ''
  discipline = 'skating'
  distance_km = -1
  city = 'Sarajevo'
} | ConvertTo-Json

try {
  Invoke-RestMethod "$baseUrl/routes" `
    -Method Post -ContentType 'application/json' -Body $invalidRoute
} catch {
  $_.Exception.Response.StatusCode
}
```

Expected: HTTP `400` with a JSON `error` field.

## TC-07: Missing resource

```powershell
try {
  Invoke-RestMethod "$baseUrl/routes/999999"
} catch {
  $_.Exception.Response.StatusCode
}
```

Expected: HTTP `404` with a JSON `error` field.

## TC-08: Weather endpoint

```powershell
$weather = Invoke-RestMethod `
  "$baseUrl/weather?latitude=43.8563&longitude=18.4131&date=2026-11-15"

$weather.data
```

Expected: HTTP `200` with temperature, precipitation probability, wind speed
and an outdoor-training recommendation.

Also test invalid coordinates:

```powershell
try {
  Invoke-RestMethod `
    "$baseUrl/weather?latitude=999&longitude=18.4131&date=2026-11-15"
} catch {
  $_.Exception.Response.StatusCode
}
```

Expected: HTTP `400`.

## TC-09: Swagger UI

Open:

```text
http://localhost/EnduroTrack/backend/docs/swagger.html
```

Verify that the page loads and shows:

- API health
- generic resource collection and item operations
- all nine resource values in the `resource` selector
- weather endpoint
- request and response status codes

### POST, PUT and PATCH request bodies

In Swagger UI, select `POST`, `PUT` or `PATCH` and then choose one of the
named examples from the request-body `Example Value` dropdown:

- `role`
- `user`
- `athlete-profile`
- `route`
- `training-session`
- `training-plan`
- `competition`
- `registration`
- `coach-recommendation`

Before executing a request:

1. Select the matching entity in the `resource` path parameter.
2. For `PUT` and `PATCH`, enter an existing record ID in the `id` parameter.
3. Choose an example that matches the selected resource.
4. Adjust IDs and unique fields if the sample values are already in use.

POST examples provide all required fields. PUT examples provide a complete
writable body for replacement. PATCH examples contain only fields that should
be changed.

## TC-10: PHP and dependency checks

```powershell
$php = 'C:\xampp\php\php.exe'
Get-ChildItem .\backend -Recurse -Filter '*.php' |
  ForEach-Object { & $php -l $_.FullName }

composer validate --no-check-publish
```

Expected: no PHP syntax errors and valid Composer configuration.
