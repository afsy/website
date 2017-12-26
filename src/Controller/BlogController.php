<?php

namespace Afsy\Controller;

use Afsy\Entity\Article;
use Afsy\Entity\Tag;
use Afsy\Repository\ArticleRepository;
use Afsy\Tag\TagManager;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/blog", name="blog_")
 */
class BlogController extends AbstractController
{
    private $repository;
    private $tagManager;

    public function __construct(
        ArticleRepository $repository,
        TagManager $tagManager
    ) {
        $this->repository = $repository;
        $this->tagManager = $tagManager;
    }

    /**
     * @Route("", name="home", methods="GET")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $articles = $paginator->paginate(
            $this->repository->getQuery(),
            $request->query->getInt('page', 1)
        );

        foreach ($articles as $article) {
            $this->tagManager->loadTagging($article);
        }

        return $this->render('blog/index.html.twig', ['pagination' => $articles]);
    }

    /**
     * @Route("/{slug}", name="show", methods="GET")
     * @Route("/preview/{slug}", name="preview", methods="GET", defaults={"preview": true})
     */
    public function show(Article $article, bool $preview = false): Response
    {
        if (!$preview && !$article->getIsPublished()) {
            throw new NotFoundHttpException('Unknown article slug.');
        }

        $this->tagManager->loadTagging($article);

        return $this->render('blog/show.html.twig', ['article' => $article]);
    }

    /**
     * @Route("/blog/tag/{name}", name="show_tag", methods="GET")
     */
    public function showTag(Request $request, PaginatorInterface $paginator, Tag $tag): Response
    {
        $articles = $paginator->paginate(
            $this->repository->getQueryForTag($tag),
            $request->query->getInt('page', 1)
        );

        foreach ($articles as $article) {
            $this->tagManager->loadTagging($article);
        }

        return $this->render('blog/index.html.twig', [
            'pagination' => $articles,
            'tag' => $tag,
        ]);
    }
}
