<?php
namespace Afsy\Entity;

use Afsy\Entity\Article;
use Doctrine\ORM\AbstractQuery;
use DoctrineExtensions\Taggable\Entity\TagRepository as BaseTagRepository;

class TagRepository extends BaseTagRepository
{
    public function getTagsWithCountArrayForPublishedArticles()
    {
        // Crappy Taggable does not allow joins...
        $qb = $this->_em->getRepository(Article::class)->getLastQueryBuilder(1111111);
        $qb->select('a.id');
        $article_ids = $qb->getQuery()->getResult(AbstractQuery::HYDRATE_SCALAR);

        $ids = array();

        foreach ($article_ids as $id) {
            $ids[] = $id['id'];
        }

        if (count($ids) < 1) {
            return array();
        }

        $qb = $this->getTagsWithCountArrayQueryBuilder("article_tag");
        $qb->andWhere('tagging.resourceId IN (:ids)');
        $qb->setParameter('ids', $ids);

        $tags = $qb->getQuery()
            ->getResult(AbstractQuery::HYDRATE_SCALAR)
        ;

        $arr = array();
        foreach ($tags as $tag) {
            $count = $tag['tag_count'];

            // don't include orphaned tags
            if ($count > 0) {
                $tagName = $tag[$this->tagQueryField];
                $arr[$tagName] = $count;
            }
        }

        return $arr;
    }
}
