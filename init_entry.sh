#!/bin/bash

pushd /var/www/html

## Replace all variable in config file
sed -i "s|DATABASE_URL.*|DATABASE_URL=mysql://${MYSQL_USER}:${MYSQL_PASSWORD}@${MYSQL_HOST}:${MYSQL_PORT}/${MYSQL_DB}|g" .env

## We need to configure JWT if they didn't exist already
if [ ! -e config/jwt/public.pem ] ; then
    openssl genrsa -out config/jwt/private.pem -passout pass:foobar -aes256 4096
    openssl rsa -pubout -passin pass:foobar -in config/jwt/private.pem -out config/jwt/public.pem
fi

## Do all migrations for now (database must exist already even if empty)
php bin/console doctrine:migrations:migrate
php bin/console cache:clear

## Now the Apache service
apache2ctl -DFOREGROUND
