FROM php:8.2-fpm

MAINTAINER Pestrich <bnm9229bnm@yandex.ru>

# Clear working directory
RUN rm -rf /var/www && mkdir -p /var/www

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    zip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Copy xdebug configuration
COPY ./.docker/php-fpm/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Copy composer.lock, composer.json and symfony.lock
COPY ./composer.* ./symfony.lock ./

# Copy composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Composer install
RUN composer install --no-scripts

# Hack: link 'sh' to 'bash'
RUN ln -sf /bin/bash /bin/sh

CMD ["php-fpm"]
