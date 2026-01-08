FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git unzip libpq-dev curl

RUN docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copier le code Laravel
COPY . .

# Installer les dépendances Laravel
RUN composer install --no-dev --prefer-dist --no-interaction

# Permissions pour storage et bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache
