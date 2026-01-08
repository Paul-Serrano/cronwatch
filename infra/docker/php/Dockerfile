FROM php:8.3-fpm

# Mettre à jour apt et installer les dépendances système
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
    && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP nécessaires pour Laravel
RUN docker-php-ext-install \
        pdo \
        pdo_pgsql \
        zip \
        bcmath \
        mbstring \
        exif \
        pcntl \
        tokenizer \
        intl

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
