# AFSY Website

AFSY stands for "Association Francophone des utilisateurs de Symfony".
See https://afsy.fr/ for more informations.

## How to add your own events on the website?

First you need to contact one of the administrators with your email and sensiolabs connect id: you may fill an issue to contact one of them.

Then, you will be able to log in into the Afsy website Back Office: https://afsy.fr/admin/. You can register new Articles and Authors.
When your article is done and published, spread the world on social networks!

## Install and run

Requirements:
- PHP 8.0 / Composer
- Symfony cli
- Docker
- NodeJS / Yarn

Run it locally:

```bash
$ symfony composer install
$ docker-compose up -d
$ symfony console doctrine:schema:create
$ symfony console doctrine:fixtures:load
$ yarn install
$ yarn dev
$ symfony serve
```

## How-to update CSS styles

Install assets and run yarn:

```bash
$ bin/console assets:i
$ yarn watch
```

## Deployments

You need rights to the SymfonyCloud account and deploy with the following command.

```sh
$ symfony deploy
```

See SymfonyCloud documentation for more. (note that you can create environment by pull request on demand).
