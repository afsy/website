<?php

namespace App\Behat;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
class FeatureContext extends AbstractContext
{
    /**
     * @Given je suis sur le calendrier de l'avent :year
     */
    public function jeSuisSurLeCalendrierDeLavent($year)
    {
        $this->crawler = $this->createClient()->request(
            'GET',
            '/avent/' . $year
        );

        \expect(self::getLastResponse()->getStatusCode())->toBe(200);
    }

    /**
     * @Then je peux accéder à tous les articles sans avoir d'erreur
     */
    public function jePeuxAccederATousLesArticlesSansAvoirDerreur()
    {
        $linkElements = $this->crawler->filter('.avent-author-block .date a');

        for ($i = 0; $i < $linkElements->count(); $i++) {
            $this->createClient()->click($linkElements->eq($i)->link());
            \expect(self::getLastResponse()->getStatusCode())->toBe(200);
        }
    }
}
