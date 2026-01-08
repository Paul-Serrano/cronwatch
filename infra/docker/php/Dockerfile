FROM php:8.3-fpm

# Dépendances système
RUN apt-get update -o Acquire::Retries=3 \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libpq-dev \
        curl \
        libzip-dev \
        zlib1g-dev \
        libxml2-dev \
        libonig-dev \
        apt-utils \
        gnupg \
        ca-certificates \
        libicu-dev \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

# Extensions PHP de base
RUN docker-php-ext-install pdo pdo_pgsql bcmath mbstring tokenizer pcntl

# Extensions PHP nécessitant configuration
RUN docker-php-ext-configure intl \
    && docker-php-ext-configure zip \
    && docker-php-ext-install intl zip exif

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Dossier de travail
WORKDIR /var/www/html

# Copier le code Laravel
COPY . .

# Permissions pour storage & bootstrap/cache et vendor
RUN mkdir -p storage bootstrap/cache vendor \
    && chown -R www-data:www-data storage bootstrap/cache vendor \
    && chmod -R 775 storage bootstrap/cache vendor

# Installer dépendances Laravel
RUN composer install --no-dev --prefer-dist --no-interaction --ignore-platform-reqs
