<?php

namespace Afsy\Controller;

use Afsy\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class FeedController extends AbstractController
{
    /**
     * @Route(
     *   path="/feed.atom",
     *   name="feed_atom",
     *   defaults={"_format": "atom"},
     *   methods="GET"
     * )
     */
    public function feedAction(ArticleRepository $repository): Response
    {
        $response = $this->render('blog/feed.atom.twig', [
            'articles'  => $repository->getLast(10)
        ]);
        $response->headers->set('Content-Type', 'application/atom+xml');

        return $response;
    }
}