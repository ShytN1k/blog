<?php
namespace AppBundle\Repositories;

use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    public function getTopAticles()
    {
        return $this->getEntityManager()->createQuery(
            "SELECT ar FROM AppBundle\Entity\Article ar ORDER BY ar.mark DESC"
        )
            ->setMaxResults(5)
            ->getResult();
    }
}