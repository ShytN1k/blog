<?php
namespace AppBundle\Repositories;

use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
{
    public function getLastComments()
    {
        return $this->getEntityManager()->createQuery(
            "SELECT c FROM AppBundle\Entity\Comment c ORDER BY c.createdAt DESC"
        )->setMaxResults(5)->getResult();
    }
}