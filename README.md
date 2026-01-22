# Tattoo API

REST API built with **Laravel 11** for managing tattoo studios, tattoo artists, artworks, services, and customers.  
Designed to be consumed by **web frontends and mobile applications**, with full **OpenAPI (Swagger)** documentation.

---

## 🧱 Tech Stack

- PHP 8.3
- Laravel 11
- MySQL 8
- Docker & Docker Compose
- Nginx
- Laravel Sanctum (Authentication)
- Swagger / OpenAPI 3

---

## 📁 Project Structure (Relevant)

```
.
├── docker/
│   ├── php/
│   │   ├── Dockerfile
│   │   ├── php.dev.ini
│   │   └── php.prod.ini
│   └── nginx/
│       ├── dev.conf
│       └── prod.conf
├── docs/
│   └── openapi.v1.yml
├── docker-compose.yml
├── docker-compose.prod.yml
├── bootstrap.sh
└── README.md
```

---

## 🚀 Development Setup

### Requirements

- Docker
- Docker Compose (v2)

---

### 1️⃣ Clone the repository

```bash
git clone git@github.com:rafaelmttv/tattoo-api.git
cd tattoo-api
```

---

### 2️⃣ Start containers (Development)

```bash
docker compose up -d --build
```

This will start:
- PHP-FPM (Laravel)
- Nginx
- MySQL
- Swagger UI

---

### 3️⃣ Bootstrap the application (DEV only)

The bootstrap script prepares the local environment:

- Installs Composer dependencies
- Generates `APP_KEY` if missing
- Runs database migrations
- Optionally seeds the database

```bash
docker compose exec app ./bootstrap.sh
```

#### Optional: Seed database

```bash
docker compose exec -e SEED_DB=true app ./bootstrap.sh
```

> ⚠️ **Important**  
> `bootstrap.sh` is **DEV-only** and **cannot be executed in production**.

---

### 4️⃣ Access the services

- API  
  👉 http://localhost:8080

- Swagger UI  
  👉 http://localhost:8081

---

## 🔐 Authentication

The API uses **Laravel Sanctum** with Bearer Token authentication.

### Flow

1. Register or login
2. Receive a Bearer token
3. Send the token in requests:

```
Authorization: Bearer <token>
```

Swagger UI supports authentication via the **Authorize** button.

---

## 📄 API Documentation (Swagger / OpenAPI)

- OpenAPI spec location:

```
docs/openapi.v1.yml
```

- Swagger UI automatically loads this file via Docker.

The documentation is designed to support:
- Frontend applications (React, Vue, Next.js)
- Mobile applications (Flutter, React Native)
- Automatic SDK generation

---

## 🏭 Production Setup

To run the application in production mode:

```bash
docker compose -f docker-compose.prod.yml up -d --build
```

### ⚠️ Production Notes

- Do **NOT** run `bootstrap.sh`
- Do **NOT** use volume mounts for application code
- Set `APP_ENV=production`
- Set `APP_DEBUG=false`
- Configure environment variables securely
- Run migrations manually and safely

---

## 🧪 Useful Commands

```bash
# List routes
docker compose exec app php artisan route:list

# Run migrations
docker compose exec app php artisan migrate

# Run tests
docker compose exec app php artisan test
```

---

## 📱 Frontend & Mobile Readiness

This API is fully prepared to be consumed by:

- React / Next.js
- Vue
- Flutter
- React Native

Thanks to:
- Versioned OpenAPI specification
- Standardized API responses
- Bearer authentication
- Pagination-ready endpoints

SDKs can be generated directly from `openapi.v1.yml`.

---

## 🧠 Environment Variables (Important)

Key variables:

```env
APP_ENV=local|production
APP_DEBUG=true|false
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=db
DB_DATABASE=tattoo_api
DB_USERNAME=laravel
DB_PASSWORD=password
```

---

## 🛡️ Security Notes

- Never commit `.env` files
- Never run `composer update` in production
- Never run destructive migrations automatically
- Restrict Swagger access in production environments

---

## 📜 License

MIT License

---

## ✅ Project Status

✔ Dockerized (DEV / PROD)  
✔ OpenAPI documented  
✔ Frontend & mobile ready  
✔ Production-safe bootstrap  
