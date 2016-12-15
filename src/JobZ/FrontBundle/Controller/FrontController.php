<?php

namespace JobZ\FrontBundle\Controller;

use JobZ\FrontBundle\Entity\Category;
use JobZ\FrontBundle\Entity\Information;
use JobZ\FrontBundle\Entity\Job;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class FrontController extends Controller
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
}
