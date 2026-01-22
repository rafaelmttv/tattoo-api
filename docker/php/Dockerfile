# =========================
# Base
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

###################################
# UID/GID alinhado com host (DEV)
###################################
ARG UID=1000
ARG GID=1000

RUN addgroup -g ${GID} appgroup \
 && adduser -D -u ${UID} -G appgroup appuser

# =========================
# DEV
# =========================
FROM base AS dev

RUN pecl install xdebug \
 && docker-php-ext-enable xdebug

COPY docker/php/php.dev.ini /usr/local/etc/php/php.ini

# 🔑 CRIA STORAGE E CACHE COM PERMISSÕES CORRETAS
RUN mkdir -p \
    storage/logs \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache \
 && chown -R appuser:appgroup storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

USER appuser

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

RUN mkdir -p storage/logs \
    storage/framework/{cache,sessions,views} \
    bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

USER www-data

CMD ["php-fpm"]
