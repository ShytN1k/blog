<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TagController extends BaseController
{
    /**
     * @Route("/tag/{tagId}", name="articlesByTag", requirements={"tagId" = "[0-9]+"})
     * @Method("GET")
     */
    public function articlesByTagAction(Request $request, $tagId)
    {
        $tag = $this->getDoctrine()->getRepository('AppBundle:Tag')->find($tagId);
        $articles = $this->getDoctrine()->getRepository('AppBundle:Article')->getArticlesForTag($tagId);

        $paging = $this->get('knp_paginator');
        $pagination = $paging->paginate($articles, $request->query->getInt('page', 1), 5);

        return $this->render("AppBundle:Tag:articles-for-tag.html.twig", array(
            'articles' => $pagination,
            'tag' => $tag
        ));
    }
}
