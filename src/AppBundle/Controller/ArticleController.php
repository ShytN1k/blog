<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends Controller
{
    /**
     * @Route("/{_locale}/article/{articleId}", name="articles", requirements={"_locale" : "en|ru|uk", "articleId" = "[0-9]+"}, defaults={"_locale" : "en"})
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request, $articleId)
    {
        $article = $this->getDoctrine()->getRepository('AppBundle:Article')->find($articleId);
        $commentManager = $this->get('app.manager.comments');
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $user = $this->getUser();
                $commentManager->addNewCommentToArticle($article, $comment, $user);

                return $this->redirectToRoute('articles', array('articleId' => $articleId));
            }
        }

        return $this->render("AppBundle:Article:show-article.html.twig", array(
            'article' => $article,
            'comment_form' => $form->createView(),
            'user' => $this->getUser()
        ));
    }
}
