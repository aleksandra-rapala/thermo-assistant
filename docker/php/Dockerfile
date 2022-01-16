FROM php:7.4.3-fpm-alpine3.11

ENV RUN_DEPS \
    zlib \
    libzip \
    libpng \
    libjpeg-turbo \
    postgresql-libs

ENV BUILD_DEPS \
    zlib-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    postgresql-dev

ENV PHP_EXTENSIONS \
    opcache \
    zip \
    gd \
    bcmath \
    pgsql \
    pdo_pgsql

RUN apk add --no-cache --virtual .build-deps $BUILD_DEPS \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j "$(nproc)" $PHP_EXTENSIONS \
    && apk del .build-deps

RUN apk add --no-cache --virtual .run-deps $RUN_DEPS
RUN wget https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64
RUN chmod +x mhsendmail_linux_amd64
RUN mv mhsendmail_linux_amd64 /usr/bin/mhsendmail
RUN echo "sendmail_path = '/usr/bin/mhsendmail --smtp-addr=mailhog:1025 --from=oferty@thermo-assistant'" > /usr/local/etc/php/php.ini

# Copy the application code
COPY . /app

VOLUME ["/app"]
