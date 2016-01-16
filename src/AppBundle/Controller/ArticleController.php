<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends BaseController
{
    /**
     * @Route("/article/{articleId}", name="articles", requirements={"teamId" = "[0-9]+"})
     * @Method("GET")
     */
    public function indexAction($articleId)
    {
        $article = $this->getDoctrine()->getRepository('AppBundle:Article')->find($articleId);

        return $this->render("AppBundle:Article:show-article.html.twig", array('article' => $article));
    }
}
