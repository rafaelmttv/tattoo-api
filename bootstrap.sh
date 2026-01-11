#!/bin/bash

# Bootstrap script for Laravel Tattoo API

# Copy .env.example to .env if .env does not exist
if [ ! -f .env ]; then
  cp .env.example .env
fi

# Generate application key if not set
php artisan key:generate

# Run database migrations
php artisan migrate

# Seed the database
php artisan db:seed