# EnduroTrack AI Context

## Project
EnduroTrack is a single-page web application for runners and cyclists. Athletes
track training sessions and routes, coaches manage training plans, and
administrators manage users and competitions.

## Technology and architecture
- Frontend: HTML templates, CSS, Bootstrap 5 and vanilla JavaScript.
- Backend: pure PHP REST API using FlightPHP and PDO.
- Database: MySQL.
- Authentication: JWT, with reusable authentication and role middleware.
- External integration: Open-Meteo weather forecast for outdoor training.
- Frontend and backend must remain separate. Do not place PHP in frontend files
  or HTML rendering in API route files.

## Folder rules
- `frontend/views/` contains page templates.
- `frontend/js/` contains SPA navigation, API access and UI logic.
- `backend/routes/` contains route definitions only.
- `backend/services/` contains business and external API logic.
- `backend/dao/` contains PDO database access.
- `backend/middleware/` contains reusable request, JWT, role and error middleware.
- `docs/` contains project documentation and the ERD.

## Naming and implementation rules
- Use `camelCase` for JavaScript functions and variables.
- Use `snake_case` for MySQL tables and columns.
- Use clear REST resource names and HTTP status codes.
- Validate input at the API boundary and never expose SQL errors or stack traces.
- Keep secrets in `backend/config/config.php`, never commit that file.
- Update `README.md` and `docs/AI_LOG.md` for every milestone.

