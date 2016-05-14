<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DGAbgSistemaBundle\Entity\AdmImagenPromocion;
use DGAbgSistemaBundle\Form\AdmImagenPromocionType;

/**
 * AdmImagenPromocion controller.
 *
 * @Route("/admin/imagen-promocion")
 */
class AdmImagenPromocionController extends Controller
{
    /**
     * Lists all AdmImagenPromocion entities.
     *
     * @Route("/", name="admin_imagenpromocion_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $admImagenPromocions = $em->getRepository('DGAbgSistemaBundle:AdmImagenPromocion')->findAll();

        return $this->render('admimagenpromocion/index.html.twig', array(
            'admImagenPromocions' => $admImagenPromocions,
        ));
    }

    /**
     * Creates a new AdmImagenPromocion entity.
     *
     * @Route("/new", name="admin_imagenpromocion_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $admImagenPromocion = new AdmImagenPromocion();
        $form = $this->createForm('DGAbgSistemaBundle\Form\AdmImagenPromocionType', $admImagenPromocion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($admImagenPromocion);
            $em->flush();

            return $this->redirectToRoute('admin_imagenpromocion_show', array('id' => $admImagenPromocion->getId()));
        }

        return $this->render('admimagenpromocion/new.html.twig', array(
            'admImagenPromocion' => $admImagenPromocion,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdmImagenPromocion entity.
     *
     * @Route("/{id}", name="admin_imagenpromocion_show")
     * @Method("GET")
     */
    public function showAction(AdmImagenPromocion $admImagenPromocion)
    {
        $deleteForm = $this->createDeleteForm($admImagenPromocion);

        return $this->render('admimagenpromocion/show.html.twig', array(
            'admImagenPromocion' => $admImagenPromocion,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdmImagenPromocion entity.
     *
     * @Route("/{id}/edit", name="admin_imagenpromocion_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AdmImagenPromocion $admImagenPromocion)
    {
        $deleteForm = $this->createDeleteForm($admImagenPromocion);
        $editForm = $this->createForm('DGAbgSistemaBundle\Form\AdmImagenPromocionType', $admImagenPromocion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($admImagenPromocion);
            $em->flush();

            return $this->redirectToRoute('admin_imagenpromocion_edit', array('id' => $admImagenPromocion->getId()));
        }

        return $this->render('admimagenpromocion/edit.html.twig', array(
            'admImagenPromocion' => $admImagenPromocion,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdmImagenPromocion entity.
     *
     * @Route("/{id}", name="admin_imagenpromocion_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AdmImagenPromocion $admImagenPromocion)
    {
        $form = $this->createDeleteForm($admImagenPromocion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($admImagenPromocion);
            $em->flush();
        }

        return $this->redirectToRoute('admin_imagenpromocion_index');
    }

    /**
     * Creates a form to delete a AdmImagenPromocion entity.
     *
     * @param AdmImagenPromocion $admImagenPromocion The AdmImagenPromocion entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdmImagenPromocion $admImagenPromocion)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_imagenpromocion_delete', array('id' => $admImagenPromocion->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
