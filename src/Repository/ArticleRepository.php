<?php

namespace Afsy\Repository;

use Afsy\Entity\Article;
use Afsy\Entity\Author;
use Afsy\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function getQuery(): Query
    {
        return $this->getLastQueryBuilder()->getQuery();
    }

    private function getTagRepository(): TagRepository
    {
        return $this->_em->getRepository(Tag::class);
    }

    public function getQueryForTag(Tag $tag): Query
    {
        $ids = $this
            ->getTagRepository()
            ->getResourceIdsForTag('article_tag', $tag->getName())
        ;

        $qb = $this->getLastQueryBuilder();

        if (count($ids)) {
            $qb->andWhere($qb->expr()->in('a.id', $ids));
        }

        return $qb->getQuery();
    }

    public function getLastQueryBuilder(int $limit = null): QueryBuilder
    {
        $qb = $this
            ->createQueryBuilder('a')
            ->andWhere('a.isPublished = :published')
            ->andWhere('a.publishedAt < :now')
            ->orderBy('a.eventDate', 'DESC')
            ->setParameter('published', true)
            ->setParameter('now', new \DateTime())
        ;

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb;
    }

    /**
     * @return Article[]
     */
    public function getLast(int $limit = 1): array
    {
        return $this
            ->getLastQueryBuilder($limit)
            ->getQuery()
            ->execute()
        ;
    }

    /**
     * @return Article[]
     */
    public function getLastByTag(Tag $tag, int $limit = 1): array
    {
        $ids = $this
            ->getTagRepository()
            ->getResourceIdsForTag('article_tag', $tag->getName())
        ;

        if (count($ids) < 1) {
            return [];
        }

        $qb = $this->getLastQueryBuilder($limit);
        $qb->andWhere($qb->expr()->in('a.id', $ids));

        return $qb->getQuery()->execute();
    }

    public function getPrevNext(Article $article): array
    {
        return array(
            'previous' => $this->getPreviousPublished($article->getPublishedAt()),
            'next' => $this->getNextPublished($article->getPublishedAt()),
        );
    }

    public function getPreviousPublished($date): ?Article
    {
        return $this
            ->createQueryBuilder('a')
            ->where('a.isPublished = :published')
            ->andWhere('a.publishedAt < :date')
            ->orderBy('a.publishedAt', 'DESC')
            ->setParameter('published', true)
            ->setParameter('date', $date)
            ->setMaxResults(1)
            ->getOneOrNullResult()
        ;
    }

    public function getNextPublished($date): ?Article
    {
        return $this
            ->createQueryBuilder('a')
            ->where('a.isPublished = :published')
            ->andWhere('a.publishedAt > :date')
            ->andWhere('a.publishedAt < :now')
            ->orderBy('a.publishedAt', 'DESC')
            ->setParameter('published', true)
            ->setParameter('date', $date)
            ->setParameter('now', new \DateTime())
            ->setMaxResults(1)
            ->getOneOrNullResult()
        ;
    }

    /**
     * Article[]
     */
    public function getLastForAuthor(int $limit = 5, Author $author): array
    {
        return $this
            ->createQueryBuilder('a')
            ->join('a.authors', 'au')
            ->where('au = :author')
            ->andWhere('a.isPublished = :published')
            ->orderBy('a.publishedAt', 'DESC')
            ->setParameter('author', $author)
            ->setParameter('published', true)
            ->setMaxResults($limit)
            ->execute()
        ;
    }

    public static function isPublished(Article $article): bool
    {
        return $article->getIsPublished();
    }
}
