FROM php:8.3-fpm

# Systemabhängigkeiten
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev zip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Composer installieren
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
