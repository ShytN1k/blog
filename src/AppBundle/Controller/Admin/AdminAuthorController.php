<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Author;
use AppBundle\Form\AuthorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/authors", name="adminAuthorController")
 */
class AdminAuthorController extends Controller
{
    /**
     * @Route("/", name="workWithAuthors")
     * @Method("GET")
     */
    public function workWithAuthorsAction(Request $request)
    {
        $authorManager = $this->get("app.manager.author");

        return $this->render("AppBundle:Admin:Author/admin-authors.html.twig",
            $authorManager->manageAuthorsAsAdmin($request->query)
        );
    }

    /**
     * @Route("/create-author", name="create-author")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAuthorAction(Request $request)
    {
        $author = new Author();
        $form = $this->createForm(new AuthorType(), $author);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $authorManager = $this->get("app.manager.author");
                $authorManager->flushEntityAsAdmin($author);

                return $this->redirectToRoute('workWithAuthors');
            }
        }


        return $this->render("AppBundle:Admin:Author/create-author.html.twig", array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/author/{id}", name="delete-author")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Author $author
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAuthorAction(Request $request, Author $author)
    {
        $form = $this->createDeleteAuthorForm($author);

        $authorManager = $this->get("app.manager.author");
        $authorManager->deleteEntityAsAdmin($request, $form, $author);

        return $this->redirectToRoute('workWithAuthors');
    }

    /**
     * @param Author $author
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteAuthorForm(Author $author)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('delete-author', array('id' => $author->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @Route("/author/{authorId}/edit", name="edit-author", requirements={"authorId" = "[0-9]+"})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param $authorId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAuthorAction(Request $request, $authorId)
    {
        /** @var Author $author */
        $author = $this->getDoctrine()->getRepository('AppBundle:Author')->find($authorId);
        $deleteForm = $this->createDeleteAuthorForm($author);
        $editForm = $this->createForm(new AuthorType(), $author);

        if ($request->getMethod() == 'POST') {
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                $authorManager = $this->get("app.manager.author");
                $newAuthorId = $authorManager->flushEntityAsAdmin($author);

                return $this->redirectToRoute('articlesByAuthor', array('authorId' => $newAuthorId));
            }
        }


        return $this->render("AppBundle:Admin:Author/edit-author.html.twig", array(
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        ));
    }
}