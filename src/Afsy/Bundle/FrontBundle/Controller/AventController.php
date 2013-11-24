<?php

namespace Afsy\Bundle\FrontBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AventController extends Controller
{
    protected $futureEnabled = true; // useful for dev
    protected $enabledYears = array('2013');

    protected $slugs = array(
        2013 => array(
            '01-Komen-Kon-Fé-Du-PHP' => "AfsyFrontBundle:Avent:day_2013_01.html.twig"
        )
    );

    public function indexAction($year)
    {
        return $this->render('AfsyFrontBundle:Avent:year_'.$year.'.html.twig', array(
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

        return $this->render($yearSlugs[$slug]);
    }

    private function loadYearData($year)
    {
        $this->validateDate($year);

        $twig = $this->get('twig');

        return array_map(function ($template) use ($twig) {
            return $twig->loadTemplate($template);
        }, $this->slugs[$year]);
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
