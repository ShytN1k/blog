<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends BaseController
{
    /**
     * @Route("/article/{articleId}", name="articles", requirements={"articleId" = "[0-9]+"})
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request, $articleId)
    {
        $article = $this->getDoctrine()->getRepository('AppBundle:Article')->find($articleId);
        $comment = new Comment();
        $form = $this->createForm(new CommentType(), $comment);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $author = $author = $this->getDoctrine()->getRepository('AppBundle:Author')->find(1);
                if ($author !== null) {
                    $comment->setAuthor($author);
                }
                $comment->setArticle($article);
                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();

                return $this->redirectToRoute('articles', array('articleId' => $articleId));
            }
        }

        return $this->render("AppBundle:Article:show-article.html.twig", array(
            'article' => $article,
            'comment_form' => $form->createView()
        ));
    }
}
