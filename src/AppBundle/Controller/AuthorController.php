<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends Controller
{
    /**
     * @Route("/{_locale}/author/{authorId}", name="articlesByAuthor", requirements={"_locale" : "en|ru|uk", "authorId" = "[0-9]+"}, defaults={"_locale" : "en"})
     * @Method("GET")
     */
    public function articlesByAuthorAction(Request $request, $authorId)
    {
        $authorManager = $this->get('app.manager.author');

        return $this->render("AppBundle:Author:articles-by-author.html.twig",
            $authorManager->getArticlesByAuthor($authorId, $request->query)
        );
    }
}
