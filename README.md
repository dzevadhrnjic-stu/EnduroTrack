# EnduroTrack

**EnduroTrack** is a responsive single-page application for runners and
cyclists. Athletes can record training sessions, save routes, track progress
and register for competitions. Coaches can prepare training plans and review
athlete progress, while administrators manage users and system data.

This repository currently contains the completed **Milestone 1** baseline,
**Milestone 2** database and DAO layer, and **Milestone 3** REST API services.

## Project structure

```text
EnduroTrack/
├── frontend/
│   ├── css/
│   ├── js/
│   ├── static/
│   └── views/
├── backend/
│   ├── config/
│   ├── dao/
│   ├── database/
│   ├── logs/
│   ├── middleware/
│   ├── routes/
│   └── services/
└── docs/
```

The frontend contains the responsive static SPA and page templates. The
backend now includes a PDO connection factory, a reusable base DAO and
entity-specific DAO classes, business services, FlightPHP CRUD routes and an
Open-Meteo weather endpoint.

## Planned entities and relationships

- `users` belong to one `roles` record.
- `athlete_profiles` extend one user profile.
- `training_sessions` belong to an athlete and may use one route.
- `routes` can be used by many training sessions and training plans.
- `training_plans` are created by a coach and assigned to an athlete.
- `competitions` contain many athlete registrations through `registrations`.
- `coach_recommendations` connect a coach with an athlete.

The draft ERD is available in [docs/ERD.md](docs/ERD.md), with an export in
[docs/ERD.svg](docs/ERD.svg), and the planned MySQL schema is in
[backend/database/schema.sql](backend/database/schema.sql).

## Roles and test accounts

The accounts below are sample records for the database:

| Role | Email | Password |
| --- | --- | --- |
| Admin | `admin@endurotrack.test` | `password` |
| Coach | `coach@endurotrack.test` | `password` |
| Athlete | `athlete@endurotrack.test` | `password` |

Authentication and frontend connection are reserved for later milestones.

## Local setup

1. Clone or copy the project into the XAMPP `htdocs` directory.
2. Create a MySQL database named `endurotrack_db`.
3. Import [backend/database/schema.sql](backend/database/schema.sql) using
   phpMyAdmin or the MySQL client.
4. Copy `backend/config/config.example.php` to `backend/config/config.php` and
   fill in local database and JWT values. Do not commit the copied file.
5. Use the DAO classes from `backend/dao/` through
   `backend/database/bootstrap.php`. The local PHP installation must have the
   PDO MySQL extension enabled.
6. Install backend dependencies with `composer install`.
7. Open `http://localhost/EnduroTrack/` in XAMPP. The root page redirects to
   the frontend SPA.

## API and Swagger

The backend API base URL is:

```text
http://localhost/EnduroTrack/backend/api
```

CRUD resources are available under `/api/` for roles, users,
athlete-profiles, routes, training-sessions, training-plans, competitions,
registrations and coach-recommendations. Each resource has collection and
single-record GET, POST, PUT, PATCH and DELETE operations: 54 CRUD operations
in total, plus the API health and weather endpoints. The visual API
documentation is available at:

```text
http://localhost/EnduroTrack/backend/docs/swagger.html
```

Manual Milestone 3 test cases are available in
[docs/MILESTONE3_TEST_CASES.md](docs/MILESTONE3_TEST_CASES.md).

Weather forecasts are available through:

```text
/backend/api/weather?latitude=43.8563&longitude=18.4131&date=2026-11-15
```

## Milestone 1 scope

- Responsive static SPA navigation without full-page reloads.
- Login and registration screens.
- Athlete, coach and admin dashboards.
- Training sessions, routes, training plans, competitions and profile screens.
- Draft entity relationships and SQL schema (ERD.md) in docs.

## Milestone 2 scope

- MySQL schema with nine related entities, primary/foreign keys and sample data.
- PDO configuration and connection bootstrap.
- Reusable `BaseDao` with prepared-statement CRUD operations.
- Entity DAO classes for roles, users, athlete profiles, routes, training
  sessions, training plans, competitions, registrations and recommendations.

## Milestone 3 scope

- Business service classes with input validation for every database entity.
- FlightPHP JSON CRUD routes with explicit success and error status codes.
- OpenAPI documentation and a Swagger UI page.
- Open-Meteo weather service with timeout and provider-error handling.
