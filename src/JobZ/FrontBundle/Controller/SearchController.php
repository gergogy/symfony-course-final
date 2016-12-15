<?php

namespace JobZ\FrontBundle\Controller;

use JobZ\FrontBundle\Entity\Job;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SearchController
 * @package JobZ\FrontBundle\Controller
 * @Route("search")
 */
class SearchController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/")
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $keyword = $request->get('keywords');

        $data = array(
            'jobs' => array(),
            'keyword' => ''
        );

        if ($keyword) {
            $result = $em->getRepository(Job::class)->search(
                (string)$keyword
            );

            $jobs = array();
            /** @var Job $res */
            foreach ($result as $res) {
                $jobs[$res->getCategory()->getName()][] = $res;
            }

            $data['jobs'] = $jobs;
            $data['keyword'] = $keyword;
        }

        return $this->render('@Front/FrontController/home.html.twig', $data);
    }
}
