<?php
namespace AppBundle\Repositories;

use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    public function getTopArticles()
    {
        return $this->getEntityManager()->createQuery(
            "SELECT ar FROM AppBundle\Entity\Article ar ORDER BY ar.mark DESC"
        )
            ->setMaxResults(5)
            ->getResult();
    }

    public function getArticlesWithTags()
    {
        return $this->createQueryBuilder('a')
            ->select('a', 't')
            ->join('a.tags', 't')
            ->getQuery()
            ->getResult();
    }

    public function getArticlesForTag($tagId)
    {
        return $this->createQueryBuilder('a')
            ->select('a', 't')
            ->join('a.tags', 't')
            ->where('t.id = :tagId')
            ->setParameter('tagId', $tagId)
            ->getQuery()
            ->getResult();
    }

    public function getArticlesByAuthor($authorId)
    {
        return $this->createQueryBuilder('a')
            ->select('a', 'au')
            ->join('a.author', 'au')
            ->where('au.id = :authorId')
            ->setParameter('authorId', $authorId)
            ->getQuery()
            ->getResult();
    }

    public function getArticle($articleId)
    {
        return $this->createQueryBuilder('a')
            ->select('a', 'au', 'c')
            ->join('a.author', 'au')
            ->join('a.comments', 'c')
            ->orderBy('c.createdAt')
            ->where('a.id = :articleId')
            ->setParameter('articleId', $articleId)
            ->getQuery()
            ->getSingleResult();
    }
}