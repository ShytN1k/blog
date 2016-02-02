<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Article;
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

    public function createNewArticle(Article $article)
    {
        $em = $this->doctrine->getManager();
        $author = $em->getRepository('AppBundle:Author')->find(1);
        if ($author !== null) {
            $article->setAuthor($author);
        }
        $em->persist($article);
        $em->flush();
    }
}