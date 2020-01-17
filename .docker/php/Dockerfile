ARG PHP_VERSION=""

FROM php:${PHP_VERSION:+${PHP_VERSION}-}fpm-alpine

COPY --from=composer /usr/bin/composer /usr/local/bin/composer


RUN apk update; \
    apk upgrade; \
    apk add --no-cache icu-dev bash git gmp-dev libgmpxx gd libpng-dev libjpeg-turbo-dev freetype-dev libxpm-dev

RUN docker-php-ext-install mysqli pdo_mysql intl gmp gd exif opcache && docker-php-ext-configure gd

COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini
# RUN docker-php-ext-install pdo_mysql
# RUN docker-php-ext-install intl
