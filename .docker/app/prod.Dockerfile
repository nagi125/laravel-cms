FROM php:8.1-fpm-alpine

ENV TZ Asia/Tokyo
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN set -eux && \
    apk add --update-cache --no-cache openssl git autoconf g++ nodejs npm yarn oniguruma-dev postgresql-dev libtool make libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev libwebp-dev libxpm-dev && \
    pecl install redis && \
    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm && \
    docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install gd pdo_pgsql pgsql opcache bcmath gd exif zip && \
    docker-php-ext-enable redis && \
    apk del autoconf g++ libtool make && \
    rm -rf /tmp/*

COPY .docker/app/conf/php.ini /usr/local/etc/php/php.ini
COPY .docker/app/conf/docker.conf /usr/local/etc/php-fpm.d/docker.conf

# install Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    chmod +x /usr/local/bin/composer

COPY --chown=www-data:www-data ./ /app

WORKDIR /app

# Composer & npm install
RUN php -d memory_limit=-1 /usr/local/bin/composer install && \
    npm install && \
    npm run production

# Cache削除
RUN sh clear-cache-prod.sh

VOLUME /app
