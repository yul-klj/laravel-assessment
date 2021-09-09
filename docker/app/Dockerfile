ARG PHP_VERSION
FROM php:${PHP_VERSION}-fpm

###########################################################################
# Core System Installation
###########################

RUN apt-get update \
    && apt install -y \
    gosu \
    libcurl4-openssl-dev \
    libedit-dev \
    libssl-dev \
    libxml2-dev \
    libxtst6 \
    libxrender1 \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libmcrypt-dev \
    libc-client-dev \
    libkrb5-dev \
    pkg-config \
    libfontconfig1 \
    software-properties-common \
    sudo \
    supervisor \
    unzip \
    vim \
    acl \
    cron \
    libzip-dev \
    && pecl install mcrypt-1.0.2 \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install mbstring json xml gd curl intl pcntl zip imap pdo_mysql \
    && docker-php-ext-enable mbstring json xml gd curl intl pcntl zip imap mcrypt pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && gosu nobody true

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer --version=2.0.13 && \
    rm composer-setup.php

###########################################################################
# Non-root user
################

ARG NON_ROOT_UID
ENV NON_ROOT_UID=${NON_ROOT_UID}

ARG APP_PATH
ENV APP_PATH=${APP_PATH}

RUN groupadd -g $NON_ROOT_UID www
RUN useradd -u $NON_ROOT_UID -ms /bin/bash -g www www

USER www

###########################################################################
# Services, Configurations & Aliases
#####################################

COPY ./php/local.ini /usr/local/etc/php/conf.d/local.ini

###########################################################################
# Workdir and Finalize
#######################

WORKDIR $APP_PATH

COPY . $APP_PATH
COPY --chown=www:www . $APP_PATH

CMD ["php-fpm"]