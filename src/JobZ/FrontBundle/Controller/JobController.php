<?php

namespace JobZ\FrontBundle\Controller;

use JobZ\FrontBundle\Entity\Job;
use JobZ\FrontBundle\Entity\User;
use JobZ\FrontBundle\Form\JobType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Job controller.
 *
 * @Route("job")
 */
class JobController extends Controller
{
    /**
     * Lists all job entities.
     *
     * @Route("/")
     * @Method("GET")
     */
    public function indexAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $jobs = $em->getRepository('FrontBundle:Job')->findBy(array(
            'email' => $user->getEmail()
        ));

        return $this->render('@Front/Job/index.html.twig', array(
            'jobs' => $jobs,
        ));
    }

    /**
     * Creates a new job entity.
     *
     * @Route("/new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        $job = new Job();
        $job->setEmail($user->getEmail());
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($job);
            $em->flush($job);

            return $this->redirectToRoute('jobz_front_job_show', array('id' => $job->getId()));
        }

        return $this->render('@Front/Job/new.html.twig', array(
            'job' => $job,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a job entity.
     *
     * @Route("/{id}")
     * @Method("GET")
     */
    public function showAction(Job $job)
    {
        $deleteForm = $this->createDeleteForm($job);

        return $this->render('@Front/Job/show.html.twig', array(
            'job' => $job,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing job entity.
     *
     * @Route("/{id}/edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Job $job)
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($job->getEmail() != $user->getEmail()) {
            throw new AccessDeniedException('You dont have permission to edit this job');
        }

        $deleteForm = $this->createDeleteForm($job);
        $editForm = $this->createForm(JobType::class, $job);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('jobz_front_job_edit', array('id' => $job->getId()));
        }

        return $this->render('FrontBundle:Job:edit.html.twig', array(
            'job' => $job,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a job entity.
     *
     * @Route("/{id}")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Job $job)
    {
        $form = $this->createDeleteForm($job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($job);
            $em->flush($job);
        }

        return $this->redirectToRoute('jobz_front_frontcontroller_home');
    }

    /**
     * Creates a form to delete a job entity.
     *
     * @param Job $job The job entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Job $job)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('jobz_front_job_delete', array('id' => $job->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @Route("/details/{id}")
     */
    public function detailsAction($id)
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
