FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    libzip-dev \
    zip

RUN docker-php-ext-install zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN php artisan config:clear
RUN php artisan config:cache

# RUN php artisan route:clear
# RUN php artisan cache:clear
# RUN php artisan optimize:clear

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000

CMD php artisan migrate --force && php artisan serve --host=dpg-d87hgs99rddc738f3100-a --port=5432
