<?php

namespace AppBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Knp\Component\Pager\Paginator;

class TagManager
{
    protected $doctrine;

    protected $knp_paginator;

    public function __construct(Registry $doctrine, Paginator $knp_paginator)
    {
        $this->doctrine = $doctrine;
        $this->knp_paginator = $knp_paginator;
    }

    public function getArticlesByTag($tagId, $requestQuery)
    {
        $tag = $this->doctrine->getRepository('AppBundle:Tag')->find($tagId);
        $articles = $this->doctrine->getRepository('AppBundle:Article')->getArticlesForTag($tagId);
        $pagination = $this->knp_paginator->paginate($articles, $requestQuery->getInt('page', 1), 5);

        return array(
            'articles' => $pagination,
            'tag' => $tag
        );
    }
}