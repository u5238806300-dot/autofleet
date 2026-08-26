FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    git \
    unzip \
    libzip-dev \
    icu-dev \
    && docker-php-ext-install pdo_mysql zip intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html