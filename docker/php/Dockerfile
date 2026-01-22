# =========================
# Base PHP
# =========================
FROM php:8.3-fpm-alpine AS base

WORKDIR /var/www

RUN apk add --no-cache \
    bash \
    git \
    curl \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    linux-headers \
    $PHPIZE_DEPS \
 && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    intl \
    zip \
    bcmath \
    opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# =========================
# DEV
# =========================
FROM base AS dev

RUN pecl install xdebug \
 && docker-php-ext-enable xdebug

COPY docker/php/php.dev.ini /usr/local/etc/php/php.ini

CMD ["php-fpm"]

# =========================
# PROD
# =========================
FROM base AS prod

COPY docker/php/php.prod.ini /usr/local/etc/php/php.ini

COPY . .

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-progress

RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

RUN php artisan key:generate --force \
 && php artisan config:clear \
 && php artisan route:clear \
 && php artisan view:clear

CMD ["php-fpm"]
