ARG PHP_VERSION=""
FROM php:${PHP_VERSION:+${PHP_VERSION}-}fpm-alpine

RUN apk update; \
    apk upgrade; \
    apk add --no-cache icu-dev bash gmp-dev;


RUN docker-php-ext-install mysqli pdo_mysql intl gmp
# RUN docker-php-ext-install pdo_mysql
# RUN docker-php-ext-install intl
