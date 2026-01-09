# -------------------------
# Base image PHP 8.3 FPM
# -------------------------
FROM php:8.3-fpm

# -------------------------
# Dépendances système
# -------------------------
RUN apt-get update -o Acquire::Retries=3 \
    && apt-get install -y --no-install-recommends \
        git unzip curl libpq-dev libzip-dev zlib1g-dev \
        libicu-dev libonig-dev libxml2-dev bash build-essential \
        nginx supervisor \
    && docker-php-ext-install pdo pdo_pgsql bcmath mbstring pcntl exif intl zip \
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
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --ignore-platform-reqs

# -------------------------
# Copier le reste du code
# -------------------------
COPY apps/backend/ .

# -------------------------
# Créer dossiers et fixer permissions
# -------------------------
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache vendor \
    && chmod -R 775 storage bootstrap/cache vendor

# -------------------------
# Configurer PHP-FPM pour écouter sur 9000 (FastCGI)
# -------------------------
RUN sed -i 's/listen = .*/listen = 9000/' /usr/local/etc/php-fpm.d/www.conf

# -------------------------
# Copier configuration Nginx
# -------------------------
COPY infra/nginx/default.conf /etc/nginx/sites-available/default

# -------------------------
# Exposer le port utilisé par Fly.io
# -------------------------
EXPOSE 8080

# -------------------------
# Commande de démarrage
# -------------------------
CMD ["sh", "-c", "php artisan config:cache && php artisan route:cache && php artisan view:cache && php-fpm -D && nginx -g 'daemon off;'"]