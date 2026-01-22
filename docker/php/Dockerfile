# =========================
# Base PHP
# =========================
FROM php:8.3-fpm-alpine AS base

WORKDIR /var/www

# Dependências do sistema
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

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# =========================
# UID/GID align (DEV)
# =========================
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

USER appuser
CMD ["php-fpm"]

# =========================
# PROD (não altera para DEV/WLS)
# =========================
FROM base AS prod

COPY docker/php/php.prod.ini /usr/local/etc/php/php.ini

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

USER www-data
CMD ["php-fpm"]
