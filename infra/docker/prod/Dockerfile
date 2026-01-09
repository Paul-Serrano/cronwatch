FROM php:8.3-fpm

# -------------------------
# Dépendances système
# -------------------------
RUN apt-get update -o Acquire::Retries=3 \
    && apt-get install -y --no-install-recommends \
        git unzip curl libpq-dev libzip-dev zlib1g-dev \
        libicu-dev libonig-dev libxml2-dev bash build-essential \
    && docker-php-ext-install \
        pdo pdo_pgsql bcmath mbstring pcntl exif intl zip \
    && rm -rf /var/lib/apt/lists/*

# -------------------------
# Composer
# -------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# -------------------------
# Dossier de travail
# -------------------------
WORKDIR /var/www/html

# -------------------------
# Copier composer pour cache Docker
# -------------------------
COPY apps/backend/composer.json apps/backend/composer.lock ./

# Installer dépendances Laravel
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --ignore-platform-reqs

# -------------------------
# Copier le reste du code
# -------------------------
COPY apps/backend/ .

# -------------------------
# Permissions
# -------------------------
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache vendor \
    && chmod -R 775 storage bootstrap/cache vendor \
    && composer dump-autoload --optimize

# -------------------------
# Exposer le port Fly
# -------------------------
EXPOSE 8080

# -------------------------
# Entrypoint
# -------------------------
# Utiliser la variable $PORT fournie par Fly
ENV PORT 8080
CMD php artisan migrate --force && php artisan config:cache && php artisan serve --host=0.0.0.0 --port=${PORT}
