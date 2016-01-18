<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends BaseController
{
    /**
     * @Route("/author/{authorId}", name="articlesByAuthor", requirements={"authorId" = "[0-9]+"})
     * @Method("GET")
     */
    public function articlesByAuthorAction(Request $request, $authorId)
    {
        $author = $this->getDoctrine()->getRepository('AppBundle:Author')->find($authorId);
        $articles = $this->getDoctrine()->getRepository('AppBundle:Article')->getArticlesByAuthor($authorId);

        $paging = $this->get('knp_paginator');
        $pagination = $paging->paginate($articles, $request->query->getInt('page', 1), 5);

        return $this->render("AppBundle:Author:articles-by-author.html.twig", array(
            'articles' => $pagination,
            'author' => $author
        ));
    }
}
