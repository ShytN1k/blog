<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
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
}
