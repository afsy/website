<?php
namespace Afsy\Bundle\CoreBundle\Entity;
use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    /**
     * Get the articles list
     *
     * @param  array   $constraints
     * @return mixed
     */
    public function getQuery()
    {
        return $this->getLastQueryBuilder()->getQuery();
    }

    /**
     * Query to get the X last published articles
     *
     * @param $limit
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getLastQueryBuilder($limit = false)
    {
        $qb = $this->createQueryBuilder('a');
        $qb->andWhere('a.is_published = 1');
        $qb->andWhere('a.published_at < :now');
        $qb->orderBy('a.published_at', 'DESC');

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        $qb->setParameter('now', new \DateTime());

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
        return $this->getLastQueryBuilder($limit)
            ->getQuery()
            ->execute();
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
            ->getResourceIdsForTag('article_tag', $tag->getName());

        if (count($ids) < 1) {
            return array();
        }

        $qb = $this->getLastQueryBuilder($limit);
        $qb->andWhere($qb->expr()->in('a.id', $ids));

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
            'next' => $this->getNextPublished($article->getPublishedAt())
        );
    }

    public function getPreviousPublished($date)
    {
        $query = $this->_em->createQuery('SELECT a FROM AfsyCoreBundle:Article a
            WHERE a.is_published = 1
            AND a.published_at < :date
            ORDER BY a.published_at DESC')
            ->setMaxResults(1);
        $query->setParameter('date', $date);

        return $query->getOneOrNullResult();
    }

    public function getNextPublished($date)
    {
        $query = $this->_em->createQuery('SELECT a FROM AfsyCoreBundle:Article a
            WHERE a.is_published = 1
            AND a.published_at > :date
            AND a.published_at < :now
            ORDER BY a.published_at ASC')
            ->setMaxResults(1);
        $query->setParameter('date', $date);
        $query->setParameter('now', new \DateTime());

        return $query->getOneOrNullResult();
    }

    public function getLastForAuthor($limit = 5, Author $author)
    {
        $query = $this->_em->createQuery('
            SELECT a
            FROM AfsyCoreBundle:Article a
            JOIN a.authors au
            WHERE au = :author
            AND a.is_published = 1
            ORDER BY a.published_at DESC'
        );

        $query->setMaxResults($limit);
        $query->setParameter("author", $author);

        return $query->execute();
    }

    public static function isPublished($article)
    {
        return $article->getIsPublished();
    }
}
