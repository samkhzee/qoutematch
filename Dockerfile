# QuoteMatch / Olance — Docker image for Render (and similar hosts)
# Language on Render: Docker
# Root Directory: leave empty (repo root)

FROM php:8.3-cli-bookworm

# System deps + PHP extensions Laravel needs
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip curl libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libonig-dev libxml2-dev libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql mysqli gd zip bcmath intl mbstring exif pcntl opcache \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy project (build context = repo root)
COPY . /app

# Install PHP dependencies (vendor is not in git)
WORKDIR /app/Files/core
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist \
    && mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

WORKDIR /app

# Entrypoint prepares .env and starts PHP server on $PORT
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 10000

ENTRYPOINT ["/entrypoint.sh"]
