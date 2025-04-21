FROM php:8.1-fpm


# Install dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
        cron \
        git \
        libzip-dev \
        zip \
        unzip \
        libwebp-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libfreetype6-dev \
        libicu-dev \
        libmagickwand-dev \
        && docker-php-ext-configure gd \
        --with-webp \
        --with-jpeg \
        --with-freetype \
        && docker-php-ext-configure intl \
        && docker-php-ext-install \
        zip \
        bcmath \
        gd \
        mysqli \
        pdo_mysql \
        intl \
        && pecl install imagick \
        && docker-php-ext-enable imagick \
        && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
