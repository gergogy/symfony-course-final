<?php

namespace JobZ\FrontBundle\Controller;

use JobZ\FrontBundle\Entity\Category;
use JobZ\FrontBundle\Entity\Job;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class FrontControllerController extends Controller
{
    /**
     * @Route("/")
     */
    public function homeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository(Category::class)->findAll();
        $jobsRepository = $em->getRepository(Job::class);

        $jobs = array();
        foreach ($categories as $category) {
            $jobs[$category->getName()] = $jobsRepository->getRecentByCategories($category);
        }

        return $this->render('FrontBundle:FrontController:home.html.twig', array(
            'jobs' => $jobs
        ));
    }

    /**
     * @Route("/category/slug")
     */
    public function categoryAction()
    {
        return $this->render('FrontBundle:FrontController:category.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/information")
     */
    public function informationAction()
    {
        return $this->render('FrontBundle:FrontController:information.html.twig');
    }
}
