# Set the base image for subsequent instructions
FROM php:8.1

# Update packages
RUN apt-get update

# Install PHP and composer dependencies
RUN apt-get install -qq \
 git \
 curl libmcrypt-dev libjpeg-dev libpng-dev libfreetype6-dev libbz2-dev argon2 libargon2-0 libargon2-0-dev openssl libonig-dev libxml2 libxml2-dev zip unzip libzip-dev \
libsodium-dev

# Clear out the local repository of retrieved package files
RUN apt-get clean

# Install needed extensions
# Here you can install any other extension that you need during the test and deployment process
RUN docker-php-ext-install bcmath ctype json mbstring pdo xml tokenizer pdo_mysql zip fileinfo session sodium

#add openssl

# Install Composer
RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


ENV PATH="${PATH}:/root/.composer/vendor/bin"
ENV COMPOSER_ALLOW_SUPERUSER=1


# Install Laravel Envoy
RUN composer global require "laravel/envoy"
