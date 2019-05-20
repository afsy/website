<?php

namespace Afsy\Listeners;

use Afsy\Entity\Article;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;

class MarkdownParserListener
{
    /**
     * @var MarkdownParserInterface
     */
    private $markdown;

    public function __construct(MarkdownParserInterface $markdownParser)
    {
        $this->markdown = $markdownParser;
    }

    /**
     * Act only on article entities and parse the markdownBody to fill the body
     *
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();

        if (!$entity instanceof Article) {
            return;
        }

        $entity->setBody($this->markdown->transformMarkdown($entity->getMarkdownBody()));
    }

    public function preUpdate(PreUpdateEventArgs $event)
    {
        $entity = $event->getEntity();

        if (!$entity instanceof Article) {
            return;
        }

        if ($event->hasChangedField('markdownBody')) {
            $entity->setBody($this->markdown->transformMarkdown($entity->getMarkdownBody()));

            $em = $event->getEntityManager();
            $em->getUnitOfWork()->computeChangeSet($em->getClassMetadata(get_class($entity)), $entity);
        }
    }
}
