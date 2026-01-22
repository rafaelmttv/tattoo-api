#!/bin/bash

set -e

echo "🚀 Bootstrapping Laravel Tattoo API (DEV ONLY)"

# Prevent running in production
if [ "$APP_ENV" = "production" ]; then
  echo "❌ Bootstrap cannot be run in production."
  exit 1
fi

# Copy .env if not exists
if [ ! -f .env ]; then
  echo "📄 Creating .env file"
  cp .env.example .env
fi

# Install dependencies (lock-based)
echo "📦 Installing Composer dependencies"
composer install --no-interaction --prefer-dist --optimize-autoloader

# Generate app key only if missing
if ! grep -q "APP_KEY=base64" .env; then
  echo "🔑 Generating APP_KEY"
  php artisan key:generate
fi

# Permissions
echo "🔐 Setting permissions"
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Database setup
echo "🗄️ Running migrations"
php artisan migrate

# Optional seeding
if [ "$SEED_DB" = "true" ]; then
  echo "🌱 Seeding database"
  php artisan db:seed
fi

echo "✅ Bootstrap completed successfully"
