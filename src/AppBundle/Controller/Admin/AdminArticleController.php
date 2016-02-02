<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/articles", name="adminArticleController")
 */
class AdminArticleController extends Controller
{
    /**
     * @Route("/", name="workWithArticles")
     * @Method("GET")
     */
    public function workWithArticlesAction(Request $request)
    {
        $articleManager = $this->get("app.manager.article");

        return $this->render("AppBundle:Admin:Article/admin-articles.html.twig",
            $articleManager->manageArticlesAsAdmin($request->query)
        );
    }

    /**
     * @Route("/create-article", name="create-article")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createArticleAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(new ArticleType(), $article);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $articleManager = $this->get("app.manager.article");
                $articleManager->createNewArticle($article);

                return $this->redirectToRoute('workWithArticles');
            }
        }


        return $this->render("AppBundle:Admin:Article/create-article.html.twig", array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/article/{id}", name="delete-article")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Article $article
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteArticleAction(Request $request, Article $article)
    {
        $form = $this->createDeleteArticleForm($article);

        $articleManager = $this->get("app.manager.article");
        $articleManager->deleteEntityAsAdmin($request, $form, $article);

        return $this->redirectToRoute('workWithArticles');
    }

    /**
     * @param Article $article
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteArticleForm(Article $article)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('delete-article', array('id' => $article->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @Route("/article/{articleId}/edit", name="edit-article", requirements={"articleId" = "[0-9]+"})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param $articleId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editArticleAction(Request $request, $articleId)
    {
        /** @var Article $article */
        $article = $this->getDoctrine()->getRepository('AppBundle:Article')->find($articleId);
        $deleteForm = $this->createDeleteArticleForm($article);
        $editForm = $this->createForm(new ArticleType(), $article);

        if ($request->getMethod() == 'POST') {
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                $articleManager = $this->get("app.manager.article");
                $newArticleId = $articleManager->flushEntityAsAdmin($article);

                return $this->redirectToRoute('articles', array('articleId' => $newArticleId));
            }
        }


        return $this->render("AppBundle:Admin:Article/edit-article.html.twig", array(
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        ));
    }
}