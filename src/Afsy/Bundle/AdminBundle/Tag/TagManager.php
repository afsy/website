<?php

namespace Afsy\Bundle\AdminBundle\Tag;

use Doctrine\Common\Util\Inflector;
use Doctrine\ORM\EntityManager;
use Gedmo\Sluggable\Util\Urlizer;
use DoctrineExtensions\Taggable\TagManager as BaseTagManager;

class TagManager extends BaseTagManager
{
    public function __construct(EntityManager $em, $tagClass = null, $taggingClass = null)
    {
        parent::__construct($em, $tagClass, $taggingClass);
    }

    /**
     * @see DoctrineExtensions\Taggable\TagManager::createTag()
     */
    protected function createTag($name)
    {
        $tag = parent::createTag($name);
        $tag->setSlug($this->slugify($name));
        return $tag;
    }

    public function loadOrCreateTags(array $names)
    {
        $slugs = array();
        $refSlug = array();

        foreach ($names as $name) {
            $slug = Urlizer::urlize($name, "-");
            $slugs[] = $slug;
            $refSlug[$slug] = $name;
        }

        $slugs = array_unique($slugs);

        $builder = $this->em->createQueryBuilder();

        $tags = $builder->select('t')
                        ->from($this->tagClass, 't')
                        ->where($builder->expr()->in('t.slug', $slugs))
                        ->getQuery()
                        ->getResult();

        $loadedSlug = array();
        foreach ($tags as $tag) {
            $loadedSlug[] = $tag->getSlug();
        }

        $missingSlugs = array_udiff($slugs, $loadedSlug, 'strcasecmp');
        if (sizeof($missingSlugs)) {
            foreach ($missingSlugs as $slug) {
                $tag = $this->createTag($refSlug[$slug] );
                $this->em->persist($tag);

                $tags[] = $tag;
            }

            $this->em->flush();
        }

        return $tags;
    }

    private function slugify($text)
    {
        return str_replace('_', '-', Inflector::tableize($text));
    }
}
