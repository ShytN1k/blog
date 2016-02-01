<?php

namespace AppBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;

class SidebarManager
{
    protected $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getAllTags()
    {
        return $this->doctrine->getRepository('AppBundle:Tag')->getSidebarTags();
    }

    public function getLastComments()
    {
        return $this->doctrine->getRepository('AppBundle:Comment')->getLastComments();
    }

    public function getTopArticles()
    {
        return $this->doctrine->getRepository('AppBundle:Article')->getTopArticles();
    }

    public function getSidebar()
    {
        return array(
            'tags' => $this->getAllTags(),
            'articles' => $this->getTopArticles(),
            'comments' => $this->getLastComments()
        );
    }
}