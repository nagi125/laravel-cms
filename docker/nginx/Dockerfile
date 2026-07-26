FROM debian:trixie-slim

RUN apt-get update \
    && apt-get install -y --no-install-recommends nginx \
    && rm -rf /var/lib/apt/lists/* \
    && rm -f /etc/nginx/sites-enabled/default

COPY docker/nginx/conf.d/app.conf /etc/nginx/sites-available/app.conf

RUN ln -s /etc/nginx/sites-available/app.conf /etc/nginx/sites-enabled/app.conf

WORKDIR /var/www/html

CMD ["nginx", "-g", "daemon off;"]
