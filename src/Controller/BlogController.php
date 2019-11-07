<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use FPN\TagBundle\Entity\TagManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Blog controller.
 */
class BlogController extends AbstractController
{
    private $knpPaginator;

    private $tagManager;

    private $doctrine;

    public function __construct(PaginatorInterface $knpPaginator, TagManager $tagManager, EntityManagerInterface $doctrine)
    {
        $this->knpPaginator = $knpPaginator;
        $this->tagManager = $tagManager;
        $this->doctrine = $doctrine;
    }

    /**
     * @Route("/", name="blog_home")
     */
    public function indexAction(Request $request)
    {
        $query = $this->getDoctrine()->getRepository('App:Article')->getQuery();
        $pagination = $this->knpPaginator->paginate(
            $query,
            $request->query->get('page', 1)
        );

        foreach ($pagination as $article) {
            $this->tagManager->loadTagging($article);
        }

        return $this->render('Blog/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/blog/{slug}", name="blog_show")
     * @Route("/blog/preview/{slug}", name="blog_preview", defaults={"preview":true})
     */
    public function showAction(Article $article, $preview = false)
    {
        if (!$preview && !$article->getIsPublished()) {
            throw new NotFoundHttpException('Unknown article slug.');
        }

        $this->tagManager->loadTagging($article);

        return $this->render('Blog/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/blog/tag/{name}", name="blog_show_tag")
     * @Template("Blog/index.html.twig")
     */
    public function showTagAction(Request $request, Tag $tag)
    {
        $query = $this->getDoctrine()->getRepository('App:Article')->getQueryForTag($tag);
        $pagination = $this->knpPaginator->paginate(
            $query,
            $request->query->get('page', 1)
        );

        foreach ($pagination as $article) {
            $this->tagManager->loadTagging($article);
        }

        return array(
            'pagination' => $pagination,
            'tag' => $tag,
        );
    }

    /**
     * @Route("/feed", name="feed_atom")
     */
    public function feedAction()
    {
        $articles = $this->doctrine->getRepository('App:Article')->getLast(10);
        $response = new Response();
        $response->headers->set('Content-Type', 'application/atom+xml');

        return $this->render('Blog/feed.atom.twig', array(
            'articles' => $articles,
        ), $response);
    }
}
