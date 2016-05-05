<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DGAbgSistemaBundle\Entity\CtlPais;
use DGAbgSistemaBundle\Form\CtlPaisType;

/**
 * CtlPais controller.
 *
 * @Route("/ctlpais")
 */
class CtlPaisController extends Controller
{
    /**
     * Lists all CtlPais entities.
     *
     * @Route("/", name="ctlpais_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ctlPais = $em->getRepository('DGAbgSistemaBundle:CtlPais')->findAll();

        return $this->render('ctlpais/index.html.twig', array(
            'ctlPais' => $ctlPais,
        ));
    }

    /**
     * Creates a new CtlPais entity.
     *
     * @Route("/new", name="ctlpais_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $ctlPai = new CtlPais();
        $form = $this->createForm('DGAbgSistemaBundle\Form\CtlPaisType', $ctlPai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlPai);
            $em->flush();

            return $this->redirectToRoute('ctlpais_show', array('id' => $ctlPai->getId()));
        }

        return $this->render('ctlpais/new.html.twig', array(
            'ctlPai' => $ctlPai,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CtlPais entity.
     *
     * @Route("/{id}", name="ctlpais_show")
     * @Method("GET")
     */
    public function showAction(CtlPais $ctlPai)
    {
        $deleteForm = $this->createDeleteForm($ctlPai);

        return $this->render('ctlpais/show.html.twig', array(
            'ctlPai' => $ctlPai,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CtlPais entity.
     *
     * @Route("/{id}/edit", name="ctlpais_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CtlPais $ctlPai)
    {
        $deleteForm = $this->createDeleteForm($ctlPai);
        $editForm = $this->createForm('DGAbgSistemaBundle\Form\CtlPaisType', $ctlPai);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlPai);
            $em->flush();

            return $this->redirectToRoute('ctlpais_edit', array('id' => $ctlPai->getId()));
        }

        return $this->render('ctlpais/edit.html.twig', array(
            'ctlPai' => $ctlPai,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CtlPais entity.
     *
     * @Route("/{id}", name="ctlpais_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CtlPais $ctlPai)
    {
        $form = $this->createDeleteForm($ctlPai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ctlPai);
            $em->flush();
        }

        return $this->redirectToRoute('ctlpais_index');
    }

    /**
     * Creates a form to delete a CtlPais entity.
     *
     * @param CtlPais $ctlPai The CtlPais entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CtlPais $ctlPai)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ctlpais_delete', array('id' => $ctlPai->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
