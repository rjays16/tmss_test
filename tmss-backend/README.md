# TMS Backend

Laravel 12 REST API for the Translation Management Service.

## Quick Start

```bash
# Start containers
docker compose up -d

# Run migrations
docker exec tmss_app php artisan migrate

# Seed database (optional)
docker exec tmss_app php artisan db:seed
```

## API Endpoints

All endpoints require Bearer token authentication except for `/api/v1/translations/export/*`.

### Translations
- `GET /api/v1/translations` - List with pagination
- `POST /api/v1/translations` - Create
- `GET /api/v1/translations/{id}` - Show
- `PUT /api/v1/translations/{id}` - Update
- `DELETE /api/v1/translations/{id}` - Delete
- `GET /api/v1/translations/search?q=query` - Search
- `GET /api/v1/translations/export/json?locales=en,fr` - Export (public)
- `GET /api/v1/translations/export/cdn` - CDN export (public)

### Locales
- `GET /api/v1/locales` - List
- `POST /api/v1/locales` - Create
- `GET /api/v1/locales/{id}` - Show
- `PUT /api/v1/locales/{id}` - Update
- `DELETE /api/v1/locales/{id}` - Delete

### Tags
- `GET /api/v1/tags` - List
- `POST /api/v1/tags` - Create
- `GET /api/v1/tags/{id}` - Show
- `PUT /api/v1/tags/{id}` - Update
- `DELETE /api/v1/tags/{id}` - Delete

## Testing

```bash
docker exec tmss_app php artisan test
```

## Available Locales

en, fr, es, de, it, pt, zh, ja, ko, ar
