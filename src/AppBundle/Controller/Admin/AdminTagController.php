<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Tag;
use AppBundle\Form\TagType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/tags", name="adminTagController")
 */
class AdminTagController extends Controller
{
    /**
     * @Route("/", name="workWithTags")
     * @Method("GET")
     */
    public function workWithTagsAction(Request $request)
    {
        $tagManager = $this->get("app.manager.tag");

        return $this->render("AppBundle:Admin:Tag/admin-tags.html.twig",
            $tagManager->manageTagsAsAdmin($request->query)
        );
    }

    /**
     * @Route("/create-tag", name="create-tag")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createTagAction(Request $request)
    {
        $tag = new Tag();
        $form = $this->createForm(new TagType(), $tag);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $tagManager = $this->get("app.manager.tag");
                $tagManager->flushEntityAsAdmin($tag);

                return $this->redirectToRoute('workWithTags');
            }
        }


        return $this->render("AppBundle:Admin:Tag/create-tag.html.twig", array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/tag/{id}", name="delete-tag")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Tag $tag
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Tag $tag)
    {
        $form = $this->createDeleteTagForm($tag);

        $tagManager = $this->get("app.manager.tag");
        $tagManager->deleteEntityAsAdmin($request, $form, $tag);

        return $this->redirectToRoute('workWithTags');
    }

    /**
     * @param Tag $tag
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteTagForm(Tag $tag)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('delete-tag', array('id' => $tag->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @Route("/tag/{tagId}/edit", name="edit-tag", requirements={"tagId" = "[0-9]+"})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param $tagId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editTagAction(Request $request, $tagId)
    {
        /** @var Tag $tag */
        $tag = $this->getDoctrine()->getRepository('AppBundle:Tag')->find($tagId);
        $deleteForm = $this->createDeleteTagForm($tag);
        $editForm = $this->createForm(new TagType(), $tag);

        if ($request->getMethod() == 'POST') {
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                $tagManager = $this->get("app.manager.tag");
                $newTagId = $tagManager->flushEntityAsAdmin($tag);

                return $this->redirectToRoute('articlesByTag', array('tagId' => $newTagId));
            }
        }


        return $this->render("AppBundle:Admin:Tag/edit-tag.html.twig", array(
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        ));
    }
}
