# AFSY Website

AFSY stands for "Association Francophone des utilisateurs de Symfony".
See http://afsy.fr/ for more informations.

## How to add your own events on the website?

First you need to contact one of the administrators with your email and sensiolabs connect id: you may fill an issue to contact one of them.

Then, you will be able to log in into the Afsy website Back Office: http://afsy.fr/admin/. You can register new Articles and Authors.
When your article is done and published, spread the world on social networks!

## Install and run

If you want to test the current version (which requires php7) :

```bash
$ docker run -d -p 8000:80 -v [YOUR_LOCAL_PROJECT_PATH]:/var/app --name afsy phpmentors/symfony-app:php71
$ docker exec -it afsy /bin/bash
root@c5130f5eef1f:~# cd /var/app
root@c5130f5eef1f:~# ./composer.phar install
```

and `localhost:8000/app_dev.php` should work.

## Howto update CSS styles
---------------------------------------------------
    1. Install compass

        sudo gem install compass

    2. Run Compass

        compass watch

## Deployments

Deployments are made using [Fabric](https://get.fabric.io/), using a simple
basic script.

 * copy `bin/fabfile.py-dist` to `bin/fabfile.py`
 * set the preprod and prod SSh servers and paths
 * ensure Fabric is installed on your system
 * run the following command:

```sh
$ fab -f bin/fabfile.py -R preprod deploy
```

This will deploy the `master` branch in the `preprod` env, run composer
install, clear the cache and warmup it.