<?php

namespace JobZ\FrontBundle\Controller;

use JobZ\FrontBundle\Entity\Job;
use JobZ\FrontBundle\Entity\User;
use JobZ\FrontBundle\Form\JobType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class JobAdminController
 * @package JobZ\FrontBundle\Controller
 *
 * @Route("admin/job")
 */
class JobAdminController extends Controller
{
    /**
     * Lists all job entities.
     *
     * @Route("/")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $jobs = $em->getRepository('FrontBundle:Job')->findAll();

        return $this->render('@Front/Job/Admin/index.html.twig', array(
            'jobs' => $jobs,
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

        return $this->render('@Front/Job/Admin/show.html.twig', array(
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
        $deleteForm = $this->createDeleteForm($job);
        $editForm = $this->createForm(JobType::class, $job);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('jobz_front_jobadmin_edit', array('id' => $job->getId()));
        }

        return $this->render('@Front/Job/Admin/edit.html.twig', array(
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

        return $this->redirectToRoute('jobz_front_jobadmin_index');
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
            ->setAction($this->generateUrl('jobz_front_jobadmin_delete', array('id' => $job->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}