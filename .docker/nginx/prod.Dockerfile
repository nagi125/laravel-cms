FROM nginx:1.22

ENV TZ Asia/Tokyo

COPY .docker/nginx/conf/prod.conf /etc/nginx/conf.d/default.conf

VOLUME /app
