FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    libzip-dev \
    libpq-dev \
    zip

# Install PHP extensions (IMPORTANT FIX HERE)
RUN docker-php-ext-install zip pdo pdo_pgsql

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy project
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Permissions (important for Laravel)
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 10000

# ONLY ONE CMD (correct way)
CMD php artisan optimize:clear && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000
