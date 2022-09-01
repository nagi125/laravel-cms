FROM php:8.1-fpm-alpine

ENV TZ Asia/Tokyo
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_PROCESS_TIMEOUT 0
ENV COMPOSER_NO_INTERACTION 1

RUN set -eux && \
    apk add --update-cache --no-cache openssl git autoconf g++ nodejs npm yarn oniguruma-dev postgresql-dev libtool make libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev libwebp-dev libxpm-dev && \
    pecl install redis && \
    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm && \
    docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install gd pdo_pgsql pgsql opcache bcmath gd exif zip && \
    docker-php-ext-enable redis && \
    apk del autoconf g++ libtool make && \
    rm -rf /tmp/*

COPY conf/php.ini /usr/local/etc/php/php.ini
COPY conf/docker.conf /usr/local/etc/php-fpm.d/docker.conf

# install Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    chmod +x /usr/local/bin/composer

WORKDIR /app