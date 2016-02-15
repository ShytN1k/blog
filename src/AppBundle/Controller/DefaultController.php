<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Author;
use AppBundle\Form\RegistrationAuthorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home_no_lang")
     * @Method("GET")
     */
    public function noLangAction(Request $request)
    {
        return $this->redirectToRoute("homepage");
    }

    /**
     * @Route("/{_locale}/", name="homepage", requirements={"_locale" : "en|ru|uk"}, defaults={"_locale" : "en"})
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $defaultManager = $this->get('app.manager.default');

        return $this->render(
            "AppBundle:Default:index.html.twig",
            $defaultManager->getAllArticles($request->query)
        );
    }

    /**
     * @Route("/{_locale}/sidebar", name="sidebar", requirements={"_locale" : "en|ru|uk"}, defaults={"_locale" : "en"})
     * @Method("GET")
     */
    public function sidebarAction()
    {
        $sidebarManager = $this->get('app.manager.sidebar');

        return $this->render(
            "AppBundle:Default:sidebar.html.twig",
            $sidebarManager->getSidebar()
        );
    }

    /**
     * @Route("/{_locale}/auth", name="auth", requirements={"_locale" : "en|ru|uk"}, defaults={"_locale" : "en"})
     * @Method("GET")
     */
    public function authorizationAction(Request $request)
    {
        $registration = ($request->get("registration") ? true : null);
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            "AppBundle:Default:auth.html.twig",
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
                'registration' => $registration
            )
        );
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction()
    {
    }

    /**
     * @Route("/{_locale}/register", name="user_registration", requirements={"_locale" : "en|ru|uk"}, defaults={"_locale" : "en"})
     * @Method({"GET", "POST"})
     */
    public function registerAction(Request $request)
    {
        $user = new Author();
        $form = $this->createForm(RegistrationAuthorType::class, $user);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $password = $this->get('security.password_encoder')
                    ->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute(
                    "auth",
                    array("registration" => true)
                );
            }
        }

        return $this->render(
            "AppBundle:Default:registration.html.twig",
            array('form' => $form->createView())
        );
    }

    /**
     *
     * @Route("/{_locale}/set_locale/", name="set_locale", requirements={"_locale" : "en|ru|uk"}, defaults={"_locale" : "en"})
     * @param Request $request
     * @param $_locale
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function setLocale(Request $request, $_locale)
    {
        $request->setLocale($_locale);
        return $this->redirectToRoute('homepage');
    }
}
