ARG PHP_VERSION=""

FROM php:${PHP_VERSION:+${PHP_VERSION}-}fpm-alpine

COPY --from=composer/composer /usr/bin/composer /usr/local/bin/composer

RUN apk update; \
    apk upgrade; \
    apk add --no-cache icu-dev bash git gmp-dev libgmpxx gd libpng-dev libjpeg-turbo-dev freetype-dev libxpm-dev autoconf g++ make bash-completion linux-headers imagemagick-dev

RUN pecl install xdebug

RUN pecl install imagick && docker-php-ext-enable imagick

RUN docker-php-ext-enable xdebug

RUN docker-php-ext-install mysqli pdo_mysql intl gmp gd exif opcache && docker-php-ext-configure gd

COPY ./conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
RUN cat /usr/local/etc/php/conf.d/opcache.ini >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

COPY ./conf.d/error_reporting.ini /usr/local/etc/php/conf.d/error_reporting.ini
RUN cat /usr/local/etc/php/conf.d/error_reporting.ini >> /usr/local/etc/php/conf.d/docker-php-ext-error_reporting.ini

COPY ./conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
RUN cat /usr/local/etc/php/conf.d/xdebug.ini >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY ./bin/bash-completion.sh /etc/bash-completion.d/cake

RUN chown www-data:www-data /var/www/html/

# USER www-data