<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DGAbgSistemaBundle\Entity\CtlTituloProfesional;
use DGAbgSistemaBundle\Form\CtlTituloProfesionalType;

/**
 * CtlTituloProfesional controller.
 *
 * @Route("/ctltituloprofesional")
 */
class CtlTituloProfesionalController extends Controller
{
    /**
     * Lists all CtlTituloProfesional entities.
     *
     * @Route("/", name="ctltituloprofesional_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ctlTituloProfesionals = $em->getRepository('DGAbgSistemaBundle:CtlTituloProfesional')->findAll();

        return $this->render('ctltituloprofesional/index.html.twig', array(
            'ctlTituloProfesionals' => $ctlTituloProfesionals,
        ));
    }

    /**
     * Creates a new CtlTituloProfesional entity.
     *
     * @Route("/new", name="ctltituloprofesional_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $ctlTituloProfesional = new CtlTituloProfesional();
        $form = $this->createForm('DGAbgSistemaBundle\Form\CtlTituloProfesionalType', $ctlTituloProfesional);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlTituloProfesional);
            $em->flush();

            return $this->redirectToRoute('ctltituloprofesional_show', array('id' => $ctlTituloProfesional->getId()));
        }

        return $this->render('ctltituloprofesional/new.html.twig', array(
            'ctlTituloProfesional' => $ctlTituloProfesional,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CtlTituloProfesional entity.
     *
     * @Route("/{id}", name="ctltituloprofesional_show")
     * @Method("GET")
     */
    public function showAction(CtlTituloProfesional $ctlTituloProfesional)
    {
        $deleteForm = $this->createDeleteForm($ctlTituloProfesional);

        return $this->render('ctltituloprofesional/show.html.twig', array(
            'ctlTituloProfesional' => $ctlTituloProfesional,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CtlTituloProfesional entity.
     *
     * @Route("/{id}/edit", name="ctltituloprofesional_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CtlTituloProfesional $ctlTituloProfesional)
    {
        $deleteForm = $this->createDeleteForm($ctlTituloProfesional);
        $editForm = $this->createForm('DGAbgSistemaBundle\Form\CtlTituloProfesionalType', $ctlTituloProfesional);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlTituloProfesional);
            $em->flush();

            return $this->redirectToRoute('ctltituloprofesional_edit', array('id' => $ctlTituloProfesional->getId()));
        }

        return $this->render('ctltituloprofesional/edit.html.twig', array(
            'ctlTituloProfesional' => $ctlTituloProfesional,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CtlTituloProfesional entity.
     *
     * @Route("/{id}", name="ctltituloprofesional_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CtlTituloProfesional $ctlTituloProfesional)
    {
        $form = $this->createDeleteForm($ctlTituloProfesional);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ctlTituloProfesional);
            $em->flush();
        }

        return $this->redirectToRoute('ctltituloprofesional_index');
    }

    /**
     * Creates a form to delete a CtlTituloProfesional entity.
     *
     * @param CtlTituloProfesional $ctlTituloProfesional The CtlTituloProfesional entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CtlTituloProfesional $ctlTituloProfesional)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ctltituloprofesional_delete', array('id' => $ctlTituloProfesional->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
