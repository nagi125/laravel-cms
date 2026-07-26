FROM composer:2 AS composer

FROM debian:trixie-slim

RUN apt-get update \
    && apt-get install -y --no-install-recommends ca-certificates curl git unzip \
    && curl -fsSL https://packages.sury.org/debsuryorg-archive-keyring.deb -o /tmp/debsuryorg-archive-keyring.deb \
    && dpkg -i /tmp/debsuryorg-archive-keyring.deb \
    && echo "deb [signed-by=/usr/share/keyrings/deb.sury.org-php.gpg] https://packages.sury.org/php/ trixie main" > /etc/apt/sources.list.d/php.list \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
        php8.5-bcmath \
        php8.5-cli \
        php8.5-curl \
        php8.5-fpm \
        php8.5-intl \
        php8.5-mbstring \
        php8.5-pgsql \
        php8.5-xml \
        php8.5-zip \
    && rm -rf /var/lib/apt/lists/* /tmp/debsuryorg-archive-keyring.deb

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY docker/php/conf.d/php.ini-development /etc/php/8.5/fpm/conf.d/99-development.ini
COPY docker/php/conf.d/php.ini-development /etc/php/8.5/cli/conf.d/99-development.ini
COPY docker/php/pool.d/www.conf /etc/php/8.5/fpm/pool.d/www.conf

WORKDIR /var/www/html

CMD ["sh", "-c", "mkdir -p /run/php && exec php-fpm8.5 -F -O"]
