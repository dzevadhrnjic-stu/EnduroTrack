# EnduroTrack

**EnduroTrack** is a responsive single-page application for runners and
cyclists. Athletes can record training sessions, save routes, track progress
and register for competitions. Coaches can prepare training plans and review
athlete progress, while administrators manage users and system data.

This repository is the **Milestone 1** submission: project structure, static
SPA frontend and database planning.

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
backend folders are prepared for the REST API, authentication, business logic
and database access in later milestones.

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

The accounts below are planned demo accounts for a later backend milestone:

| Role | Email | Password |
| --- | --- | --- |
| Admin | `admin@endurotrack.test` | `Admin123!` |
| Coach | `coach@endurotrack.test` | `Coach123!` |
| Athlete | `athlete@endurotrack.test` | `Athlete123!` |

These credentials are not connected to the frontend yet; Milestone 1 is static.

## External API

The planned integration is the free **[Open-Meteo API](https://open-meteo.com/)**.
The training form and training details page will use the selected route
coordinates and training date to show temperature, wind and precipitation
probability. The forecast will produce a visible outdoor-training warning when
conditions are unsuitable. API timeouts and failures will be handled in the
backend service in a later milestone.

## Local setup

1. Clone or copy the project into the XAMPP `htdocs` directory.
2. Create a MySQL database named `endurotrack_db`.
3. Import [backend/database/schema.sql](backend/database/schema.sql) using
   phpMyAdmin or the MySQL client.
4. Copy `backend/config/config.example.php` to `backend/config/config.php` and
   fill in local database and JWT values. Do not commit the copied file.
5. Install backend dependencies in a later milestone with Composer.
6. Open `http://localhost/EnduroTrack/` in XAMPP. The root page redirects to
   the frontend SPA.

## Documentation and deployment

- Swagger/OpenAPI documentation: planned at
  `http://localhost/EnduroTrack/backend/docs/` (to be implemented with the REST API).
- Deployed application: **Not deployed yet — link will be added before the
  deployment milestone.**

## Milestone 1 scope

- Responsive static SPA navigation without full-page reloads.
- Login and registration screens.
- Athlete, coach and admin dashboards.
- Training sessions, routes, training plans, competitions and profile screens.
- Draft entity relationships and SQL schema (ERD.md) in docs.
