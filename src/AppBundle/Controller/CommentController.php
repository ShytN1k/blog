<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    /**
     * @Route("/comment/{id}", name="delete-comment")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Comment $comment
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Comment $comment)
    {
        $form = $this->createDeleteCommentForm($comment);

        $commentManager = $this->get("app.manager.comments");
        $commentManager->deleteEntityAsAdmin($request, $form, $comment);

        return $this->redirectToRoute('articles', array('articleId' => $comment->getArticle()->getId()));
    }

    /**
     * @param Comment $comment
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteCommentForm(Comment $comment)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('delete-comment', array('id' => $comment->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @Route("/comment/{commentId}/edit", name="edit-comment", requirements={"commentId" = "[0-9]+"})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param $commentId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editCommentAction(Request $request, $commentId)
    {
        /** @var Comment $comment */
        $comment = $this->getDoctrine()->getRepository('AppBundle:Comment')->find($commentId);
        $mark = $comment->getCommentMark();
        $this->denyAccessUnlessGranted('edit', $comment);

        $deleteForm = $this->createDeleteCommentForm($comment);
        $editForm = $this->createForm(CommentType::class, $comment);

        if ($request->getMethod() == 'POST') {
            $editForm->handleRequest($request);
            $comment->setCommentMark($mark);

            if ($editForm->isValid()) {
                $commentManager = $this->get("app.manager.comments");
                $commentManager->flushEntityAsAdmin($comment, true);

                return $this->redirectToRoute('articles', array('articleId' => $comment->getArticle()->getId()));
            }
        }


        return $this->render("AppBundle:Comment:edit-comment.html.twig", array(
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'articleId' => $comment->getArticle()->getId(),
        ));
    }
}
