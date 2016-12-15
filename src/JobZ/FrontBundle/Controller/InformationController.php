<?php

namespace JobZ\FrontBundle\Controller;

use JobZ\FrontBundle\Entity\Information;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class InformationController
 * @package JobZ\FrontBundle\Controller
 */
class InformationController extends Controller
{
    /**
     * @Route("/information/{slug}")
     */
    public function detailsAction($slug)
    {
        $infoRepo = $this->getDoctrine()->getManager()->getRepository(Information::class);
        $info = $infoRepo->findOneBy(array(
            'slug' => (string) $slug
        ));

        return $this->render('FrontBundle:FrontController:information.html.twig', array(
            'info' => $info
        ));
    }
}
