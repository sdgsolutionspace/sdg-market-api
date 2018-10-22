FROM debian:stretch

MAINTAINER Olivier Bitsch
WORKDIR /var/www/html
ENV PHP_VER 7.2

## Defaults values for the config
ENV BASE_URL=http://localhost
ENV BASE_API_URL=http://localhost:8000/api

## Non interactive Debian package installation
ENV DEBIAN_FRONTEND noninteractive

## Let refresh first the Debian repo
RUN apt-get update \
    && apt-get -y install wget \
    gnupg \
    apt-transport-https \
    openssl

## Adding Sury (php backports) repository
RUN wget -qO- https://deb.nodesource.com/setup_10.x | bash -

## Installing dependancies
RUN apt-get update \
    && apt-get -y install \
    nodejs \
    nginx

## Copy the entire application
RUN rm /var/www/html/* && mkdir /tmp/build
COPY . /tmp/build

## Install npm dependancies
#USER www-data
RUN cd /tmp/build && npm install

## Build with Angular
RUN cd /tmp/build && node_modules/.bin/ng build #--prod

## Finally keep only static files and cleanup
RUN cp -a /tmp/build/dist/github-trading/* /var/www/html && rm -rf /tmp/build

## Define the port used by Apache
EXPOSE 80

## Prepare the proper init script
COPY docker/init_entry.sh /init_entry.sh
RUN chmod +x /init_entry.sh
ENTRYPOINT [ "/init_entry.sh" ]
