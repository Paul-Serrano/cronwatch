FROM php:8.4-fpm

RUN apt-get update \
    && apt-get install -y \
        git unzip curl libpq-dev libzip-dev zlib1g-dev \
        libicu-dev libonig-dev libxml2-dev \
        $PHPIZE_DEPS \
    && docker-php-ext-install \
        pdo pdo_pgsql bcmath mbstring exif pcntl \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
