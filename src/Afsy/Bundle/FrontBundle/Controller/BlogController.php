<?php

namespace Afsy\Bundle\FrontBundle\Controller;

use Afsy\Bundle\CoreBundle\Entity\Author;
use Afsy\Bundle\CoreBundle\Entity\Tag;

use Afsy\Bundle\CoreBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Blog controller
 */
class BlogController extends Controller
{
    /**
     * homepage
     */
    public function indexAction($year = null, $month = null)
    {
        $repository = $this->getDoctrine()->getRepository('AfsyCoreBundle:Article');
        $repository->setPaginator($this->get('knp_paginator'));
        $articles = $repository->getAll($year, $month);
        $dates = $repository->getDateArray();

        return $this->render('AfsyFrontBundle:Blog:index.html.twig', array(
            'articles' => $articles,
            'dates' => $dates
        ));
    }

    public function showAction(Article $article, $preview = false)
    {
        if (!$preview && !$article->getIsPublished()) {
            throw new NotFoundHttpException('Unknown article slug.');
        }

        $tagManager = $this->get('fpn_tag.tag_manager');
        $tagManager->loadTagging($article);

        return $this->render('AfsyFrontBundle:Blog:show.html.twig', array(
            'article' => $article,
        ));
    }

    public function feedAction()
    {
        $articles = $this->getDoctrine()->getRepository('AfsyCoreBundle:Article')->getLast(10);
        $response = new Response();
        $response->headers->set('Content-Type', 'application/atom+xml');

        return $this->render('AfsyFrontBundle:Blog:feed.atom.twig', array(
            'articles'  => $articles
        ), $response);
    }
}
