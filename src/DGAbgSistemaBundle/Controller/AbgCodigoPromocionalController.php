<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DGAbgSistemaBundle\Entity\AbgCodigoPromocional;
use DGAbgSistemaBundle\Form\AbgCodigoPromocionalType;

/**
 * AbgCodigoPromocional controller.
 *
 * @Route("/admin/codigos-promocionales")
 */
class AbgCodigoPromocionalController extends Controller
{
    /**
     * Lists all AbgCodigoPromocional entities.
     *
     * @Route("/", name="admin_codigos-promocionales_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $abgCodigoPromocionals = $em->getRepository('DGAbgSistemaBundle:AbgCodigoPromocional')->findAll();

        return $this->render('abgcodigopromocional/index.html.twig', array(
            'abgCodigoPromocionals' => $abgCodigoPromocionals,
        ));
    }

    /**
     * Creates a new AbgCodigoPromocional entity.
     *
     * @Route("/new", name="admin_codigos-promocionales_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $abgCodigoPromocional = new AbgCodigoPromocional();
        $form = $this->createForm('DGAbgSistemaBundle\Form\AbgCodigoPromocionalType', $abgCodigoPromocional);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($abgCodigoPromocional);
            $em->flush();

            return $this->redirectToRoute('admin_codigos-promocionales_show', array('id' => $abgCodigoPromocional->getId()));
        }

        return $this->render('abgcodigopromocional/new.html.twig', array(
            'abgCodigoPromocional' => $abgCodigoPromocional,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AbgCodigoPromocional entity.
     *
     * @Route("/{id}", name="admin_codigos-promocionales_show")
     * @Method("GET")
     */
    public function showAction(AbgCodigoPromocional $abgCodigoPromocional)
    {
        $deleteForm = $this->createDeleteForm($abgCodigoPromocional);

        return $this->render('abgcodigopromocional/show.html.twig', array(
            'abgCodigoPromocional' => $abgCodigoPromocional,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AbgCodigoPromocional entity.
     *
     * @Route("/{id}/edit", name="admin_codigos-promocionales_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AbgCodigoPromocional $abgCodigoPromocional)
    {
        $deleteForm = $this->createDeleteForm($abgCodigoPromocional);
        $editForm = $this->createForm('DGAbgSistemaBundle\Form\AbgCodigoPromocionalType', $abgCodigoPromocional);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($abgCodigoPromocional);
            $em->flush();

            return $this->redirectToRoute('admin_codigos-promocionales_edit', array('id' => $abgCodigoPromocional->getId()));
        }

        return $this->render('abgcodigopromocional/edit.html.twig', array(
            'abgCodigoPromocional' => $abgCodigoPromocional,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AbgCodigoPromocional entity.
     *
     * @Route("/{id}", name="admin_codigos-promocionales_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AbgCodigoPromocional $abgCodigoPromocional)
    {
        $form = $this->createDeleteForm($abgCodigoPromocional);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($abgCodigoPromocional);
            $em->flush();
        }

        return $this->redirectToRoute('admin_codigos-promocionales_index');
    }

    /**
     * Creates a form to delete a AbgCodigoPromocional entity.
     *
     * @param AbgCodigoPromocional $abgCodigoPromocional The AbgCodigoPromocional entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AbgCodigoPromocional $abgCodigoPromocional)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_codigos-promocionales_delete', array('id' => $abgCodigoPromocional->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
