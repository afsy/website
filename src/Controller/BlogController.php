<?php

namespace Afsy\Controller;

use Afsy\Entity\Article;
use Afsy\Entity\Tag;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends Controller
{
    /**
     * homepage
     */
    public function indexAction(Request $request)
    {
        $query = $this->getDoctrine()->getRepository(Article::class)->getQuery();
        $pagination = $this->get('knp_paginator')->paginate(
            $query,
            $request->query->get('page', 1)
        );
        $tagManager = $this->get('afsy.tag.tag_manager');

        foreach ($pagination as $article) {
            $tagManager->loadTagging($article);
        }

        return $this->render('Blog/index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    public function showAction(Article $article, $preview = false)
    {
        if (!$preview && !$article->getIsPublished()) {
            throw new NotFoundHttpException('Unknown article slug.');
        }

        $tagManager = $this->get('afsy.tag.tag_manager');
        $tagManager->loadTagging($article);

        return $this->render('Blog/show.html.twig', array(
            'article' => $article,
        ));
    }

    public function showTagAction(Request $request, Tag $tag)
    {
        $query = $this->getDoctrine()->getRepository(Article::class)->getQueryForTag($tag);
        $pagination = $this->get('knp_paginator')->paginate(
            $query,
            $request->query->get('page', 1)
        );
        $tagManager = $this->get('afsy.tag.tag_manager');

        foreach ($pagination as $article) {
            $tagManager->loadTagging($article);
        }

        return $this->render('Blog/index.twig', array(
            'pagination' => $pagination,
            'tag' => $tag
        ));
    }

    public function feedAction()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->getLast(10);
        $response = new Response();
        $response->headers->set('Content-Type', 'application/atom+xml');

        return $this->render('Blog/feed.atom.twig', array(
            'articles'  => $articles
        ), $response);
    }
}
