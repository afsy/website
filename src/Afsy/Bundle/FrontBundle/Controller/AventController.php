<?php

namespace Afsy\Bundle\FrontBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AventController extends Controller
{
    protected $futureEnabled = false; // useful for dev
    protected $enabledYears = array('2013', '2017');

    protected $slugs = array(
        // Last articles top
        2013 => array(
            '24-avancee-symfony-2-5'                                      => 'AfsyFrontBundle:Avent:day_2013_24.html.twig',
            '23-sensibilisation-ddd'                                      => 'AfsyFrontBundle:Avent:day_2013_23.html.twig',
            '22-formulaires-Symfony2-et-data-binding'                     => 'AfsyFrontBundle:Avent:day_2013_22.html.twig',
            '21-rabbitmq-et-Symfony2-traitements-asynchrones'             => 'AfsyFrontBundle:Avent:day_2013_21.html.twig',
            '20-elasticsearch-dans-votre-Symfony2'                        => 'AfsyFrontBundle:Avent:day_2013_20.html.twig',
            '19-bien-penser-ses-commandes-Symfony'                        => 'AfsyFrontBundle:Avent:day_2013_19.html.twig',
            '18-capifony-et-capistrano'                                   => 'AfsyFrontBundle:Avent:day_2013_18.html.twig',
            '17-commencer-a-adopter-Devops-sur-ses-projets-Symfony'       => 'AfsyFrontBundle:Avent:day_2013_17.html.twig',
            '16-symfony-et-angularjs-tips'                                => 'AfsyFrontBundle:Avent:day_2013_16.html.twig',
            '15-etendre-behat-pour-y-mettre-son-metier'                   => 'AfsyFrontBundle:Avent:day_2013_15.html.twig',
            '14-votre-application-est-lente-pensez-a-optimiser-doctrine'  => 'AfsyFrontBundle:Avent:day_2013_14.html.twig',
            '13-jouons-a-cache-cache-avec-http'                           => 'AfsyFrontBundle:Avent:day_2013_13.html.twig',
            '12-et-si-on-mettait-un-peu-de-symfony-dans-javascript'       => 'AfsyFrontBundle:Avent:day_2013_12.html.twig',
            '11-les-cms-autour-de-symfony2'                               => 'AfsyFrontBundle:Avent:day_2013_11.html.twig',
            '10-les-meta-donnees-doctrine'                                => 'AfsyFrontBundle:Avent:day_2013_10.html.twig',
            '09-symfony-1-5-notre-fork-de-symfony1'                       => 'AfsyFrontBundle:Avent:day_2013_09.html.twig',
            '08-utilisation-avancee-du-composant-sonfig-de-symfony2'      => 'AfsyFrontBundle:Avent:day_2013_08.html.twig',
            '07-symfony-framework-MVC-javascript'                         => "AfsyFrontBundle:Avent:day_2013_07.html.twig",
            '06-best-practices-pour-vos-apis-rest-http-avec-symfony2'     => "AfsyFrontBundle:Avent:day_2013_06.html.twig",
            '05-conteneur-de-services-creer-ses-propres-tags'             => 'AfsyFrontBundle:Avent:day_2013_05.html.twig',
            '04-utilisez-apydatagridbundle-pour-des-listings-impeccables' => 'AfsyFrontBundle:Avent:day_2013_04.html.twig',
            '03-faites-le-plein-de-propel'                                => 'AfsyFrontBundle:Avent:day_2013_03.html.twig',
            '02-principes-stupid-solid-poo'                               => 'AfsyFrontBundle:Avent:day_2013_02.html.twig',
            '01-presentation-afsy'                                        => 'AfsyFrontBundle:Avent:day_2013_01.html.twig',
        ),
        2017 => array(
            '06-headless-is-more'                                 => 'AfsyFrontBundle:Avent:day_2017_06.html.twig',
            '05-deboguer-php-avec-gdb'                            => 'AfsyFrontBundle:Avent:day_2017_05.html.twig',
            '04-tagger-son-application'                           => 'AfsyFrontBundle:Avent:day_2017_04.html.twig',
            '03-deployer-un-projet-symfony-flex-sur-heroku'       => 'AfsyFrontBundle:Avent:day_2017_03.html.twig',
            '02-la-nouvelle-configuration-par-defaut-de-symfony4' => 'AfsyFrontBundle:Avent:day_2017_02.html.twig',
            '01-easyadminbundle-l-arriere-guichet-easy-peasy'     => 'AfsyFrontBundle:Avent:day_2017_01.html.twig',
            // '04-form-et-votre-modele'                                     => 'AfsyFrontBundle:Avent:day_2017_04.html.twig',
        )
    );

    public function indexAction($year)
    {
        // redirect to the most recent year
        if (null === $year) {
            $years = array_keys($this->slugs);
            krsort($years);

            return $this->redirect($this->generateUrl('avent', array('year' => current($years))));
        }

        return $this->render('AfsyFrontBundle:Avent:year_'.$year.'.html.twig', array(
            'year' => $year,
            'days' => $this->loadYearData($year)
        ));
    }

    public function feedAction($year)
    {
        return $this->render('AfsyFrontBundle:Avent:year_'.$year.'.atom.twig', array(
            'year' => $year,
            'days' => $this->loadYearData($year)
        ));
    }

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
            'day'       => $day,
            'prev'      => $prev_template ? $this->get('twig')->loadTemplate($prev_template) : null,
            'prev_slug' => $prev_slug,
            'next'      => $next_template ? $this->get('twig')->loadTemplate($next_template) : null,
            'next_slug' => $next_slug,
            'slug'      => $slug
        ));
    }

    private function getNext($slugs, $current_slug, $year)
    {
        $day = (int) $current_slug;

        try {
            $this->validateDate($year, $day+1);
        } catch (NotFoundHttpException $e) {
            return array(false, false);
        }

        // Get the next article, tricky! :)
        reset($slugs);
        while (key($slugs) !== null && key($slugs) !== $current_slug) {
            next($slugs);
        }

        // we use `prev` for next slug because they are in reverse order in the array
        $template = prev($slugs);
        $slug = key($slugs);

        if ($slug === $current_slug) {
            return array(false, false);
        }

        if ($this->futureEnabled || $year < date('Y') || date('m') == 12 && $day <= date('d')) {
            return array($template, $slug);
        }

        return array(false, false);
    }

    private function getPrev($slugs, $current_slug, $year)
    {
        // Get the previous article, tricky! :)
        reset($slugs);
        while (key($slugs) !== null && key($slugs) !== $current_slug) {
            next($slugs);
        }

        // we use `next` for previous slug because they are in reverse order in the array
        $template = next($slugs);
        $slug = key($slugs);
        $day = (int) $slug;
        if ($this->futureEnabled || $year < date('Y') || date('m') == 12 && $day <= date('d')) {
            return array($template, $slug);
        }

        return array(false, false);
    }

    private function loadYearData($year)
    {
        $this->validateDate($year);

        $twig = $this->get('twig');

        $today = date('d');
        $kept = array();

        foreach ($this->slugs[$year] as $slug => $template) {
            // Thanks, all articles start with '0X'
            $day = (int) $slug;

            if ($this->futureEnabled || $year < date('Y') || date('m') == 12 && $day <= $today) {
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

        if (date('m') < 12 || $day > date('d')) {
            throw $this->createNotFoundException(sprintf('Day "%s" is not yet accessible (today: %s).', $day.'/12', date('d/m')));
        }
    }
}
