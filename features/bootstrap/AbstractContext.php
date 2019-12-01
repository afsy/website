<?php

namespace App\Behat;

use Behat\Behat\Context\Context;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestAssertionsTrait;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class AbstractContext implements Context, KernelAwareContext
{
    use KernelDictionary;
    use WebTestAssertionsTrait;

    /** @var Crawler */
    protected $crawler;

    public static function fail($message = '')
    {
        Assert::fail($message);
    }

    protected function createClient(array $server = [], KernelInterface $kernel = null): KernelBrowser
    {
        if ($kernel === null) {
            $kernel = $this->kernel;
        }

        $client = $kernel->getContainer()->get('test.client');
        $client->setServerParameters($server);

        return self::getClient($client);
    }

    protected static function getLastResponse(): Response
    {
        return self::getClient()->getResponse();
    }
}
