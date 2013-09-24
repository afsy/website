<?php

namespace Afsy\Bundle\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    /**
     * Get the articles list
     *
     * @param  array $constraints
     * @return mixed
     */
    public function getQuery()
    {
        return $this->getLastQueryBuilder()->getQuery();
    }

    /**
     * Query to get the X last published articles
     *
     * @param integer|null $limit
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getLastQueryBuilder($limit = null)
    {
        $qb = $this
            ->createQueryBuilder('a')
            ->andWhere('a.isPublished = :published')
            ->andWhere('a.publishedAt < :now')
            ->orderBy('a.eventDate', 'DESC')
        ;

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        $qb
            ->setParameter('published', true)
            ->setParameter('now', new \DateTime())
        ;

        return $qb;
    }

    /**
     * Get the X last Article
     *
     * @param  int   $limit
     * @return mixed
     */
    public function getLast($limit = 1)
    {
        return $this
            ->getLastQueryBuilder($limit)
            ->getQuery()
            ->execute()
        ;
    }

    /**
     * Return the last published articles for a Tag
     *
     * @param  Tag   $tag
     * @param  int   $limit
     * @return array
     */

    public function getLastByTag(Tag $tag, $limit = 1)
    {
        $ids = $this->getEntityManager()
            ->getRepository('AfsyCoreBundle:Tag')
            ->getResourceIdsForTag('article_tag', $tag->getName())
        ;

        if (count($ids) < 1) {
            return array();
        }

        $qb = $this
            ->getLastQueryBuilder($limit)
            ->andWhere($qb->expr()->in('a.id', $ids))
        ;

        return $qb->getQuery()->execute();
    }

    /**
     * @param  Article $article
     * @return array
     */

    public function getPrevNext(Article $article)
    {
        return array(
            'previous' => $this->getPreviousPublished($article->getPublishedAt()),
            'next' => $this->getNextPublished($article->getPublishedAt()),
        );
    }

    public function getPreviousPublished($date)
    {
        $query = $this->_em->createQuery('
            SELECT a FROM AfsyCoreBundle:Article a
            WHERE a.isPublished = :published
            AND a.publishedAt < :date
            ORDER BY a.publishedAt DESC'
        );

        $query
            ->setParameter('published', true)
            ->setParameter('date', $date)
            ->setMaxResults(1)
        ;

        return $query->getOneOrNullResult();
    }

    public function getNextPublished($date)
    {
        $query = $this->_em->createQuery('
            SELECT a FROM AfsyCoreBundle:Article a
            WHERE a.isPublished = :published
            AND a.publishedAt > :date
            AND a.publishedAt < :now
            ORDER BY a.publishedAt ASC'
        );

        $query
            ->setMaxResults(1)
            ->setParameter('published', true)
            ->setParameter('date', $date)
            ->setParameter('now', new \DateTime())
        ;

        return $query->getOneOrNullResult();
    }

    public function getLastForAuthor($limit = 5, Author $author)
    {
        $query = $this->_em->createQuery('
            SELECT a
            FROM AfsyCoreBundle:Article a
            JOIN a.authors au
            WHERE au = :author
            AND a.is_published = :published
            ORDER BY a.published_at DESC'
        );

        $query
            ->setParameter('author', $author)
            ->setParameter('published', true)
            ->setMaxResults($limit)
        ;

        return $query->execute();
    }

    public static function isPublished($article)
    {
        return $article->getIsPublished();
    }
}
