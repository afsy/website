#!/bin/bash
set -e
cd "`dirname "$0"`"

if [ ! -f composer.phar ]; then
    echo "- download composer"
    curl -s http://getcomposer.org/installer | php
fi

if [ ! -d vendor ]; then
    echo "- install dependencies"
    php composer.phar install
fi

echo "- clean cache"
rm -rf app/cache/* app/logs/* web/record/*

echo "- reinstall bundle assets"
./app/console assets:install --symlink

echo "- drop database"
./app/console doctrine:database:drop --force || true

echo "- create database"
./app/console doctrine:database:create

echo "- create schema"
./app/console doctrine:schema:create

echo "- load fixtures"
./app/console doctrine:fixtures:load --append

echo "- dump production assets"
./app/console assetic:dump --env=prod --no-debug
