<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Repositories\CommentRepository;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     */
    public function indexAction()
    {
        $articles = $this->getDoctrine()->getRepository('AppBundle:Article')->findAll();

        return $this->render("AppBundle:Default:index.html.twig", array('articles'=>$articles));
    }

    /**
     * @Route("/sidebar", name="sidebar")
     * @Method("GET")
     */
    public function sidebarAction()
    {
        return $this->render("AppBundle:Default:sidebar.html.twig", array(
            'tags' => $this->getAllTags(),
            'articles' => $this->getTopAticles(),
            'comments' => $this->getLastComments()
        ));
    }
}
