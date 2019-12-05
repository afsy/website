<?php

namespace App\Controller;

use Carbon\Carbon;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class AventController extends AbstractController
{
    private $twig;
    protected $futureEnabled;
    protected $enabledYears = array('2013', '2017', '2019');
    private $now;

    public function __construct(Environment $twig, $env)
    {
        $this->twig = $twig;
        if('prod' != $env) {
            $this->futureEnabled = true;
        }
        else {
            $this->futureEnabled = false;
        }

        $this->now = Carbon::now(new DateTimeZone('Europe/Paris'));
    }

    protected $slugs = array(
        // Last articles top
        2013 => array(
            '24-avancee-symfony-2-5' => 'Avent/2013/day_24.html.twig',
            '23-sensibilisation-ddd' => 'Avent/2013/day_23.html.twig',
            '22-formulaires-Symfony2-et-data-binding' => 'Avent/2013/day_22.html.twig',
            '21-rabbitmq-et-Symfony2-traitements-asynchrones' => 'Avent/2013/day_21.html.twig',
            '20-elasticsearch-dans-votre-Symfony2' => 'Avent/2013/day_20.html.twig',
            '19-bien-penser-ses-commandes-Symfony' => 'Avent/2013/day_19.html.twig',
            '18-capifony-et-capistrano' => 'Avent/2013/day_18.html.twig',
            '17-commencer-a-adopter-Devops-sur-ses-projets-Symfony' => 'Avent/2013/day_17.html.twig',
            '16-symfony-et-angularjs-tips' => 'Avent/2013/day_16.html.twig',
            '15-etendre-behat-pour-y-mettre-son-metier' => 'Avent/2013/day_15.html.twig',
            '14-votre-application-est-lente-pensez-a-optimiser-doctrine' => 'Avent/2013/day_14.html.twig',
            '13-jouons-a-cache-cache-avec-http' => 'Avent/2013/day_13.html.twig',
            '12-et-si-on-mettait-un-peu-de-symfony-dans-javascript' => 'Avent/2013/day_12.html.twig',
            '11-les-cms-autour-de-symfony2' => 'Avent/2013/day_11.html.twig',
            '10-les-meta-donnees-doctrine' => 'Avent/2013/day_10.html.twig',
            '09-symfony-1-5-notre-fork-de-symfony1' => 'Avent/2013/day_09.html.twig',
            '08-utilisation-avancee-du-composant-sonfig-de-symfony2' => 'Avent/2013/day_08.html.twig',
            '07-symfony-framework-MVC-javascript' => 'Avent/2013/day_07.html.twig',
            '06-best-practices-pour-vos-apis-rest-http-avec-symfony2' => 'Avent/2013/day_06.html.twig',
            '05-conteneur-de-services-creer-ses-propres-tags' => 'Avent/2013/day_05.html.twig',
            '04-utilisez-apydatagridbundle-pour-des-listings-impeccables' => 'Avent/2013/day_04.html.twig',
            '03-faites-le-plein-de-propel' => 'Avent/2013/day_03.html.twig',
            '02-principes-stupid-solid-poo' => 'Avent/2013/day_02.html.twig',
            '01-presentation-afsy' => 'Avent/2013/day_01.html.twig',
        ),
        2017 => array(
            '24-unpack-tes-packs' => 'Avent/2017/day_24.html.twig',
            '23-comment-l-architecture-hexagonale-nous-a-facilite-la-vie' => 'Avent/2017/day_23.html.twig',
            '22-log-me-tender' => 'Avent/2017/day_22.html.twig',
            '21-symfony-et-websockets' => 'Avent/2017/day_21.html.twig',
            '20-elasticsearch-6-et-symfony-4' => 'Avent/2017/day_20.html.twig',
            '19-composant-workflow-par-l-exemple' => 'Avent/2017/day_19.html.twig',
            '18-structurer-sa-demarche-de-test' => 'Avent/2017/day_18.html.twig',
            '17-bien-demarrer-avec-symfony-et-react' => 'Avent/2017/day_17.html.twig',
            '16-des-astuces-de-securite-avec-symfony' => 'Avent/2017/day_16.html.twig',
            '15-comment-ne-pas-trop-exposer-symfony' => 'Avent/2017/day_15.html.twig',
            '14-une-api-graphql-avec-symfony' => 'Avent/2017/day_14.html.twig',
            '13-creer-une-application-cross-platform' => 'Avent/2017/day_13.html.twig',
            '12-serverless-php-et-symfony' => 'Avent/2017/day_12.html.twig',
            '11-non-a-la-doctrine-decouvrez-pomm-et-re-decouvrez-postgresql' => 'Avent/2017/day_11.html.twig',
            '10-comment-mettre-les-mains-dans-un-projet-existant' => 'Avent/2017/day_10.html.twig',
            '09-utilisation-de-twig-en-phase-de-prototypage' => 'Avent/2017/day_09.html.twig',
            '08-symfony-flex-la-nouvelle-facon-de-developper-avec-symfony' => 'Avent/2017/day_08.html.twig',
            '07-decoupler-form-et-votre-modele' => 'Avent/2017/day_07.html.twig',
            '06-headless-is-more' => 'Avent/2017/day_06.html.twig',
            '05-deboguer-php-avec-gdb' => 'Avent/2017/day_05.html.twig',
            '04-tagger-son-application' => 'Avent/2017/day_04.html.twig',
            '03-deployer-un-projet-symfony-flex-sur-heroku' => 'Avent/2017/day_03.html.twig',
            '02-la-nouvelle-configuration-par-defaut-de-symfony4' => 'Avent/2017/day_02.html.twig',
            '01-easyadminbundle-l-arriere-guichet-easy-peasy' => 'Avent/2017/day_01.html.twig',
        ),
        2019 => array(
            '19-comment-tester-du-code-non-deterministe' => 'Avent/2019/day_19.html.twig',
            '18-rex-implementation-bundle-symfony' => 'Avent/2019/day_18.html.twig',
            '17-tirer-profit-des-bundles-symfony' => 'Avent/2019/day_17.html.twig',
            '16-rex-sylius-le-framework-e-commerce' => 'Avent/2019/day_16.html.twig',
            '15-api-jane' => 'Avent/2019/day_15.html.twig',
            '14-comprendre-symfony-avec-eventdispatcher' => 'Avent/2019/day_14.html.twig',
            '13-plongee-au-coeur-du-composant-messenger' => 'Avent/2019/day_13.html.twig',
            '12-workflow-as-middleware' => 'Avent/2019/day_12.html.twig',
            '11-images-responsives-avec-symfony' => 'Avent/2019/day_11.html.twig',
            '10-gerer-les-erreurs-en-prod' => 'Avent/2019/day_10.html.twig',
            '09-konmariser-son-code' => 'Avent/2019/day_09.html.twig',
            '08-rex-symfony-2-a-4-back' => 'Avent/2019/day_08.html.twig',
            '07-rex-symfony-2-a-4-front' => 'Avent/2019/day_07.html.twig',
            '06-php-asynchrone' => 'Avent/2019/day_06.html.twig',
            '05-structurez-votre-monolithe' => 'Avent/2019/day_05.html.twig',
            '04-ne-me-parlez-plus-de-manager' => 'Avent/2019/day_04.html.twig',
            '03-symfony-et-test-technique' => 'Avent/2019/day_03.html.twig',
            '02-symfony4-vers-symfony5' => 'Avent/2019/day_02.html.twig',
            '01-10-astuces-incroyables-sur-symfony' => 'Avent/2019/day_01.html.twig',
        )
    );

    /**
     * @Route("/avent/{year}", name="avent")
     */
    public function indexAction($year = null)
    {
        // redirect to the most recent year
        if (null === $year) {
            $years = array_keys($this->slugs);
            krsort($years);

            return $this->redirectToRoute('avent', array('year' => current($years)));
        }

        return $this->render('Avent/year.html.twig', array(
            'year' => $year,
            'days' => $this->loadYearData($year),
        ));
    }

    /**
     * @Route("/avent/{year}/feed.atom", name="avent_feed_atom", defaults={"_format": "atom"})
     */
    public function feedAction($year)
    {
        return $this->render('Avent/year.atom.twig', array(
            'year' => $year,
            'days' => $this->loadYearData($year),
        ));
    }

    /**
     * @Route("/avent/{year}/{slug}", name="avent_day")
     */
    public function dayAction($year, $slug)
    {
        $this->validateDate($year);
        $yearSlugs = $this->slugs[$year];

        if (!isset($yearSlugs[$slug])) {
            throw $this->createNotFoundException(sprintf('Unrecognized slug for year "%s": %s.', $year, $slug));
        }

        // Thanks, all articles start with '0X'
        $day = (int) $slug;
        $this->validateDate($year, $day);

        list($prev_template, $prev_slug) = $this->getPrev($yearSlugs, $slug, $year);

        // For the next we check the date + 1
        list($next_template, $next_slug) = $this->getNext($yearSlugs, $slug, $year);

        return $this->render($yearSlugs[$slug], array(
            'day' => $day,
            'prev' => $prev_template ? $this->twig->loadTemplate($prev_template)->renderBlock('article_title', []) : null,
            'prev_slug' => $prev_slug,
            'next' => $next_template ? $this->twig->loadTemplate($next_template)->renderBlock('article_title', []) : null,
            'next_slug' => $next_slug,
            'slug' => $slug,
        ));
    }

    private function getNext($slugs, $current_slug, $year)
    {
        $day = (int) $current_slug;

        try {
            $this->validateDate($year, $day + 1);
        } catch (NotFoundHttpException $e) {
            return array(false, false);
        }

        // Get the next article, tricky! :)
        reset($slugs);
        while (null !== key($slugs) && key($slugs) !== $current_slug) {
            next($slugs);
        }

        // we use `prev` for next slug because they are in reverse order in the array
        $template = prev($slugs);
        $slug = key($slugs);

        if ($slug === $current_slug) {
            return array(false, false);
        }

        if ($this->futureEnabled || $year < $this->now->format('Y') || 12 == $this->now->format('m') && $day <= $this->now->format('d')) {
            return array($template, $slug);
        }

        return array(false, false);
    }

    private function getPrev($slugs, $current_slug, $year)
    {
        // Get the previous article, tricky! :)
        reset($slugs);
        while (null !== key($slugs) && key($slugs) !== $current_slug) {
            next($slugs);
        }

        // we use `next` for previous slug because they are in reverse order in the array
        $template = next($slugs);
        $slug = key($slugs);
        $day = (int) $slug;
        if ($this->futureEnabled || $year < $this->now->format('Y') || 12 == $this->now->format('m') && $day <= $this->now->format('d')) {
            return array($template, $slug);
        }

        return array(false, false);
    }

    private function loadYearData($year)
    {
        $this->validateDate($year);

        $twig = $this->get('twig');

        $kept = array();

        foreach ($this->slugs[$year] as $slug => $template) {
            // Thanks, all articles start with '0X'
            $day = (int) $slug;

            if ($this->futureEnabled || $year < $this->now->format('Y') || 12 == $this->now->format('m') && $day <= $this->now->format('d')) {
                $kept[$slug] = $template;
            }
        }

        return array_map(function ($template) use ($twig) {
            return $twig->loadTemplate($template);
        }, $kept);
    }

    private function validateDate($year, $day = null)
    {
        if (!in_array($year, $this->enabledYears)) {
            throw $this->createNotFoundException(sprintf('Year "%s" is not yet accessible.', $year));
        }

        if ($year < date('Y')) {
            return; // no check if past year
        }

        if (null === $day) {
            return;
        }

        if ($this->futureEnabled) {
            return;
        }

        if ($this->now->format('m') < 12 || $day > $this->now->format('d')) {
            throw $this->createNotFoundException(sprintf('Day "%s" is not yet accessible (now: %s).', $day.'/12', date('d/m')));
        }
    }
}
