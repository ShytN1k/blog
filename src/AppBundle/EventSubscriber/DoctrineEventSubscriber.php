<?php
namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Comment;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Article;
class DoctrineEventSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'prePersist',
            'preUpdate'
        );
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof Comment) {
            /** @var Article $article */
            $article = $entity->getArticle();
            $em  = $args->getEntityManager();
            $countComment = count($article->getComments());

            $calculatedMark = ($article->getMark()*($countComment-1) + $entity->getCommentMark()) / $countComment;
            $article->setMark(round($calculatedMark, 2));
            $em->merge($article);
            $em->flush();
        }
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof Comment || $entity instanceof Article) {
            $now = new \DateTime();
            $entity->setCreatedAt($now->format('Y-m-d H:i:s'));
            $entity->setUpdatedAt($now->format('Y-m-d H:i:s'));
        }
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof Comment || $entity instanceof Article) {
            $now = new \DateTime();
            $entity->setUpdatedAt($now->format('Y-m-d H:i:s'));
        }
    }
}