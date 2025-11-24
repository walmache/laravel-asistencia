FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    supervisor

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u 1337 -m wwwuser
RUN mkdir -p /var/www
RUN chown -R wwwuser:www-data /var/www

USER wwwuser
WORKDIR /var/www

RUN composer create-project laravel/laravel:11.* temp-laravel --no-interaction
RUN cp -r temp-laravel/. . && rm -rf temp-laravel

COPY --chown=wwwuser:www-data . /var/www

RUN rm -f composer.lock
RUN composer install

EXPOSE 9000
CMD ["php-fpm"]