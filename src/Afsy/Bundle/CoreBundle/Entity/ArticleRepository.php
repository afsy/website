<?php
namespace Afsy\Bundle\CoreBundle\Entity;
use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    protected
        $paginator,
        $pageSize = 10;

    public function setPaginator($paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * Get the articles list, eventually constrained on a given year and month
     *
     * @param  array $constraints
     * @return mixed
     */
    public function getAll($constraints)
    {
        $qb = $this->getLastQueryBuilder();

        if (isset($constraints['year'])) {
            $start = new \DateTime($constraints['year'].'-01-01');
            $end = $start->add(new DateInterval('1y'));

            if ($constraints['month']) {
                $start = new \DateTime($constraints['year'].'-'.$constraints['month'].'-01');
                $end = $start->add(new DateInterval('1m'));
            }

            $qb
                ->andWhere('a.published_at < :end')
                ->andWhere('a.published_at >= :start');
            $qb->setParameter('end', $end);
            $qb->setParameter('start', $start);
        }

        $query = $qb->getQuery();

        return isset($constraints['page']) ? $this->paginator->paginate($query, $constraints['page'], $this->pageSize)->getItems() : $query->getResult();
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
     * Return a array of year with an array of DateTime for each month with published articles
     *
     * @return array
     */
    public function getDateArray()
    {
        $articles = $this->getLastQueryBuilder()->select('a.published_at')->getQuery()->execute();
        $dates    = array();

        foreach ($articles as $article) {
            $year   = (int) $article['published_at']->format('Y');
            $month  = (int) $article['published_at']->format('m');

            if (!isset($dates[$year])) {
                $dates[$year] = array(
                    $month => new \DateTime($year. '-'. $month .'-1')
                );
            } elseif (!isset($dates[$year][$month])) {
                $dates[$year][$month] = new \DateTime($year. '-'. $month .'-1');
            }
        }

        return $dates;
    }

    /**
     * Doctrine2 sux so let's do it with 2 queries
     *
     * @param  \DateTime $date
     * @return array
     */
    public function getForDate(\DateTime $date)
    {
        // Select all the article id's for a month/year
        $article_ids = array();
        $sql = "SELECT id FROM Article p
                WHERE YEAR( p.published_at ) = :year
                AND MONTH( p.published_at ) = :month";
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute(array(
            ':year' => $date->format('Y'),
            ':month' => (int) $date->format('m')
        ));
        $rows = $stmt->fetchAll();

        foreach ($rows as $row) {
            $article_ids[] = $row['id'];
        }

        if (empty($article_ids)) {
            return array();
        }

        return $this->getLastQueryBuilder()->
                andWhere('a.id IN (:ids)')
                ->setParameter('ids', $article_ids)
                ->getQuery()->execute();
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
        $qb->andWhere($qb->expr()
                         ->in('a.id', $ids));

        return $qb->getQuery()
                  ->execute();
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
        $query = $this->_em
                      ->createQuery('SELECT a FROM AfsyCoreBundle:Article a
                                    WHERE a.is_published = 1
                                    AND a.published_at < :date
                                    ORDER BY a.published_at DESC')
                      ->setMaxResults(1);
        $query->setParameter('date', $date);

        return $query->getOneOrNullResult();
    }

    public function getNextPublished($date)
    {
        $query = $this->_em
                      ->createQuery('SELECT a FROM AfsyCoreBundle:Article a
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
