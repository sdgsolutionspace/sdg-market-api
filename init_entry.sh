#!/bin/bash

pushd /var/www/html
args="$@"

## Replace all variable in config file
sed -i "s|DATABASE_URL.*|DATABASE_URL=mysql://${MYSQL_USER}:${MYSQL_PASSWORD}@${MYSQL_HOST}:${MYSQL_PORT}/${MYSQL_DB}|g" .env

sed -i "s|OAUTH_GITHUB_CLIENT_ID.*|OAUTH_GITHUB_CLIENT_ID=${GITHUB_OAUTH_CLIENT_ID}|g" .env
sed -i "s|OAUTH_GITHUB_CLIENT_SECRET.*|OAUTH_GITHUB_CLIENT_SECRET=${GITHUB_OAUTH_CLIENT_SECRET}|g" .env
sed -i "s|GITHUB_PARSER_LOGIN.*|GITHUB_PARSER_LOGIN=${GITHUB_PARSER_LOGIN}|g" .env
sed -i "s|GITHUB_PARSER_PASSWORD.*|GITHUB_PARSER_PASSWORD=${GITHUB_PARSER_PASSWORD}|g" .env
sed -i 's|JWT_PASSPHRASE.*|JWT_PASSPHRASE=foobar|g' .env

if [ ! -z "$args" ] ; then
  php bin/console "$args"
  exit
fi

## We need to configure JWT if they didn't exist already
if [ ! -e config/jwt/public.pem ] ; then
    openssl genrsa -out config/jwt/private.pem -passout pass:foobar -aes256 4096
    openssl rsa -pubout -passin pass:foobar -in config/jwt/private.pem -out config/jwt/public.pem
fi

## Set access for specific folders
chown -R www-data:www-data config/jwt/* var
chmod -R 750 config/jwt/private.pem var

## Wait until database is ready.
maxcounter=45
counter=1
while ! mysql --protocol TCP -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" -h"$MYSQL_HOST" -e "show databases;" > /dev/null 2>&1; do
    sleep 1
    counter=`expr $counter + 1`
    if [ $counter -gt $maxcounter ]; then
        >&2 echo "We have been waiting for MySQL too long already; failing."
        exit 1
    fi;
done

## Do all migrations for now (database must exist already even if empty)
php bin/console doctrine:migrations:migrate -q
php bin/console cache:clear

## Now the Apache service
apache2ctl -DFOREGROUND
