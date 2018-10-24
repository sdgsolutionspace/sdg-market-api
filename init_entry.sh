#!/bin/bash

pushd /var/www/html

## Replace all variable in config file
sed -i "s|DATABASE_URL.*|DATABASE_URL=mysql://${MYSQL_USER}:${MYSQL_PASSWORD}@${MYSQL_HOST}:${MYSQL_PORT}/${MYSQL_DB}|g" .env

sed -i "s|OAUTH_GITHUB_CLIENT_ID.*|OAUTH_GITHUB_CLIENT_ID=${GITHUB_OAUTH_CLIENT_ID}|g" .env
sed -i "s|OAUTH_GITHUB_CLIENT_SECRET.*|OAUTH_GITHUB_CLIENT_SECRET=${GITHUB_OAUTH_CLIENT_SECRET}|g" .env
sed -i "s|GITHUB_PARSER_LOGIN.*|GITHUB_PARSER_LOGIN=${GITHUB_PARSER_LOGIN}|g" .env
sed -i "s|GITHUB_PARSER_PASSWORD.*|GITHUB_PARSER_PASSWORD=${GITHUB_PARSER_PASSWORD}|g" .env


## We need to configure JWT if they didn't exist already
if [ ! -e config/jwt/public.pem ] ; then
    openssl genrsa -out config/jwt/private.pem -passout pass:foobar -aes256 4096
    openssl rsa -pubout -passin pass:foobar -in config/jwt/private.pem -out config/jwt/public.pem
fi

## Set access for specific folders
chown -R www-data:www-data config/jwt/* var
chmod -R 750 config/jwt/private.pem var

## Do all migrations for now (database must exist already even if empty)
php bin/console doctrine:migrations:migrate -q
php bin/console cache:clear

## Now the Apache service
apache2ctl -DFOREGROUND
