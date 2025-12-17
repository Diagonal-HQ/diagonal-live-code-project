ARG BASE_IMAGE=php:8.4-fpm-alpine
FROM ${BASE_IMAGE}

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Use /var/www/html as the working directory within the container
RUN mkdir -p /var/www/html
WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install packages for Alpine
RUN apk --update add \
    wget \
    curl \
    gd \
    build-base \
    libxml2-dev \
    pcre-dev \
    zlib-dev \
    autoconf \
    oniguruma-dev \
    openssl \
    openssl-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    jpeg-dev \
    libpng-dev \
    imagemagick-dev \
    imagemagick \
    postgresql-dev \
    libzip-dev \
    gettext-dev \
    libxslt-dev \
    libgcrypt-dev \
    linux-headers && \
    rm /var/cache/apk/*

RUN install-php-extensions redis
RUN install-php-extensions gd
RUN install-php-extensions xdebug

# Download, patch, build, and install imagick manually:
RUN pecl download imagick \
    && tar -xzf imagick-*.tgz \
    && cd imagick-* \
    && phpize \
    && ./configure CPPFLAGS='-Dphp_strtolower=zend_str_tolower' \
    && make -j$(nproc) \
    && make install \
    && cd .. \
    && rm -rf imagick-* \
    && docker-php-ext-enable imagick

# Install swoole
RUN pecl install swoole \ 
    && docker-php-ext-enable swoole

RUN docker-php-ext-install \
    mbstring \
    pdo \
    xml \
    pcntl \
    bcmath \
    pdo_pgsql \
    zip \
    intl \
    gettext \
    soap \
    sockets \
    xsl

USER www-data