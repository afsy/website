<?php

namespace Afsy\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Afsy\Entity\Article;

class LoadArticleData extends AbstractFixture implements OrderedFixtureInterface
{
    protected $articles = array(
        array(
            'title' => 'Calendrier de l\'avent 2013 par l\'Afsy',
            'body' => '<p>Pour les fêtes de cette fin d\'année, l\'Afsy vous a concocté <strong>un calendrier de l\'avent</strong>,
            avec du PHP dedans.</p><p>Rendez-vous sur <a href="/app_dev.php/avent/2013">cette page</a>',
            'city' => 'Paris',
            'tags' => array('Config', 'Paris', 'config'),
            'published_at' => '2012-05-09',
            'address' => '',
            'map' => '<a href="/avent/2013"><img src="http://www.homelifeweekly.com/wp-content/uploads/noel-christmas-card-printable-design.png" width="333" height="184" /></a>'
        ),
        array(
            'title' => 'Introduction au composant Config',
            'body' => '<p>
                    <strong>Mercredi 9 mai 2012</strong>, <strong>de 19h à 20h</strong>, la société <a href="http://www.theodo.fr/">Theodo</a> accueille <strong>Christophe Coevet</strong> (<a href="https://twitter.com/#!/Stof70">Stof70</a>) qui présentera une introduction au composant autonome &laquo; Config &raquo; de Symfony. Cette conférence mettra en avant l\'utilité de ce composant et sa mise en oeuvre pratique au sein des bundles. Christophe mettra aussi l\'accent sur l\'utilisation de ce composant en dehors du contexte de Symfony. La soirée se terminera par un pot général au pub <strong>The Lions</strong>.
                </p>',
            'city' => 'Paris',
            'tags' => array('Noel', 'Avent'),
            'address' => "Pub The Lions\n120 rue Montmartre\n75002 Paris",
            'published_at' => '2013-11-23',
            'map' => '<img src="http://maps.google.com/maps/api/staticmap?center=48.867804,2.343843&amp;zoom=16&amp;markers=size:mid|color:green|120+rue+Montmartre,+Paris&amp;path=color:0x0000FFff|weight:10|48.88435,2.40034&amp;size=470x260&amp;sensor=true" alt="" />'
        )
    );
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->articles as $article) {
            $entity = new Article();
            $entity->setBody($article['body']);
            $entity->setCity($article['city']);
            $entity->setAddress($article['address']);
            $entity->setIsPublished(true);
            $entity->setMap($article['map']);
            $entity->setMarkdownBody('html content loaded, please do not edit this post');
            $entity->setPublishedAt(new \DateTime($article['published_at']));
            $entity->setTags($article['tags']);
            $entity->setTitle($article['title']);
            $entity->addAuthor($this->getReference('xavierlacot'));
            $manager->persist($entity);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
