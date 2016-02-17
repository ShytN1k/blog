<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Article;
use AppBundle\Entity\Author;
use AppBundle\Traits\WorkWithEntityTrait;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;

class ArticleManager
{
    use WorkWithEntityTrait;

    protected $knp_paginator;

    protected $doctrine;

    public function __construct(Registry $doctrine, Paginator $knp_paginator)
    {
        $this->doctrine = $doctrine;
        $this->knp_paginator = $knp_paginator;
    }

    public function manageArticlesAsAdmin($requestQuery)
    {
        $articles = $this->doctrine->getRepository('AppBundle:Article')->findAll();
        $pagination = $this->knp_paginator->paginate($articles, $requestQuery->getInt('page', 1), 10);

        return array(
            'articles' => $pagination
        );
    }

    public function createNewArticle(Article $article, Author $author)
    {
        $em = $this->doctrine->getManager();
        if ($author !== null) {
            $article->setAuthor($author);
        }
        $em->persist($article);
        $em->flush();
    }
}