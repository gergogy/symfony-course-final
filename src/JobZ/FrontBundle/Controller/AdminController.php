<?php

namespace JobZ\FrontBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class AdminController
 * @package JobZ\FrontBundle\Controller
 * @Route("admin")
 */
class AdminController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/")
     */
    public function defaultAction()
    {
        return $this->redirectToRoute('jobz_front_jobadmin_index');
    }
}