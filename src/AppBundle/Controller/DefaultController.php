<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Repositories\CommentRepository;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $articles = $this->getDoctrine()->getRepository('AppBundle:Article')->getArticlesWithTags();

        $paging = $this->get('knp_paginator');
        $pagination = $paging->paginate($articles, $request->query->getInt('page', 1), 5);

        return $this->render("AppBundle:Default:index.html.twig", array(
            'articles'=>$pagination
        ));
    }

    /**
     * @Route("/sidebar", name="sidebar")
     * @Method("GET")
     */
    public function sidebarAction()
    {
        return $this->render("AppBundle:Default:sidebar.html.twig", array(
            'tags' => $this->getAllTags(),
            'articles' => $this->getTopArticles(),
            'comments' => $this->getLastComments()
        ));
    }
}
