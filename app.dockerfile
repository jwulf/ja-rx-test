FROM php:7.1-fpm

WORKDIR /var/www

# Application user

RUN useradd -d /var/www -r -G www-data app

# Install system dependencies

RUN apt-get update && \
    apt-get install -y \
        git \
        libpq-dev \
        unzip \
        wget && \
    apt-get clean && \
    docker-php-ext-install pdo_pgsql mbstring tokenizer

# Composer (installed early to aid Docker caching)

COPY bin/composer-install.sh bin/

RUN bin/composer-install.sh && mv composer.phar /usr/local/bin/composer

COPY composer.json composer.lock ./

RUN chown -R app:app . && \
    su app -c "composer install --no-progress --no-autoloader --no-scripts && composer clear-cache"

# Install the application

COPY . .

RUN chown -R app:app .

RUN su app -c "composer install --no-progress"

# Update permissions for serving

RUN chown -R www-data:www-data storage bootstrap/cache
