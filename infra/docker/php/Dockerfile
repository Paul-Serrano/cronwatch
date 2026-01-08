FROM php:8.3-fpm

# Installer dépendances système + PHP extensions pour Laravel
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev curl libzip-dev zlib1g-dev \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath

# Installer composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail
WORKDIR /var/www/html

# Copier le code Laravel
COPY . .

# Permissions pour storage & bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Installer les dépendances Laravel
RUN composer install --no-dev --prefer-dist --no-interaction --ignore-platform-reqs
