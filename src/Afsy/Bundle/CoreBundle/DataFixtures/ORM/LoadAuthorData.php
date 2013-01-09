<?php

namespace Afsy\Bundle\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Afsy\Bundle\CoreBundle\Entity\Author;

class LoadPagesData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = new Author();
        $user->setName('Xavier Lacot');
        $user->setEmail('xavier@lacot.org');
        $user->setCity('Paris');
        $user->setIsEnabled(true);
        $manager->persist($user);
        $manager->flush();
    }
}
