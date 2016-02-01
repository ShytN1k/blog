<?php

namespace AppBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Knp\Component\Pager\Paginator;

class DefaultManager
{
    protected $doctrine;

    protected $knp_paginator;

    public function __construct(Registry $doctrine, Paginator $knp_paginator)
    {
        $this->doctrine = $doctrine;
        $this->knp_paginator = $knp_paginator;
    }

    public function getAllArticles($requestQuery)
    {
        $articles = $this->doctrine->getRepository('AppBundle:Article')->getArticlesWithTags();

        $pagination = $this->knp_paginator->paginate($articles, $requestQuery->getInt('page', 1), 5);

        return array(
            'articles'=>$pagination
        );
    }
}