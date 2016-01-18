<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Author;
use AppBundle\Entity\Tag;
use AppBundle\Form\ArticleType;
use AppBundle\Form\AuthorType;
use AppBundle\Form\TagType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin", name="admin")
 */
class AdminController extends BaseController
{
    /**
     * @Route("/", name="adminPage")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render("AppBundle:Admin:admin.html.twig");
    }

    /**
     * @Route("/authors", name="workWithAuthors")
     * @Method("GET")
     */
    public function workWithAuthorsAction()
    {
        $authors = $this->getDoctrine()->getRepository('AppBundle:Author')->findAll();

        return $this->render("AppBundle:Admin:admin-authors.html.twig", array(
            'authors' => $authors
        ));
    }

    /**
     * @Route("/articles", name="workWithArticles")
     * @Method("GET")
     */
    public function workWithArticlesAction()
    {
        $articles = $this->getDoctrine()->getRepository('AppBundle:Article')->findAll();

        return $this->render("AppBundle:Admin:admin-articles.html.twig", array(
            'articles' => $articles
        ));
    }

    /**
     * @Route("/tags", name="workWithTags")
     * @Method("GET")
     */
    public function workWithTagsAction()
    {
        $tags = $this->getDoctrine()->getRepository('AppBundle:Tag')->findAll();

        return $this->render("AppBundle:Admin:admin-tags.html.twig", array(
            'tags' => $tags
        ));
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
                $em = $this->getDoctrine()->getManager();
                $em->persist($author);
                $em->flush();

                return $this->redirectToRoute('workWithAuthors');
            }
        }


        return $this->render("AppBundle:Author:create-author.html.twig", array(
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

        if ($request->getMethod() == 'DELETE') {
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            $em->remove($author);
            $em->flush();
        }

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
                $em = $this->getDoctrine()->getManager();
                $em->merge($author);
                $em->flush();

                return $this->redirectToRoute('articlesByAuthor', array('authorId' => $author->getId()));
            }
        }


        return $this->render("AppBundle:Author:edit-author.html.twig", array(
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
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
            $author = $author = $this->getDoctrine()->getRepository('AppBundle:Author')->find(1);
            if ($author !== null) {
                $article->setAuthor($author);
            }

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                return $this->redirectToRoute('workWithArticles');
            }
        }


        return $this->render("AppBundle:Article:create-article.html.twig", array(
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


        return $this->render("AppBundle:Article:edit-article.html.twig", array(
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        ));
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
                $em = $this->getDoctrine()->getManager();
                $em->persist($tag);
                $em->flush();

                return $this->redirectToRoute('workWithTags');
            }
        }


        return $this->render("AppBundle:Tag:create-tag.html.twig", array(
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

        if ($request->getMethod() == 'DELETE') {
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            $em->remove($tag);
            $em->flush();
        }

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
                $em = $this->getDoctrine()->getManager();
                $em->persist($tag);
                $em->flush();

                return $this->redirectToRoute('articlesByTag', array('tagId' => $tag->getId()));
            }
        }


        return $this->render("AppBundle:Tag:edit-tag.html.twig", array(
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        ));
    }
}
