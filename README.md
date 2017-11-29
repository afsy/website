AFSY Website
============

AFSY stands for "Association Francophone des utilisateurs de Symfony". See http://afsy.fr/ for more informations.

If you want to test the current version (which requires php5.3) :

```bash
$ docker run -d -p 8000:80 -v [YOUR_LOCAL_PROJECT_PATH]:/var/app --name afsy phpmentors/symfony-app:php53
$ docker exec -it afsy /bin/bash
root@c5130f5eef1f:~# cd /var/app
root@c5130f5eef1f:~# ./composer.phar install
```

and `localhost:8000/app_dev.php` should work.

/* Update CSS
---------------------------------------------------
    1. Install compass

        sudo gem install compass

    2. Run Compass

        compass watch
