Installation
============

```
cp app/config/parameters.yml-dist app/config/parameters.yml
# configure app/config/parameters.yml
composer install
app/console doctrine:database:create
app/console doctrine:schema:update --force
app/console doctrine:fixtures:load
```
