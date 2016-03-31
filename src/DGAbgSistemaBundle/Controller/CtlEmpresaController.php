<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DGAbgSistemaBundle\Entity\CtlEmpresa;
use DGAbgSistemaBundle\Form\CtlEmpresaType;
use Symfony\Component\HttpFoundation\Response;


/**
 * CtlEmpresa controller.
 *
 * @Route("/admin/ctlempresa")
 */
class CtlEmpresaController extends Controller
{
    /**
     * Lists all CtlEmpresa entities.
     *
     * @Route("/", name="admin_ctlempresa_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ctlEmpresas = $em->getRepository('DGAbgSistemaBundle:CtlEmpresa')->findAll();

        return $this->render('ctlempresa/index.html.twig', array(
            'ctlEmpresas' => $ctlEmpresas,
        ));
    }

    /**
     * Creates a new CtlEmpresa entity.
     *
     * @Route("/new", name="admin_ctlempresa_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $ctlEmpresa = new CtlEmpresa();
        $form = $this->createForm('DGAbgSistemaBundle\Form\CtlEmpresaType', $ctlEmpresa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlEmpresa);
            $em->flush();

            return $this->redirectToRoute('admin_ctlempresa_show', array('id' => $ctlEmpresa->getId()));
        }

        return $this->render('ctlempresa/new.html.twig', array(
            'ctlEmpresa' => $ctlEmpresa,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CtlEmpresa entity.
     *
     * @Route("/{id}", name="admin_ctlempresa_show")
     * @Method("GET")
     */
    public function showAction(CtlEmpresa $ctlEmpresa)
    {
        $deleteForm = $this->createDeleteForm($ctlEmpresa);

        return $this->render('ctlempresa/show.html.twig', array(
            'ctlEmpresa' => $ctlEmpresa,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CtlEmpresa entity.
     *
     * @Route("/{id}/edit", name="admin_ctlempresa_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CtlEmpresa $ctlEmpresa)
    {
        $deleteForm = $this->createDeleteForm($ctlEmpresa);
        $editForm = $this->createForm('DGAbgSistemaBundle\Form\CtlEmpresaType', $ctlEmpresa);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlEmpresa);
            $em->flush();

            return $this->redirectToRoute('admin_ctlempresa_edit', array('id' => $ctlEmpresa->getId()));
        }

        return $this->render('ctlempresa/edit.html.twig', array(
            'ctlEmpresa' => $ctlEmpresa,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CtlEmpresa entity.
     *
     * @Route("/{id}", name="admin_ctlempresa_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CtlEmpresa $ctlEmpresa)
    {
        $form = $this->createDeleteForm($ctlEmpresa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ctlEmpresa);
            $em->flush();
        }

        return $this->redirectToRoute('admin_ctlempresa_index');
    }

    /**
     * Creates a form to delete a CtlEmpresa entity.
     *
     * @param CtlEmpresa $ctlEmpresa The CtlEmpresa entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CtlEmpresa $ctlEmpresa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_ctlempresa_delete', array('id' => $ctlEmpresa->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
