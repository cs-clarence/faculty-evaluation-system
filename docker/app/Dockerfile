ARG ENV=development

FROM imbios/bun-node:latest-current-alpine AS build-support-js
ARG ENV=development
ENV ENV=$ENV
ENV NODE_ENV=$ENV
WORKDIR /var/www/html
COPY ./ ./
RUN bun install
RUN bun run build

FROM composer:latest AS build-support-php
ARG ENV=development
ENV ENV=$ENV
WORKDIR /var/www/html
COPY --from=build-support-js /var/www/html/ ./
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader --ignore-platform-reqs \
    && composer dump-autoload --no-dev --optimize  \
    && php artisan optimize \
    && php artisan config:cache \
    && php artisan event:cache \
    && php artisan route:cache \
    && php artisan view:cache


FROM php:fpm-alpine AS build-main-production

COPY --from=build-support-php /var/www/html/ ./
COPY ./docker/app/fs/etc/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/app/fs/usr/local/etc/php/conf.d /usr/local/etc/php/conf.d
COPY ./docker/app/fs/usr/local/etc/php-fpm.d /usr/local/etc/php-fpm.d
COPY ./docker/app/fs/etc/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

FROM php:fpm-alpine AS build-main-development


FROM build-main-${ENV}
ARG ENV=development
ENV ENV=$ENV
LABEL authors="rencema"

RUN set -ex \
    && apk --no-cache add postgresql-dev postgresql-libs icu-dev nginx supervisor \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo pgsql pdo_pgsql intl \
    && docker-php-ext-enable pdo_pgsql intl opcache \
    && apk del postgresql-dev
CMD [ "supervisord", "-c", "/etc/supervisor/supervisord.conf" ]
