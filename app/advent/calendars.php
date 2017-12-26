<?php

use Afsy\Advent\AdventArticle;
use Afsy\Advent\AdventCalendar;

$articles[2017] = function () {
    $calendar = new AdventCalendar(2017);
    $calendar->add(new AdventArticle('24', 'Unpack tes packs', 'unpack-tes-packs'));
    $calendar->add(new AdventArticle('23', "Comment l'architecture hexagonale nous a facilité la vie !", 'comment-l-architecture-hexagonale-nous-a-facilite-la-vie'));
    $calendar->add(new AdventArticle('22', 'Log me tender', 'log-me-tender'));
    $calendar->add(new AdventArticle('21', 'Symfony et WebSockets', 'symfony-et-websockets'));
    $calendar->add(new AdventArticle('20', 'Intégrer Elasticsearch 6 dans votre application Symfony 4', 'elasticsearch-6-et-symfony-4'));
    $calendar->add(new AdventArticle('19', "Le composant Workflow par l'exemple", 'composant-workflow-par-l-exemple'));
    $calendar->add(new AdventArticle('18', 'Structurer sa démarche de test', 'structurer-sa-demarche-de-test'));
    $calendar->add(new AdventArticle('17', 'Bien démarrer avec Symfony et React', 'bien-demarrer-avec-symfony-et-react'));
    $calendar->add(new AdventArticle('16', 'Des astuces sécurité avec Symfony', 'des-astuces-de-securite-avec-symfony'));
    $calendar->add(new AdventArticle('15', 'Comment ne pas (trop) exposer Symfony !', 'comment-ne-pas-trop-exposer-symfony'));
    $calendar->add(new AdventArticle('14', 'Une API GraphQL avec Symfony', 'une-api-graphql-avec-symfony'));
    $calendar->add(new AdventArticle('13', 'Créer une application cross-platform avec Symfony Flex, Webpack Encore et Phonegap', 'creer-une-application-cross-platform'));
    $calendar->add(new AdventArticle('12', 'Serverless, PHP et Symfony', 'serverless-php-et-symfony'));
    $calendar->add(new AdventArticle('11', 'Non à la Doctrine, découvrez Pomm et (re-)découvrez PostgreSQL', 'non-a-la-doctrine-decouvrez-pomm-et-re-decouvrez-postgresql'));
    $calendar->add(new AdventArticle('10', 'Mettre les mains dans un projet existant', 'comment-mettre-les-mains-dans-un-projet-existant'));
    $calendar->add(new AdventArticle('09', 'Détournement de Twig pour des prototypages d\'application', 'utilisation-de-twig-en-phase-de-prototypage'));
    $calendar->add(new AdventArticle('08', 'Symfony Flex : la nouvelle façon de développer avec Symfony', 'symfony-flex-la-nouvelle-facon-de-developper-avec-symfony'));
    $calendar->add(new AdventArticle('07', 'Découpler Form et votre modèle', 'decoupler-form-et-votre-modele'));
    $calendar->add(new AdventArticle('06', 'HEADLESS IS MORE (sans-tête est plus)', 'headless-is-more'));
    $calendar->add(new AdventArticle('05', 'Comment déboguer une erreur « segfault » de PHP', 'deboguer-php-avec-gdb'));
    $calendar->add(new AdventArticle('04', 'Tagger son application', 'tagger-son-application'));
    $calendar->add(new AdventArticle('03', 'Déployer un projet Symfony 4 avec Flex sur Heroku', 'deployer-un-projet-symfony-flex-sur-heroku'));
    $calendar->add(new AdventArticle('02', 'La nouvelle configuration par défaut de Symfony 4', 'la-nouvelle-configuration-par-defaut-de-symfony4'));
    $calendar->add(new AdventArticle('01', 'EasyAdminBundle : l\'arrière-guichet easy peasy', 'easyadminbundle-l-arriere-guichet-easy-peasy'));

    return $calendar;
};

$articles[2013] = function () {

};

return $articles;