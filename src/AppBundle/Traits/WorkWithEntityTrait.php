<?php

namespace AppBundle\Traits;

use Symfony\Component\HttpFoundation\Request;

trait WorkWithEntityTrait
{
    public function deleteEntityAsAdmin(Request $request, $form, $entity)
    {
        if ($request->getMethod() == 'DELETE') {
            $form->handleRequest($request);
            $em = $this->doctrine->getManager();
            $em->remove($entity);
            $em->flush();
        }
    }

    public function flushEntityAsAdmin($entity, $edit = false)
    {
        $em = $this->doctrine->getManager();
        if (!$edit){
            $em->persist($entity);
        }
        $em->flush();

        return $entity->getId();
    }
}