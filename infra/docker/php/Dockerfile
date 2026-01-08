FROM php:8.3-fpm

# Dépendances système + outils de compilation PHP
RUN apt-get update -o Acquire::Retries=3 \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        curl \
        libpq-dev \
        libzip-dev \
        zlib1g-dev \
        libicu-dev \
        libonig-dev \
        libxml2-dev \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
        $PHPIZE_DEPS \
    && rm -rf /var/lib/apt/lists/*

# Extensions PHP nécessaires à Laravel
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    bcmath \
    mbstring \
    exif \
    pcntl

# Extensions avec configuration
RUN docker-php-ext-configure intl \
    && docker-php-ext-configure zip \
    && docker-php-ext-install intl zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Code Laravel
COPY . .

# Permissions Laravel
RUN mkdir -p storage bootstrap/cache vendor \
    && chown -R www-data:www-data storage bootstrap/cache vendor \
    && chmod -R 775 storage bootstrap/cache vendor

# Dépendances Laravel
RUN composer install --no-dev --prefer-dist --no-interaction --ignore-platform-reqs
