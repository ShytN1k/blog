<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    public function getAllTags()
    {
        return $this->getDoctrine()->getRepository('AppBundle:Tag')->getSidebarTags();
    }

    public function getLastComments()
    {
        return $this->getDoctrine()->getRepository('AppBundle:Comment')->getLastComments();
    }

    public function getTopArticles()
    {
        return $this->getDoctrine()->getRepository('AppBundle:Article')->getTopArticles();
    }
}
