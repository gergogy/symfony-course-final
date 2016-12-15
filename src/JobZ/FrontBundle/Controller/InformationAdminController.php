<?php

namespace JobZ\FrontBundle\Controller;

use JobZ\FrontBundle\Entity\Information;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Information controller.
 *
 * @Route("admin/information")
 */
class InformationAdminController extends Controller
{
    /**
     * Lists all information entities.
     *
     * @Route("/")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $information = $em->getRepository('FrontBundle:Information')->findAll();

        return $this->render('@Front/Information/index.html.twig', array(
            'information' => $information,
        ));
    }

    /**
     * Creates a new information entity.
     *
     * @Route("/new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $information = new Information();
        $form = $this->createForm('JobZ\FrontBundle\Form\InformationType', $information);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($information);
            $em->flush($information);

            return $this->redirectToRoute('jobz_front_informationadmin_show', array('id' => $information->getId()));
        }

        return $this->render('@Front/Information/new.html.twig', array(
            'information' => $information,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a information entity.
     *
     * @Route("/{id}")
     * @Method("GET")
     */
    public function showAction(Information $information)
    {
        $deleteForm = $this->createDeleteForm($information);

        return $this->render('@Front/Information/show.html.twig', array(
            'information' => $information,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing information entity.
     *
     * @Route("/{id}/edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Information $information)
    {
        $deleteForm = $this->createDeleteForm($information);
        $editForm = $this->createForm('JobZ\FrontBundle\Form\InformationType', $information);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('jobz_front_informationadmin_edit', array('id' => $information->getId()));
        }

        return $this->render('@Front/Information/edit.html.twig', array(
            'information' => $information,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a information entity.
     *
     * @Route("/{id}", name="admin_information_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Information $information)
    {
        $form = $this->createDeleteForm($information);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($information);
            $em->flush($information);
        }

        return $this->redirectToRoute('jobz_front_informationadmin_index');
    }

    /**
     * Creates a form to delete a information entity.
     *
     * @param Information $information The information entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Information $information)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_information_delete', array('id' => $information->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
