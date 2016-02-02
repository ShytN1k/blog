<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Author;
use AppBundle\Traits\WorkWithEntityTrait;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Knp\Component\Pager\Paginator;

class AuthorManager
{
    use WorkWithEntityTrait;
    protected $paginator;

    protected $doctrine;

    public function __construct(Registry $doctrine, Paginator $knp_paginator)
    {
        $this->paginator = $knp_paginator;
        $this->doctrine = $doctrine;
    }

    public function getArticlesByAuthor($authorId, $requestQuery)
    {
        $author = $this->doctrine->getRepository('AppBundle:Author')->find($authorId);
        $articles = $this->doctrine->getRepository('AppBundle:Article')->getArticlesByAuthor($authorId);

        $pagination = $this->paginator->paginate($articles, $requestQuery->getInt('page', 1), 5);

        return array(
            'articles' => $pagination,
            'author' => $author
        );
    }

    public function manageAuthorsAsAdmin($requestQuery)
    {
        $authors = $this->doctrine->getRepository('AppBundle:Author')->findAll();
        $pagination = $this->paginator->paginate($authors, $requestQuery->getInt('page', 1), 10);

        return array(
            'authors' => $pagination
        );
    }
}