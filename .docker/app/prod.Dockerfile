FROM php:8.1-fpm-buster

ENV TZ Asia/Tokyo
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_PROCESS_TIMEOUT 0
ENV COMPOSER_NO_INTERACTION 1

# install Lib
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - && \
    apt-get update -qq && \
    apt-get install --no-install-recommends -y libpq-dev libonig-dev libxml2-dev nodejs git zip unzip && \
    apt-get install --no-install-recommends -y zlib1g-dev libfreetype6-dev libpng-dev libjpeg62-turbo-dev libwebp-dev libxpm-dev && \
    apt-get clean && \
    rm -rf /var/cache/apt && \
    npm install npm@latest -g

# add extension
RUN docker-php-ext-install mbstring pdo pdo_pgsql && \
    docker-php-ext-enable mbstring

# add opcache
RUN docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install opcache

# add gd
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ --with-webp && \
    docker-php-ext-install -j$(nproc) gd

# setting conf files
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
COPY .docker/app/conf/php.ini /usr/local/etc/php/conf.d/php.ini
COPY .docker/app/conf/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY .docker/app/conf/docker.conf /usr/local/etc/php-fpm.d/docker.conf

# install Composer
COPY --from=composer/composer /usr/bin/composer /usr/bin/composer

COPY --chown=www-data:www-data ./ /app

WORKDIR /app

# Composer & npm install
RUN php -d memory_limit=-1 /usr/bin/composer install && \
    npm install && \
    npm run production

VOLUME /app
