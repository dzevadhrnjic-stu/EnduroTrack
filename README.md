# EnduroTrack

**EnduroTrack** is a responsive single-page application for runners and
cyclists. Athletes can record training sessions, save routes, track progress
and register for competitions. Coaches can prepare training plans and review
athlete progress, while administrators manage users and system data.

This repository currently contains the completed **Milestone 1** baseline and
the **Milestone 2** database and DAO layer.

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
entity-specific DAO classes.

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
6. Open `http://localhost/EnduroTrack/` in XAMPP. The root page redirects to
   the frontend SPA.

## Milestone 2 scope

- MySQL schema with nine related entities, primary/foreign keys and sample data.
- PDO configuration and connection bootstrap.
- Reusable `BaseDao` with prepared-statement CRUD operations.
- Entity DAO classes for roles, users, athlete profiles, routes, training
  sessions, training plans, competitions, registrations and recommendations.

## Milestone 1 scope

- Responsive static SPA navigation without full-page reloads.
- Login and registration screens.
- Athlete, coach and admin dashboards.
- Training sessions, routes, training plans, competitions and profile screens.
- Draft entity relationships and SQL schema (ERD.md) in docs.
