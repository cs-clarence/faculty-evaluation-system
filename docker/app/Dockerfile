ARG ENV=development

FROM composer:latest AS build-support-php
ARG ENV
ENV ENV=$ENV
WORKDIR /var/www/html
COPY ./ ./
RUN git config --global --add safe.directory /var/www/html \
    && composer install --no-dev --no-interaction --no-progress --optimize-autoloader --ignore-platform-reqs \
    && composer dump-autoload --no-dev --optimize  \
    && php artisan optimize \
    && php artisan config:cache \
    && php artisan event:cache \
    && php artisan route:cache \
    && php artisan view:cache

FROM imbios/bun-node:latest-current-alpine AS build-support-js
ARG ENV
ENV ENV=$ENV
ENV NODE_ENV=$ENV
WORKDIR /var/www/html
COPY --from=build-support-php /var/www/html/ ./
RUN bun install \
    && bun run build \
    && rm -rf ./node_modules \
    && rm -rf ./public/hot

FROM build-support-js AS build-support

FROM php:fpm-alpine AS build-main-base
ARG ENV
ENV ENV=$ENV

RUN set -ex \
    && apk --no-cache add postgresql-dev postgresql-libs icu-dev nginx supervisor libzip-dev zip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo pgsql pdo_pgsql intl zip \
    && docker-php-ext-enable pdo_pgsql intl opcache \
    && apk del postgresql-dev

FROM build-main-base AS build-main-production

COPY --from=build-support /var/www/html/ ./
COPY ./docker/app/fs/etc/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/app/fs/usr/local/etc/php/conf.d /usr/local/etc/php/conf.d
COPY ./docker/app/fs/usr/local/etc/php-fpm.d /usr/local/etc/php-fpm.d
COPY ./docker/app/fs/etc/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

FROM build-main-base AS build-main-development


FROM build-main-${ENV}
RUN  if [ "$ENV" = "development" ]; then echo "Building in development mode"; else chmod -R 777 /var/www/html; fi
CMD [ "supervisord", "-c", "/etc/supervisor/supervisord.conf" ]
