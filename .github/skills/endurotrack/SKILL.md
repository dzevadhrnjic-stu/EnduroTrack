---
name: endurotrack
description: "Use for all EnduroTrack project work: Milestone 1+ frontend, PHP REST API, MySQL schema, JWT authentication, routes, training sessions, competitions, Open-Meteo integration, documentation, testing, and UI changes."
argument-hint: "Describe the EnduroTrack feature, milestone, bug, or documentation task."
user-invocable: true
---

# EnduroTrack Project Skill

## Project context

EnduroTrack is a single-page web application for runners and cyclists. Athletes
track training sessions and routes, coaches manage training plans and athlete
progress, and administrators manage users and competitions.

The current Milestone 1 baseline includes:

- Responsive static SPA frontend with no full-page reload navigation.
- Login and registration screens rendered by the frontend.
- Dashboard, training sessions, routes, training plans, competitions, profile,
  coach workspace, and admin demo views.
- Demo locations and routes from Bosnia and Herzegovina, the Balkans, and
  Europe.
- Light-gray, black, dark-blue, light-blue, and orange visual theme.
- Draft MySQL schema and ERD exports in `docs/ERD.md`, `docs/ERD.svg`,
  `docs/ERD.png`, and `docs/ERD.pdf`.
- Planned Open-Meteo weather integration.

## Technology and architecture

- Frontend: HTML templates, CSS, Bootstrap 5, Bootstrap Icons, and vanilla
  JavaScript.
- Backend: pure PHP REST API using FlightPHP and PDO.
- Database: MySQL.
- Authentication: JWT with reusable authentication and role middleware.
- External integration: Open-Meteo weather forecast for outdoor training.
- Frontend and backend must remain separate. Do not put PHP in frontend files
  or HTML rendering in API route files.

## Folder rules

- `frontend/views/` contains page templates.
- `frontend/js/` contains SPA navigation, API access, and UI logic.
- `frontend/css/` contains the visual theme and responsive styles.
- `frontend/static/` contains static assets.
- `backend/routes/` contains route definitions only.
- `backend/services/` contains business and external API logic.
- `backend/dao/` contains PDO database access.
- `backend/middleware/` contains reusable request, JWT, role, and error
  middleware.
- `backend/config/` contains local configuration; never commit `config.php`.
- `backend/logs/` is for runtime logs and must remain excluded from Git.
- `docs/` contains project documentation, AI log, and ERD exports.

## Naming and implementation rules

- Use `camelCase` for JavaScript functions and variables.
- Use `snake_case` for MySQL tables and columns.
- Use clear REST resource names and appropriate HTTP status codes.
- Validate input at the API boundary.
- Never expose SQL errors, stack traces, secrets, or JWT secrets.
- Reuse existing helpers and patterns before adding new logic.
- Preserve the separation between static frontend behavior and future backend
  integration.
- Keep UI responsive and preserve the current color palette unless the user
  requests a change.
- Update `README.md` and `docs/AI_LOG.md` for every milestone.

## Milestone workflow

1. Inspect the relevant existing files before editing.
2. Identify whether the task belongs to frontend, backend, database,
   documentation, or multiple layers.
3. Make precise changes using the existing project patterns.
4. Keep demo/static behavior working until backend integration replaces it.
5. Validate the smallest relevant surface:
   - frontend: browser smoke test and `problems` check;
   - PHP: syntax check and targeted tests when available;
   - SQL: review schema compatibility and constraints;
   - documentation: verify links and generated deliverables.
6. Update related documentation when behavior or project scope changes.

## Current important files

- SPA entry point: `frontend/index.html`
- SPA logic and demo data: `frontend/js/app.js`
- Theme: `frontend/css/style.css`
- Root redirect: `index.html`
- Project documentation: `README.md`
- AI log: `docs/AI_LOG.md`
- Draft ERD source: `docs/ERD.md`
- ERD exports: `docs/ERD.svg`, `docs/ERD.png`, `docs/ERD.pdf`
- Planned schema: `backend/database/schema.sql`
- Configuration example: `backend/config/config.example.php`

## Future milestone guidance

When implementing backend milestones:

- Keep route files limited to HTTP route definitions and delegation.
- Put business logic and Open-Meteo calls in services.
- Put database queries in DAO classes or functions using PDO prepared
  statements.
- Apply JWT and role middleware consistently.
- Return explicit JSON responses with correct status codes.
- Do not replace the SPA with server-rendered HTML.
- Add real coordinates and map functionality to routes only when the feature
  is requested or required by the milestone.
