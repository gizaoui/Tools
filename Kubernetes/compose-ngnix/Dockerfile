FROM php:8.2-fpm

RUN apt-get -y update && apt-get install -y libssl-dev pkg-config libzip-dev unzip git \
locales curl wget mlocate tree gnupg gcc make autoconf libc-dev libpq-dev libonig-dev

RUN pecl install zlib zip && docker-php-ext-enable zip

RUN docker-php-ext-install pgsql
RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install mbstring

# set locale to utf-8
RUN echo "fr_FR.UTF-8 UTF-8" > /etc/locale.gen && locale-gen
ENV LANG='fr_FR.UTF-8' LANGUAGE='en_US:en' LC_ALL='en_US.UTF-8'


