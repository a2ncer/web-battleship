FROM nginx:alpine

ARG PHP_UPSTREAM=php-fpm

RUN apk update \
    && apk upgrade \
    && apk add --no-cache bash \
    && adduser -D -H -u 1000 -s /bin/bash www-data \
    && rm /etc/nginx/conf.d/default.conf \
    && echo "upstream php-upstream { server ${PHP_UPSTREAM}:9000; }" > /etc/nginx/conf.d/upstream.conf


COPY sites/*.conf /etc/nginx/sites_configs/
COPY sites/default.conf /etc/nginx/sites-available/default.conf

ARG SSL_ENABLE=false
ENV SSL_ENABLE ${SSL_ENABLE}
ARG APP_DOMAIN=default
ENV APP_DOMAIN ${APP_DOMAIN}
RUN if [ ${SSL_ENABLE} = true ]; then \
    cp -f /etc/nginx/sites_configs/default_ssl.conf /etc/nginx/sites-available/default.conf \
    && sed -i "s/{APP_DOMAIN}/${APP_DOMAIN}/g" /etc/nginx/sites-available/default.conf \
;fi

ADD nginx.conf /etc/nginx/

CMD ["nginx"]

EXPOSE 80 443
