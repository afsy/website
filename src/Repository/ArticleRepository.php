<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Tag;

class ArticleRepository extends EntityRepository
{
    /**
     * Get the articles list.
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQuery()
    {
        return $this->getLastQueryBuilder()->getQuery();
    }

    /**
     * Returns a query builder for articles tagged with a certain tag.
     *
     * @param Tag $tag
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryForTag(Tag $tag)
    {
        $qb = $this->getLastQueryBuilder();
        $ids = $this->getEntityManager()
                    ->getRepository('App:Tag')
                    ->getResourceIdsForTag('article_tag', $tag->getName());

        if (count($ids) >= 1) {
            $qb->andWhere($qb->expr()->in('a.id', $ids));
        }

        return $qb->getQuery();
    }

    /**
     * Query to get the X last published articles.
     *
     * @param int|null $limit
     *
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
     * Get the X last Article.
     *
     * @param int $limit
     *
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
     * Return the last published articles for a Tag.
     *
     * @param Tag $tag
     * @param int $limit
     *
     * @return array
     */
    public function getLastByTag(Tag $tag, $limit = 1)
    {
        $ids = $this->getEntityManager()
            ->getRepository('App:Tag')
            ->getResourceIdsForTag('article_tag', $tag->getName())
        ;

        if (count($ids) < 1) {
            return array();
        }

        $qb = $this->getLastQueryBuilder($limit);
        $qb->andWhere($qb->expr()->in('a.id', $ids));

        return $qb->getQuery()->execute();
    }

    /**
     * @param Article $article
     *
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
            SELECT a FROM App:Article a
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
            SELECT a FROM App:Article a
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
            FROM App:Article a
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
