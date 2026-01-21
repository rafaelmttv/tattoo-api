#!/bin/bash

# Bootstrap script for Laravel Tattoo API

# Copy .env.example to .env if .env does not exist
if [ ! -f .env ]; then
  cp .env.example .env
fi

# Install composer dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader

# Update composer dependencies
composer update

# Generate application key if not set
php artisan key:generate

# Set permissions for storage and cache directories
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Refresh database
php artisan migrate:fresh

# Run database migrations
php artisan migrate

# Seed the database
php artisan db:seed