# Tattoo API

This is a REST API for managing tattoo artists, studios, customers, artworks, and services.

## Setup

### Prerequisites

- Docker
- Docker Compose

### Installation

1. Clone the repository.
2. Copy `.env.example` to `.env` and configure environment variables.
3. Run `docker-compose up --build -d` to start the containers.
4. Run `./bootstrap.sh` to set up the database and seed data.
5. The API will be available at `http://localhost:8080`.

### Database

The application uses MySQL. Migrations are included for all models.

### Seeds

Run `php artisan db:seed` to seed initial roles and users.

### API Documentation

See `openapi.yaml` for the API specification.
