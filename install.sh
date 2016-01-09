#!/bin/bash

clear
echo "Choose action :"
echo "1 - INSTALL"
echo "2 - UPDATE"
echo "3 - UPDATE DATABASE"
echo "4 - FIXTURES"
echo "5 - EXIT"

read Key

case "$Key" in
1) echo "install..."
    curl -sS https://getcomposer.org/installer | php
    php composer.phar install
    rm composer.phar
    npm install
    ./node_modules/.bin/bower install
    ./node_modules/.bin/gulp
    php app/console doctrine:database:create
    php app/console doctrine:schema:update --force
;;
2) echo "update..."
    curl -sS https://getcomposer.org/installer | php
    php composer.phar update
    rm composer.phar
;;
3) echo "update database..."
    php app/console doctrine:database:drop --force
    php app/console doctrine:database:create
    php app/console doctrine:schema:update --force
;;
4) echo "fixtures..."
    php app/console doctrine:database:drop --force
    php app/console doctrine:database:create
    php app/console doctrine:schema:update --force
    php app/console hautelook_alice:doctrine:fixtures:load --no-interaction
;;
5) exit 0
;;
esac
