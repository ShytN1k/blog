#!/bin/bash

clear
echo "Choose action :"
echo "1 - INSTALL"
echo "2 - UPDATE"
echo "3 - CREATE DATABASE"
echo "4 - UPDATE DATABASE"
echo "5 - CREATE FIXTURES"
echo "6 - UPDATE FIXTURES"
echo "7 - EXIT"

read Key

case "$Key" in
1) echo "installing..."
    curl -sS https://getcomposer.org/installer | php
    php composer.phar install
    rm composer.phar
    npm install
    ./node_modules/.bin/bower install
    ./node_modules/.bin/gulp
    php app/console doctrine:database:create
    php app/console doctrine:schema:update --force
;;
2) echo "updating..."
    curl -sS https://getcomposer.org/installer | php
    php composer.phar update
    rm composer.phar
;;
3) echo "creating database..."
    php app/console doctrine:database:create
    php app/console doctrine:schema:update --force
;;
4) echo "updating database..."
    php app/console doctrine:database:drop --force
    php app/console doctrine:database:create
    php app/console doctrine:schema:update --force
;;
5) echo "creating fixtures..."
    php app/console doctrine:database:create
    php app/console doctrine:schema:update --force
    php app/console hautelook_alice:doctrine:fixtures:load --no-interaction
;;
6) echo "updating fixtures..."
    php app/console doctrine:database:drop --force
    php app/console doctrine:database:create
    php app/console doctrine:schema:update --force
    php app/console hautelook_alice:doctrine:fixtures:load --no-interaction
;;
7) exit 0
;;
esac
