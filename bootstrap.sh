#!/bin/bash
set -e

echo "🚀 Bootstrapping Laravel Tattoo API (DEV/WSL friendly)"

#####################################
# Environment
#####################################
if [ ! -f .env ]; then
  echo "📄 Creating .env from .env.example"
  cp .env.example .env
fi

#####################################
# Dependencies
#####################################
if [ ! -d vendor ]; then
  echo "📦 Installing composer dependencies"
  composer install --no-interaction --prefer-dist --optimize-autoloader
else
  echo "📦 Composer dependencies already installed"
fi

#####################################
# Application key
#####################################
if ! php artisan key:generate --show > /dev/null 2>&1; then
  echo "🔑 Generating APP_KEY"
  php artisan key:generate --force
else
  echo "🔑 APP_KEY already exists"
fi

#####################################
# Permissions (ignore errors on WSL)
#####################################
echo "🔐 Setting permissions (ignoring host bind mount errors)"
chmod -R 775 storage bootstrap/cache || true

#####################################
# Migrations & Seeds
#####################################
echo "🗄️ Running migrations"
php artisan migrate:fresh --force

# Optional seeding
if [ "$SEED_DB" = "true" ]; then
  echo "🌱 Seeding database"
  php artisan db:seed
fi

#####################################
# Cache / Config
#####################################
echo "🧹 Clearing caches"
php artisan optimize:clear

echo "⚡ Optimizing application"
php artisan optimize

echo "✅ Bootstrap completed successfully"
