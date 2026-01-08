FROM php:8.3-fpm

# Installer dépendances système + PHP extensions Laravel
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev curl libzip-dev zlib1g-dev \
    libxml2-dev libonig-dev \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath mbstring exif pcntl tokenizer

# Installer composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail
WORKDIR /var/www/html

# Copier le code Laravel
COPY . .

# Permissions pour storage & bootstrap/cache et vendor
RUN mkdir -p storage bootstrap/cache vendor \
    && chown -R www-data:www-data storage bootstrap/cache vendor \
    && chmod -R 775 storage bootstrap/cache vendor

# Installer les dépendances Laravel
RUN composer install --no-dev --prefer-dist --no-interaction --ignore-platform-reqs
