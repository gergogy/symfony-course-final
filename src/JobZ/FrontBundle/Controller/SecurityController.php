<?php

namespace JobZ\FrontBundle\Controller;

use JobZ\FrontBundle\Entity\User;
use JobZ\FrontBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

/**
 * Class SecurityController
 * @package JobZ\FrontBundle\Controller
 */
class SecurityController extends Controller
{
    /**
     * Login
     *
     * @param Request $request
     * @return Response
     *
     * @Route("/login")
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();
        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(Security::AUTHENTICATION_ERROR);
            $session->remove(Security::AUTHENTICATION_ERROR);
        }

        return $this->render(
            '@Front/Security/login.html.twig',
            array(
                'last_username' => $session->get(Security::LAST_USERNAME),
                'error' => $error
            )
        );
    }
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/register")
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $encoder = $this->get('security.password_encoder');
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('jobz_front_security_login');
        }
        return $this->render(
            '@Front/Security/register.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }
    /**
     * @Route("login_check")
     */
    public function loginCheckAction()
    {
    }

    /**
     * Logout
     *
     * @Route("logout")
     */
    public function logoutAction()
    {
    }
}