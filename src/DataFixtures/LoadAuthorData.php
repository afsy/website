<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadAuthorData extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = new Author();
        $user->setName('Xavier Lacot');
        $user->setEmail('xavier@lacot.org');
        $user->setCity('Paris');
        $user->setIsEnabled(true);

        $maxime = new Author();
        $maxime->setName('Maxime Veber');
        $maxime->setEmail('nek.dev@gmail.com');
        $maxime->setCity('Paris');
        $maxime->setIsEnabled(true);

        $manager->persist($user);
        $manager->persist($maxime);
        $this->addReference('xavierlacot', $user);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
