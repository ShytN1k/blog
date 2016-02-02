<?php

namespace AppBundle\Manager;

use AppBundle\Traits\WorkWithEntityTrait;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Knp\Component\Pager\Paginator;

class TagManager
{
    use WorkWithEntityTrait;

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

    public function manageTagsAsAdmin($requestQuery)
    {
        $tags = $this->doctrine->getRepository('AppBundle:Tag')->findAll();
        $pagination = $this->knp_paginator->paginate($tags, $requestQuery->getInt('page', 1), 10);

        return array(
            'tags' => $pagination
        );
    }
}