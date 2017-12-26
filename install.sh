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
rm -rf var/cache/* var/logs/* web/record/*

echo "- reinstall bundle assets"
./bin/console assets:install --symlink --env=dev

echo "- drop database"
./bin/console doctrine:database:drop --force --env=dev || true

echo "- create database"
./bin/console doctrine:database:create --env=dev

echo "- create schema"
./bin/console doctrine:schema:update --force --env=dev

echo "- load fixtures"
./bin/console doctrine:fixtures:load --no-interaction --env=dev

echo "- dump production assets"
./bin/console assetic:dump --env=dev --no-debug
