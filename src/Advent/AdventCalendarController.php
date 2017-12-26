<?php

namespace Afsy\Advent;

use Afsy\Advent\AdventCalendar;
use Afsy\Advent\AdventCalendarRepository;
use Afsy\Advent\Exception\AdventCalendarNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route(
 *   path="/avent/{year}",
 *   methods="GET",
 *   requirements={"year": "20[1-2][0-9]"},
 *   defaults={"year": null}
 * )
 */
class AdventCalendarController extends AbstractController
{
    private $repository;

    private $futureEnabled = false; // useful for dev
    private $enabledYears = array('2013', '2017');

    private $slugs = array(
        // Last articles top
        2013 => array(
            '24-avancee-symfony-2-5'                                      => 'avent/day_2013_24.html.twig',
            '23-sensibilisation-ddd'                                      => 'avent/day_2013_23.html.twig',
            '22-formulaires-Symfony2-et-data-binding'                     => 'avent/day_2013_22.html.twig',
            '21-rabbitmq-et-Symfony2-traitements-asynchrones'             => 'avent/day_2013_21.html.twig',
            '20-elasticsearch-dans-votre-Symfony2'                        => 'avent/day_2013_20.html.twig',
            '19-bien-penser-ses-commandes-Symfony'                        => 'avent/day_2013_19.html.twig',
            '18-capifony-et-capistrano'                                   => 'avent/day_2013_18.html.twig',
            '17-commencer-a-adopter-Devops-sur-ses-projets-Symfony'       => 'avent/day_2013_17.html.twig',
            '16-symfony-et-angularjs-tips'                                => 'avent/day_2013_16.html.twig',
            '15-etendre-behat-pour-y-mettre-son-metier'                   => 'avent/day_2013_15.html.twig',
            '14-votre-application-est-lente-pensez-a-optimiser-doctrine'  => 'avent/day_2013_14.html.twig',
            '13-jouons-a-cache-cache-avec-http'                           => 'avent/day_2013_13.html.twig',
            '12-et-si-on-mettait-un-peu-de-symfony-dans-javascript'       => 'avent/day_2013_12.html.twig',
            '11-les-cms-autour-de-symfony2'                               => 'avent/day_2013_11.html.twig',
            '10-les-meta-donnees-doctrine'                                => 'avent/day_2013_10.html.twig',
            '09-symfony-1-5-notre-fork-de-symfony1'                       => 'avent/day_2013_09.html.twig',
            '08-utilisation-avancee-du-composant-sonfig-de-symfony2'      => 'avent/day_2013_08.html.twig',
            '07-symfony-framework-MVC-javascript'                         => "avent/day_2013_07.html.twig",
            '06-best-practices-pour-vos-apis-rest-http-avec-symfony2'     => "avent/day_2013_06.html.twig",
            '05-conteneur-de-services-creer-ses-propres-tags'             => 'avent/day_2013_05.html.twig',
            '04-utilisez-apydatagridbundle-pour-des-listings-impeccables' => 'avent/day_2013_04.html.twig',
            '03-faites-le-plein-de-propel'                                => 'avent/day_2013_03.html.twig',
            '02-principes-stupid-solid-poo'                               => 'avent/day_2013_02.html.twig',
            '01-presentation-afsy'                                        => 'avent/day_2013_01.html.twig',
        ),
        2017 => array(
            '24-unpack-tes-packs'                                            => 'avent/day_2017_24.html.twig',
            '23-comment-l-architecture-hexagonale-nous-a-facilite-la-vie'    => 'avent/day_2017_23.html.twig',
            '22-log-me-tender'                                               => 'avent/day_2017_22.html.twig',
            '21-symfony-et-websockets'                                       => 'avent/day_2017_21.html.twig',
            '20-elasticsearch-6-et-symfony-4'                                => 'avent/day_2017_20.html.twig',
            '19-composant-workflow-par-l-exemple'                            => 'avent/day_2017_19.html.twig',
            '18-structurer-sa-demarche-de-test'                              => 'avent/day_2017_18.html.twig',
            '17-bien-demarrer-avec-symfony-et-react'                         => 'avent/day_2017_17.html.twig',
            '16-des-astuces-de-securite-avec-symfony'                        => 'avent/day_2017_16.html.twig',
            '15-comment-ne-pas-trop-exposer-symfony'                         => 'avent/day_2017_15.html.twig',
            '14-une-api-graphql-avec-symfony'                                => 'avent/day_2017_14.html.twig',
            '13-creer-une-application-cross-platform'                        => 'avent/day_2017_13.html.twig',
            '12-serverless-php-et-symfony'                                   => 'avent/day_2017_12.html.twig',
            '11-non-a-la-doctrine-decouvrez-pomm-et-re-decouvrez-postgresql' => 'avent/day_2017_11.html.twig',
            '10-comment-mettre-les-mains-dans-un-projet-existant'            => 'avent/day_2017_10.html.twig',
            '09-utilisation-de-twig-en-phase-de-prototypage'                 => 'avent/day_2017_09.html.twig',
            '08-symfony-flex-la-nouvelle-facon-de-developper-avec-symfony'   => 'avent/day_2017_08.html.twig',
            '07-decoupler-form-et-votre-modele'                              => 'avent/day_2017_07.html.twig',
            '06-headless-is-more'                                            => 'avent/day_2017_06.html.twig',
            '05-deboguer-php-avec-gdb'                                       => 'avent/day_2017_05.html.twig',
            '04-tagger-son-application'                                      => 'avent/day_2017_04.html.twig',
            '03-deployer-un-projet-symfony-flex-sur-heroku'                  => 'avent/day_2017_03.html.twig',
            '02-la-nouvelle-configuration-par-defaut-de-symfony4'            => 'avent/day_2017_02.html.twig',
            '01-easyadminbundle-l-arriere-guichet-easy-peasy'                => 'avent/day_2017_01.html.twig',
        )
    );

    public function __construct(AdventCalendarRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("", name="avent")
     */
    public function indexAction(int $year = null): Response
    {
        if (null === $year) {
            return $this->redirectToRoute('avent', ['year' => $this->repository->mostRecentYear()]);
        }

        $calendar = $this->loadCalendar($year);

        return $this->render(sprintf('avent/year_%s.html.twig', $year), [
            'year' => $year,
            'days' => $this->loadYearData($year),
        ]);
    }

    /**
     * @Route("/feed.atom", name="avent_feed_atom", defaults={"_format": "atom"})
     */
    public function feedAction(int $year): Response
    {
        $calendar = $this->loadCalendar($year);

        return $this->render(sprintf('avent/year_%s.atom.twig', $year), [
            'year' => $year,
            'days' => $this->loadYearData($year),
        ]);
    }

    /**
     * @Route("/{slug}", name="avent_day")
     */
    public function dayAction(int $year, string $slug, \Twig_Environment $twig): Response
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
            'prev'      => $prev_template ? $twig->loadTemplate($prev_template) : null,
            'prev_slug' => $prev_slug,
            'next'      => $next_template ? $twig->loadTemplate($next_template) : null,
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

        $today = date('d');
        $kept = array();

        foreach ($this->slugs[$year] as $slug => $template) {
            // Thanks, all articles start with '0X'
            $day = (int) $slug;

            if ($this->futureEnabled || $year < date('Y') || date('m') == 12 && $day <= $today) {
                $kept[$slug] = $template;
            }
        }

        $twig = $this->get('twig');

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

    private function loadCalendar(int $year): AdventCalendar
    {
        try {
            return $this->repository->ofYear($year);
        } catch (AdventCalendarNotFoundException $exception) {
            throw $this->createNotFoundException('Advent calendar not found!', $exception);
        }
    }
}
