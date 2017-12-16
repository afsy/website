Installation
============

```
cp app/config/parameters.yml-dist app/config/parameters.yml
# configure app/config/parameters.yml
composer install
bin/console doctrine:database:create
bin/console doctrine:schema:update --force
bin/console doctrine:fixtures:load
bin/console assets:install web
```
