# Translation Management Service (TMS)

## Prerequisites

- Docker & Docker Compose
- Node.js 22.19.0 (for frontend)

## Setup with Docker

### 1. Start the containers

```bash
cd tmss-backend
docker compose up -d
```

### 2. Run migrations and seed

```bash
docker-compose exec app composer install
docker-compose exec app php artisan migrate:fresh --seed
```

### 3. Access the application

- API: http://localhost:8000
- Frontend: http://localhost:5173 (run separately)
- phpMyAdmin: http://localhost:8080

## Setup without Docker

### Backend

```bash
cd tmss-backend
composer install
cp .env.example .env
php artisan migrate
php artisan db:seed
php artisan serve
```

### Frontend

```bash
cd tmss-frontend
npm install
npm run dev
```

## Testing

```bash
docker-compose exec app php artisan test
```

## Configuration

### Environment Variables

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tmss_db
DB_USERNAME=tmss_user
DB_PASSWORD=tmss_pass

CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

CDN_ENABLED=true
CDN_CACHE_TTL=3600
```
