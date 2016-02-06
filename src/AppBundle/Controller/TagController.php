<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller
{
    /**
     * @Route("/tag/{tagId}", name="articlesByTag", requirements={"tagId" = "[0-9]+"})
     * @Method("GET")
     */
    public function articlesByTagAction(Request $request, $tagId)
    {
        $tagManager = $this->get("app.manager.tag");

        return $this->render("AppBundle:Tag:articles-for-tag.html.twig",
            $tagManager->getArticlesByTag($tagId, $request->query)
        );
    }
}
