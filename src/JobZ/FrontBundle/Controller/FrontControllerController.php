<?php

namespace JobZ\FrontBundle\Controller;

use JobZ\FrontBundle\Entity\Category;
use JobZ\FrontBundle\Entity\Information;
use JobZ\FrontBundle\Entity\Job;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class FrontControllerController extends Controller
{
    /**
     * @Route("/")
     */
    public function homeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $keyword = $request->get('keywords');

        if ($keyword) {
            $result = $em->getRepository(Job::class)->search(
                (string)$keyword
            );

            $jobs = array();
            /** @var Job $res */
            foreach ($result as $res) {
                $jobs[$res->getCategory()->getName()][] = $res;
            }

            $data = array(
                'jobs' => $jobs,
                'keyword' => $keyword
            );
        } else {
            $categories = $em->getRepository(Category::class)->findAll();
            $jobsRepository = $em->getRepository(Job::class);

            $jobs = array();
            foreach ($categories as $category) {
                $jobs[$category->getName()] = $jobsRepository->getRecentByCategories($category);
            }

            $data = array(
                'jobs' => $jobs
            );
        }

        return $this->render('FrontBundle:FrontController:home.html.twig', $data);
    }

    /**
     * @Route("/category/{id}")
     */
    public function categoryAction(Category $category)
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

    /**
     * @Route("/information/{slug}")
     */
    public function informationAction($slug)
    {
        $infoRepo = $this->getDoctrine()->getManager()->getRepository(Information::class);
        $info = $infoRepo->findOneBy(array(
            'slug' => (string) $slug
        ));

        return $this->render('FrontBundle:FrontController:information.html.twig', array(
            'info' => $info
        ));
    }

    /**
     * @Route("/details/{id}")
     */
    public function jobAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $job = $em->getRepository(Job::class)->findOneBy(
            array(
                'id' => (int)$id
            )
        );

        return $this->render('@Front/FrontController/job.html.twig', array(
            'job' => $job
        ));

    }
}
