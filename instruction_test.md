# Laravel Senior Developer Code Test

## Objective

Build a **Translation Management Service** to evaluate your ability to write clean, scalable, and secure code, with a focus on performance.

This is a test task and does not need to be a production-grade application. The expected completion time is **2 hours**, but feel free to complete as much as possible if you have additional time available.

---

## Task

Develop an **API-driven service** with the following features:

### Performance Requirements

- Store translations for multiple locales (e.g., `en`, `fr`, `es`) with the ability to add new languages in the future.
- Tag translations for context (e.g., `mobile`, `desktop`, `web`).
- Expose endpoints to:
  - Create translations
  - Update translations
  - View translations
  - Search translations by tags, keys, or content
- Provide a **JSON export endpoint** to supply translations for frontend applications like Vue.js.
- JSON endpoint should always return updated translations whenever requested.
- Response time for all endpoints should be in milliseconds (i.e., **< 200ms**).
- Provide a command or factory to populate the database with **100k+ records** for scalability testing.
- The JSON export endpoint should handle large datasets efficiently and return responses in **< 500ms**.

---

## Technical Requirements

- Follow **PSR-12** standards.
- Use a **scalable database schema**.
- Follow **SOLID design principles**.

---

## Plus Points

- Optimized SQL queries.
- Implement **token-based authentication** to secure the API.
- No external libraries for CRUD or translation services should be used.
- Docker setup.
- CDN support.
- Test coverage > 95%.
- OpenAPI or Swagger documentation for the API.
- Write unit and feature tests for all critical functionalities, including performance testing.

---

## Deliverables

- GitHub repository with code.
- Include a `README.md` with:
  - Setup instructions
  - Brief explanation of design choices

---

## Evaluation Criteria

| Criteria | Weight |
|----------|--------|
| Code quality and adherence to PSR-12 standards | 20% |
| Scalability and performance optimization | 25% |
| API design and functionality | 20% |
| Security best practices | 20% |
| Testing (unit, feature, and performance) | 15% |

---

_Source: Laravel Senior Developer Code Test document_ :contentReference[oaicite:0]{index=0}
