<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DGAbgSistemaBundle\Entity\CtlCiudad;
use DGAbgSistemaBundle\Form\CtlCiudadType;

/**
 * CtlCiudad controller.
 *
 * @Route("/directorio")
 */
class DirectorioController extends Controller
{
    /**
     * Lists all CtlCiudad entities.
     *
     * @Route("/", name="directorio_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ctlCiudads = $em->getRepository('DGAbgSistemaBundle:CtlCiudad')->findAll();

        return $this->render('directorio/directorio.html.twig', array(
            'ctlCiudads' => $ctlCiudads,
        ));
    }

    /**
     * Creates a new CtlCiudad entity.
     *
     * @Route("/new", name="ctlciudad_new")
     * @Method({"GET", "POST"})
     */
//    public function newAction(Request $request)
//    {
//        $ctlCiudad = new CtlCiudad();
//        $form = $this->createForm('DGAbgSistemaBundle\Form\CtlCiudadType', $ctlCiudad);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($ctlCiudad);
//            $em->flush();
//
//            return $this->redirectToRoute('ctlciudad_show', array('id' => $ctlCiudad->getId()));
//        }
//
//        return $this->render('ctlciudad/new.html.twig', array(
//            'ctlCiudad' => $ctlCiudad,
//            'form' => $form->createView(),
//        ));
//    }

    /**
     * Finds and displays a CtlCiudad entity.
     *
     * @Route("/{id}", name="ctlciudad_show")
     * @Method("GET")
     */
//    public function showAction(CtlCiudad $ctlCiudad)
//    {
//        $deleteForm = $this->createDeleteForm($ctlCiudad);
//
//        return $this->render('ctlciudad/show.html.twig', array(
//            'ctlCiudad' => $ctlCiudad,
//            'delete_form' => $deleteForm->createView(),
//        ));
//    }

    /**
     * Displays a form to edit an existing CtlCiudad entity.
     *
     * @Route("/{id}/edit", name="ctlciudad_edit")
     * @Method({"GET", "POST"})
     */
//    public function editAction(Request $request, CtlCiudad $ctlCiudad)
//    {
//        $deleteForm = $this->createDeleteForm($ctlCiudad);
//        $editForm = $this->createForm('DGAbgSistemaBundle\Form\CtlCiudadType', $ctlCiudad);
//        $editForm->handleRequest($request);
//
//        if ($editForm->isSubmitted() && $editForm->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($ctlCiudad);
//            $em->flush();
//
//            return $this->redirectToRoute('ctlciudad_edit', array('id' => $ctlCiudad->getId()));
//        }
//
//        return $this->render('ctlciudad/edit.html.twig', array(
//            'ctlCiudad' => $ctlCiudad,
//            'edit_form' => $editForm->createView(),
//            'delete_form' => $deleteForm->createView(),
//        ));
//    }

    /**
     * Deletes a CtlCiudad entity.
     *
     * @Route("/{id}", name="ctlciudad_delete")
     * @Method("DELETE")
     */
//    public function deleteAction(Request $request, CtlCiudad $ctlCiudad)
//    {
//        $form = $this->createDeleteForm($ctlCiudad);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->remove($ctlCiudad);
//            $em->flush();
//        }
//
//        return $this->redirectToRoute('ctlciudad_index');
//    }

    /**
     * Creates a form to delete a CtlCiudad entity.
     *
     * @param CtlCiudad $ctlCiudad The CtlCiudad entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
//    private function createDeleteForm(CtlCiudad $ctlCiudad)
//    {
//        return $this->createFormBuilder()
//            ->setAction($this->generateUrl('ctlciudad_delete', array('id' => $ctlCiudad->getId())))
//            ->setMethod('DELETE')
//            ->getForm()
//        ;
//    }
}
