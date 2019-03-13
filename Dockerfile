FROM debian:stretch

MAINTAINER Olivier Bitsch
WORKDIR /var/www/html
ENV PHP_VER 7.2

## Defaults values for the config
ENV MYSQL_PORT=3306
ENV MYSQL_HOST=db
ENV MYSQL_DB=ghtrading
ENV MYSQL_USER=ghtrading
ENV MYSQL_PASSWORD=ghtrading
ENV GITHUB_PARSER_LOGIN=
ENV GITHUB_PARSER_PASSWORD=
ENV GITHUB_OAUTH_CLIENT_ID=
ENV GITHUB_OAUTH_CLIENT_SECRET=


## Non interactive Debian package installation
ENV DEBIAN_FRONTEND noninteractive

## Let refresh first the Debian repo
RUN apt-get update \
    && apt-get -y install wget \
    gnupg \
    apt-transport-https \
    openssl

## Adding Sury (php backports) repository
RUN echo "deb https://packages.sury.org/php stretch main" > /etc/apt/sources.list.d/sury.list \
    && wget -O - https://packages.sury.org/php/apt.gpg | apt-key add -

## Installing dependancies
RUN apt-get update \
    && apt-get -y install \
    libapache2-mod-php${PHP_VER} \
    composer \
    php${PHP_VER}-mbstring \
    php${PHP_VER}-dom \
    php${PHP_VER}-xml \
    php${PHP_VER}-zip \
    php${PHP_VER}-curl \
    php${PHP_VER}-json \
    php${PHP_VER}-mysql


## Copy the entire application
RUN rm /var/www/html/*
COPY . /var/www/html
RUN mv .env.dist .env
RUN chown -R www-data /var/www/
RUN chmod 777 /var/www/html/var/cache/prod/

## Install composer dependancies
#USER www-data
RUN composer install

## Define data location for JWT keys
VOLUME /var/www/html/config/jwt/

## Override default configuration values
RUN sed -i 's/dev/prod/g' .env

## Do a proper Apache2 configuration
RUN a2enmod rewrite
RUN rm /etc/apache2/sites-enabled/*.conf
COPY apache.conf /etc/apache2/sites-enabled/ghtrading.conf

## Define the port used by Apache
EXPOSE 80

## Prepare the proper init script
COPY init_entry.sh /init_entry.sh
RUN chmod +x /init_entry.sh
ENTRYPOINT [ "/init_entry.sh" ]
