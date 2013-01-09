<?php

namespace Afsy\Bundle\FrontBundle\Controller;

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
    public function indexAction()
    {
        $query = $this->getDoctrine()->getRepository('AfsyCoreBundle:Article')->getQuery();
        $pagination = $this->get('knp_paginator')->paginate(
            $query,
            $this->get('request')->query->get('page', 1)
        );

        return $this->render('AfsyFrontBundle:Blog:index.html.twig', array(
            'pagination' => $pagination
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
