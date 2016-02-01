<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Repositories\CommentRepository;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $defaultManager = $this->get('app.manager.default');

        return $this->render("AppBundle:Default:index.html.twig",
            $defaultManager->getAllArticles($request->query));
    }

    /**
     * @Route("/sidebar", name="sidebar")
     * @Method("GET")
     */
    public function sidebarAction()
    {
        $sidebarManager = $this->get('app.manager.sidebar');

        return $this->render("AppBundle:Default:sidebar.html.twig",
            $sidebarManager->getSidebar()
        );
    }
}
