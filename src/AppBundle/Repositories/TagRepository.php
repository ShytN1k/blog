<?php
namespace AppBundle\Repositories;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
    public function getSidebarTags()
    {
        return $this->createQueryBuilder('t')
            ->select('t', 'a')
            ->join('t.articles', 'a')
            ->getQuery()
            ->getResult();
    }
}