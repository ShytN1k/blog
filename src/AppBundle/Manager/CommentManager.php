<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Comment;
use AppBundle\Traits\WorkWithEntityTrait;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Knp\Component\Pager\Paginator;

class CommentManager
{
    use WorkWithEntityTrait;

    protected $knp_paginator;

    protected $doctrine;

    public function __construct(Registry $doctrine, Paginator $knp_paginator)
    {
        $this->doctrine = $doctrine;
        $this->knp_paginator = $knp_paginator;
    }

    public function addNewCommentToArticle($article, Comment $comment, $author)
    {
        if ($author !== null) {
            $comment->setAuthor($author);
        }
        $comment->setArticle($article);
        $em = $this->doctrine->getManager();
        $em->persist($comment);
        $em->flush();
    }
}