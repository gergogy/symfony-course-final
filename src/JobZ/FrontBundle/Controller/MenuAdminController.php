<?php

namespace JobZ\FrontBundle\Controller;

use JobZ\FrontBundle\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Menu controller.
 *
 * @Route("admin/menu")
 */
class MenuAdminController extends Controller
{
    /**
     * Lists all menu entities.
     *
     * @Route("/")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $menus = $em->getRepository('FrontBundle:Menu')->findAll();

        return $this->render('@Front/Menu/index.html.twig', array(
            'menus' => $menus,
        ));
    }

    /**
     * Creates a new menu entity.
     *
     * @Route("/new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $menu = new Menu();
        $form = $this->createForm('JobZ\FrontBundle\Form\MenuType', $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($menu);
            $em->flush($menu);

            return $this->redirectToRoute('jobz_front_menuadmin_show', array('id' => $menu->getId()));
        }

        return $this->render('@Front/Menu/new.html.twig', array(
            'menu' => $menu,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a menu entity.
     *
     * @Route("/{id}")
     * @Method("GET")
     */
    public function showAction(Menu $menu)
    {
        $deleteForm = $this->createDeleteForm($menu);

        return $this->render('@Front/Menu/show.html.twig', array(
            'menu' => $menu,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing menu entity.
     *
     * @Route("/{id}/edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Menu $menu)
    {
        $deleteForm = $this->createDeleteForm($menu);
        $editForm = $this->createForm('JobZ\FrontBundle\Form\MenuType', $menu);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('jobz_front_menuadmin_edit', array('id' => $menu->getId()));
        }

        return $this->render('@Front/Menu/edit.html.twig', array(
            'menu' => $menu,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a menu entity.
     *
     * @Route("/{id}")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Menu $menu)
    {
        $form = $this->createDeleteForm($menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($menu);
            $em->flush($menu);
        }

        return $this->redirectToRoute('jobz_front_menuadmin_index');
    }

    /**
     * Creates a form to delete a menu entity.
     *
     * @param Menu $menu The menu entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Menu $menu)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('jobz_front_menuadmin_delete', array('id' => $menu->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
