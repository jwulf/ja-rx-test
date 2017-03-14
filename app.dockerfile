FROM php:7.1-fpm

RUN apt-get update && \
    apt-get install -y \
        libpq-dev && \
    apt-get clean && \
    docker-php-ext-install pdo_pgsql mbstring tokenizer
