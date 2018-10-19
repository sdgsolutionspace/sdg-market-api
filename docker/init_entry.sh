#!/bin/bash

pushd /var/www/html

## Replace all variable in config file
sed -i "s|baseUrl.*|baseUrl:\'${BASE_URL}\'',|g" environment.prod.ts
sed -i "s|baseUrl.*|baseUrl:\'${BASE_URL}\'',|g" environment.prod.ts

## Now the Apache service
nginx -g "daemon off;"