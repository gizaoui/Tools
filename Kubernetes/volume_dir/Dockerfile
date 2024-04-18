FROM php:8.2-fpm

RUN apt-get -y update && apt-get install -y libssl-dev pkg-config libzip-dev unzip git \
locales curl wget mlocate tree gnupg gcc make autoconf libc-dev libpq-dev libonig-dev

RUN pecl install zlib zip && docker-php-ext-enable zip

RUN docker-php-ext-install pgsql
RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install mbstring

# set locale to utf-8
RUN echo "fr_FR.UTF-8 UTF-8" > /etc/locale.gen && locale-gen
ENV LANG='fr_FR.UTF-8' LANGUAGE='fr_FR:en' LC_ALL='fr_FR.UTF-8'

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
RUN echo "date.timezone=Europe/Rome" >> /usr/local/etc/php/php.ini

WORKDIR /app
COPY index.php .
COPY phpinfo.php .
COPY testdb.php .
COPY css ./css
EXPOSE 9000
CMD ["php-fpm"]
