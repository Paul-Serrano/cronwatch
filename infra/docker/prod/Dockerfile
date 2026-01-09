FROM php:8.3-fpm

# Dépendances système + extensions PHP
RUN apt-get update -o Acquire::Retries=3 \
    && apt-get install -y --no-install-recommends \
        git unzip curl libpq-dev libzip-dev zlib1g-dev \
        libicu-dev libonig-dev libxml2-dev bash build-essential \
        libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install \
        pdo pdo_pgsql bcmath mbstring pcntl exif intl zip gd \
    && docker-php-ext-enable intl gd \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copier composer.json et composer.lock pour le cache Docker
COPY apps/backend/composer.json apps/backend/composer.lock ./

# Créer dossiers nécessaires
RUN mkdir -p storage bootstrap/cache vendor \
    && chown -R www-data:www-data storage bootstrap/cache vendor

# Installer dépendances Laravel sans scripts
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs --no-scripts

# Copier le reste du code
COPY apps/backend/ .

# Permissions finales
RUN chown -R www-data:www-data storage bootstrap/cache vendor \
    && chmod -R 775 storage bootstrap/cache vendor

# Exposer le port utilisé par Fly.io
EXPOSE 8080

# Lancer le serveur Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
