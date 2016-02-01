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
        $articles = $this->getDoctrine()->getRepository('AppBundle:Article')->findAll();

        $paging = $this->get('knp_paginator');
        $pagination = $paging->paginate($articles, $request->query->getInt('page', 1), 10);

        return $this->render("AppBundle:Admin:Article/admin-articles.html.twig", array(
            'articles' => $pagination
        ));
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
                $author = $author = $this->getDoctrine()->getRepository('AppBundle:Author')->find(1);
                if ($author !== null) {
                    $article->setAuthor($author);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

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

        if ($request->getMethod() == 'DELETE') {
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();
        }

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
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                return $this->redirectToRoute('articles', array('articleId' => $article->getId()));
            }
        }


        return $this->render("AppBundle:Admin:Article/edit-article.html.twig", array(
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        ));
    }
}