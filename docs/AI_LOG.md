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

