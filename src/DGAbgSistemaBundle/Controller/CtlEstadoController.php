<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DGAbgSistemaBundle\Entity\CtlEstado;
use DGAbgSistemaBundle\Form\CtlEstadoType;

/**
 * CtlEstado controller.
 *
 * @Route("/ctlestado")
 */
class CtlEstadoController extends Controller
{
    /**
     * Lists all CtlEstado entities.
     *
     * @Route("/", name="ctlestado_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ctlEstados = $em->getRepository('DGAbgSistemaBundle:CtlEstado')->findAll();

        return $this->render('ctlestado/index.html.twig', array(
            'ctlEstados' => $ctlEstados,
        ));
    }

    /**
     * Creates a new CtlEstado entity.
     *
     * @Route("/new", name="ctlestado_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $ctlEstado = new CtlEstado();
        $form = $this->createForm('DGAbgSistemaBundle\Form\CtlEstadoType', $ctlEstado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlEstado);
            $em->flush();

            return $this->redirectToRoute('ctlestado_show', array('id' => $ctlEstado->getId()));
        }

        return $this->render('ctlestado/new.html.twig', array(
            'ctlEstado' => $ctlEstado,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CtlEstado entity.
     *
     * @Route("/{id}", name="ctlestado_show")
     * @Method("GET")
     */
    public function showAction(CtlEstado $ctlEstado)
    {
        $deleteForm = $this->createDeleteForm($ctlEstado);

        return $this->render('ctlestado/show.html.twig', array(
            'ctlEstado' => $ctlEstado,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CtlEstado entity.
     *
     * @Route("/{id}/edit", name="ctlestado_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CtlEstado $ctlEstado)
    {
        $deleteForm = $this->createDeleteForm($ctlEstado);
        $editForm = $this->createForm('DGAbgSistemaBundle\Form\CtlEstadoType', $ctlEstado);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlEstado);
            $em->flush();

            return $this->redirectToRoute('ctlestado_edit', array('id' => $ctlEstado->getId()));
        }

        return $this->render('ctlestado/edit.html.twig', array(
            'ctlEstado' => $ctlEstado,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CtlEstado entity.
     *
     * @Route("/{id}", name="ctlestado_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CtlEstado $ctlEstado)
    {
        $form = $this->createDeleteForm($ctlEstado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ctlEstado);
            $em->flush();
        }

        return $this->redirectToRoute('ctlestado_index');
    }

    /**
     * Creates a form to delete a CtlEstado entity.
     *
     * @param CtlEstado $ctlEstado The CtlEstado entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CtlEstado $ctlEstado)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ctlestado_delete', array('id' => $ctlEstado->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
