FROM php:8.0-fpm

COPY composer.lock composer.json /var/www/

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev

RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo_mysql zip exif pcntl
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www
COPY --chown=root:root . /var/www

USER root
EXPOSE 9000

RUN chmod -R 777 storage
RUN touch /var/www/storage/logs/laravel.log
RUN composer install --ignore-platform-reqs
RUN php artisan cache:clear
RUN php artisan config:clear

CMD bash -c "php-fpm"
