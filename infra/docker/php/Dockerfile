FROM php:8.3-fpm

# Dépendances système + PHP build
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
        $PHPIZE_DEPS \
    && rm -rf /var/lib/apt/lists/*

# Extensions PHP Laravel
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    bcmath \
    mbstring \
    exif \
    pcntl

RUN docker-php-ext-configure intl \
    && docker-php-ext-configure zip \
    && docker-php-ext-install intl zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 1️⃣ Copier UNIQUEMENT composer files
COPY composer.json composer.lock ./

# 2️⃣ Installer dépendances SANS scripts
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-scripts \
    --ignore-platform-reqs

# 3️⃣ Copier le reste du projet
COPY . .

# 4️⃣ Permissions Laravel
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache vendor \
    && chmod -R 775 storage bootstrap/cache

# 5️⃣ Lancer les scripts Laravel maintenant
RUN composer dump-autoload --optimize
