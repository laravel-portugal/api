FROM php:7.4-fpm

RUN apt-get update
RUN apt-get install -y \
            libzip-dev \
            libc-client-dev \
            libkrb5-dev \
            libpng-dev \
            libjpeg-dev \
            libwebp-dev \
            libfreetype6-dev \
            libkrb5-dev \
            libicu-dev \
            zlib1g-dev \
            zip \
            ffmpeg \
            libmemcached11 \
            libmemcachedutil2 \
            build-essential \
            libmemcached-dev \
            gnupg2 \
            libpq-dev \
            libpq5 \
            libz-dev
RUN docker-php-ext-configure gd \
    --with-webp=/usr/include/ \
    --with-freetype=/usr/include/ \
    --with-jpeg=/usr/include/
RUN docker-php-ext-install gd
RUN docker-php-ext-configure imap \
    --with-kerberos \
    --with-imap-ssl
RUN docker-php-ext-install imap
RUN docker-php-ext-configure zip
RUN docker-php-ext-install zip
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl
RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install pgsql
RUN docker-php-ext-install exif
RUN docker-php-ext-install fileinfo

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ADD . /var/www/html

EXPOSE 80

CMD php -S 0.0.0.0:80 -t ./public
