ARG PHP_VERSION=""

FROM php:${PHP_VERSION:+${PHP_VERSION}-}fpm-alpine

COPY --from=composer/composer /usr/bin/composer /usr/local/bin/composer

RUN apk update; \
    apk upgrade; \
    apk add --no-cache icu-dev bash git gmp-dev libgmpxx gd libpng-dev libjpeg-turbo-dev freetype-dev libxpm-dev autoconf g++ make

RUN pecl install xdebug

RUN docker-php-ext-enable xdebug

RUN docker-php-ext-install mysqli pdo_mysql intl gmp gd exif opcache && docker-php-ext-configure gd

COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini
RUN cat /usr/local/etc/php/conf.d/opcache.ini >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
RUN cat /usr/local/etc/php/conf.d/xdebug.ini >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN chown www-data:www-data /var/www/html/

# USER www-data