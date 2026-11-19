# AI Usage Log

## Milestone 1 — Initial setup, static frontend and database planning

### Tools used

- Copilot SDK in VS Code was used to propose the EnduroTrack concept, design the
  entity relationships, create the folder structure and generate the initial
  static SPA templates.
- No external AI tools were used.

### Important prompts

- “Suggest a project that satisfies the PHP, JS, MySQL, FlightPHP, JWT,
  external API and SPA requirements.”
- “Create a Milestone 1 structure for EnduroTrack, a running and cycling
  training application, with static responsive pages and a draft ERD.”

### AI mistakes and corrections

1. The first concept included a broad sports-club scope with many sports. This
   was narrowed to running and cycling so the domain remains realistic and
   manageable. The frontend and schema now use the two selected disciplines.
2. The first external API idea included sports-result APIs that may require
   API keys or have unstable limits. It was replaced with Open-Meteo, which is
   free for this use case and directly supports weather recommendations for
   outdoor training.

All generated files were reviewed and adjusted to keep frontend and backend
concerns separate and to make the Milestone 1 deliverables explicit.

## Milestone 2 — Database and DAO layer

### Tools used

- Copilot SDK in VS Code was used to inspect the project requirements and
  implement the PDO connection, reusable base DAO, entity DAO classes and
  sample database data.
- No external AI tools were used.

### Important prompts

- “Read the project requirements and implement Milestone 2.”
- “Implement a reusable PDO DAO layer with CRUD operations using prepared
  statements for the existing EnduroTrack entities.”

### AI mistakes and corrections

1. The first implementation attempt produced a malformed non-ASCII route name
   in the SQL sample data. It was noticed during review and corrected to the
   project’s ASCII naming convention.
2. The existing schema had only role seed data, so it did not satisfy the
   sample-data requirement. Sample records were added for users, profiles,
   routes, sessions, plans, competitions, registrations and recommendations.

## Milestone 3 — Services, routes, OpenAPI and external API

### Tools used

- Copilot SDK in VS Code was used to implement the FlightPHP bootstrap,
  entity business services, JSON CRUD routes, Swagger UI and Open-Meteo
  weather service.
- Composer was used to install the FlightPHP core dependency.
- No external AI tools were used.

### AI mistakes and corrections

1. The first HTTP smoke test exposed the raw PDO connection exception as an
   HTML fatal error when MySQL was unavailable. A bootstrap guard was added so
   the API returns a JSON `500` message without stack traces.
2. The first service validation version did not validate string, email and
   generic numeric fields consistently. Explicit validation rules were added
   to the shared service base before entity services were tested.

The route layer delegates to services, and services delegate to DAOs, keeping
database access out of FlightPHP route definitions.

Password handling was also corrected so the users API accepts a plain
`password` field, validates it and creates or replaces `password_hash` with
PHP `password_hash()`. Password hashes are excluded from GET responses.
