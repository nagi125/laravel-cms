FROM php:7.4-fpm

ENV TZ Asia/Tokyo
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV EXT_REDIS_VERSION=5.1.1 EXT_IGBINARY_VERSION=3.0.1

# install Lib
RUN curl -sL https://deb.nodesource.com/setup_11.x | bash - && \
    apt-get update -qq && \
    apt-get install --no-install-recommends -y libpq-dev libonig-dev libzip-dev zlib1g-dev nodejs git zip unzip && \
    apt-get clean && \
    rm -rf /var/cache/apt && \
    npm install npm@latest -g

# add php,apache-module
RUN docker-php-ext-install mbstring pdo pdo_pgsql zip

# Redis
RUN docker-php-source extract && \
    mkdir -p /usr/src/php/ext/igbinary && \
    curl -fsSL https://github.com/igbinary/igbinary/archive/$EXT_IGBINARY_VERSION.tar.gz | tar xvz -C /usr/src/php/ext/igbinary --strip 1 && \
    docker-php-ext-install igbinary && \
    mkdir -p /usr/src/php/ext/redis && \
    curl -fsSL https://github.com/phpredis/phpredis/archive/$EXT_REDIS_VERSION.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 && \
    docker-php-ext-configure redis --enable-redis-igbinary && \
    docker-php-ext-install redis

# php.conf php-fpm.conf
COPY docker/app/conf/php/php.ini /usr/local/etc/php/php.ini
COPY docker/app/conf/php/docker.conf /usr/local/etc/php-fpm.d/docker.conf

# install Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    chmod +x /usr/local/bin/composer

COPY . /app

WORKDIR /app
