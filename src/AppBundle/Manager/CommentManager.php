<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Registry;

class CommentManager
{
    protected $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function addNewCommentToArticle($article, Comment $comment)
    {
        $author = $author = $this->doctrine->getRepository('AppBundle:Author')->find(1);
        if ($author !== null) {
            $comment->setAuthor($author);
        }
        $comment->setArticle($article);
        $em = $this->doctrine->getManager();
        $em->persist($comment);
        $em->flush();
    }
}