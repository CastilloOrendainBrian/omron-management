FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
        git \
        curl \
        unzip \
        libpq-dev \
        libzip-dev \
        libicu-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
        pdo \
        pdo_pgsql \
        pcntl \
        bcmath \
        zip \
        intl \
        opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 8000

CMD ["sh", "-c", "set -e\nif [ ! -f artisan ]; then\n  echo '==> No Laravel project found, creating one...'\n  composer create-project laravel/omron-management . --no-interaction --prefer-dist\nfi\nif [ ! -d vendor/laravel/sanctum ]; then\n  echo '==> Installing Sanctum...'\n  composer require laravel/sanctum --no-interaction\n  php artisan install:api --no-interaction\nfi\nif [ ! -d vendor/spatie/laravel-permission ]; then\n  echo '==> Installing Spatie laravel-permission...'\n  composer require spatie/laravel-permission --no-interaction\n  php artisan vendor:publish --tag=permission-config --no-interaction\n  php artisan vendor:publish --tag=permission-migrations --no-interaction\nfi\necho '==> Configuring database...'\nsed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=pgsql/' .env || true\nsed -i 's|^DB_HOST=.*|DB_HOST=db|' .env || true\nsed -i 's|^DB_PORT=.*|DB_PORT=5432|' .env || true\nsed -i 's|^DB_DATABASE=.*|DB_DATABASE=omron-management|' .env || true\nsed -i 's|^DB_USERNAME=.*|DB_USERNAME=postgres|' .env || true\nsed -i 's|^DB_PASSWORD=.*|DB_PASSWORD=password|' .env || true\necho '==> Running migrations...'\nphp artisan migrate --force --no-interaction || true\necho '==> Dev server running on http://localhost:8000'\nexec php artisan serve --host=0.0.0.0 --port=8000"]
