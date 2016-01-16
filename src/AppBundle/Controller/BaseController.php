<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    public function getAllTags()
    {
        return $this->getDoctrine()->getRepository('AppBundle:Tag')->findAll();
    }

    public function getLastComments()
    {
        return $this->getDoctrine()->getRepository('AppBundle:Comment')->getLastComments();
    }

    public function getTopAticles()
    {
        return $this->getDoctrine()->getRepository('AppBundle:Article')->getTopAticles();
    }
}
