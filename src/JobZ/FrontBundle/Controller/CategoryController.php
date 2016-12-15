<?php

namespace JobZ\FrontBundle\Controller;

use JobZ\FrontBundle\Entity\Category;
use JobZ\FrontBundle\Entity\Job;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class CategoryController
 * @package JobZ\FrontBundle\Controller
 */
class CategoryController extends Controller
{
    /**
     * @Route("/category/{id}")
     */
    public function detailsAction(Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $jobsByCat = $em->getRepository(Job::class)->findBy(
            array(
                'category' => $category
            ),
            array(
                'createdAt' => 'DESC'
            )
        );

        return $this->render('FrontBundle:FrontController:category.html.twig', array(
            'category' => $category,
            'jobs' => $jobsByCat
        ));
    }
}
