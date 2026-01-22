#!/bin/bash
set -e

echo "🚀 Bootstrapping Laravel Tattoo API (DEV/WSL friendly)"

# =====================
# Create .env if missing
# =====================
if [ ! -f .env ]; then
  echo "📄 Creating .env from .env.example"
  cp .env.example .env
fi

# =====================
# APP_KEY
# =====================
if ! php artisan key:generate --show > /dev/null 2>&1; then
  echo "🔑 Generating APP_KEY"
  php artisan key:generate --force
else
  echo "🔑 APP_KEY already exists"
fi

# =====================
# Composer dependencies
# =====================
if [ ! -d vendor ]; then
  echo "📦 Installing composer dependencies"
  composer install --no-interaction --prefer-dist --optimize-autoloader
else
  echo "📦 Composer dependencies already installed"
fi

# =====================
# Clear & optimize
# =====================
echo "🧹 Clearing caches"
php artisan optimize:clear

echo "⚡ Optimizing application"
php artisan optimize

# =====================
# Migrations & Seeders
# =====================
echo "⏳ Waiting 5s for DB"
sleep 5

echo "🗄️ Running migrations"
php artisan migrate --force

echo "🌱 Seeding database"
php artisan db:seed --force

echo "✅ Bootstrap completed successfully"