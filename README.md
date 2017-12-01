# AFSY Website

AFSY stands for "Association Francophone des utilisateurs de Symfony".
See http://afsy.fr/ for more informations.

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
