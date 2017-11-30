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

How to add your own events on the website?
==========================================

First you need to contact one of the administrators with your email and sensiolabs connect id: you may fill an issue to contact one of them.

Then, you will be able to log in into the Afsy website Back Office: http://afsy.fr/admin/. You can register new Articles and Authors.
When your article is done and published, spread the world on social networks!
