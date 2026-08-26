FROM php:8.2-fpm

# Instalacja zależności systemowych i rozszerzeń PHP
RUN apt-get update && apt-get install -y \
    unzip git libzip-dev \
    && docker-php-ext-install pdo_mysql zip \
    && pecl install redis \
    && docker-php-ext-enable redis

# Instalacja Composera
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app