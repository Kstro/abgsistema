<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DGAbgSistemaBundle\Entity\AbgPersona;
use DGAbgSistemaBundle\Form\AbgPersonaType;

/**
 * AbgPersona controller.
 *
 * @Route("/abgpersona")
 */
class AbgPersonaController extends Controller
{
    /**
     * Lists all AbgPersona entities.
     *
     * @Route("/", name="abgpersona_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $abgPersonas = $em->getRepository('DGAbgSistemaBundle:AbgPersona')->findAll();

        return $this->render('abgpersona/index.html.twig', array(
            'abgPersonas' => $abgPersonas,
        ));
    }

    /**
     * Creates a new AbgPersona entity.
     *
     * @Route("/new", name="abgpersona_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $abgPersona = new AbgPersona();
        $form = $this->createForm('DGAbgSistemaBundle\Form\AbgPersonaType', $abgPersona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($abgPersona);
            $em->flush();

            return $this->redirectToRoute('abgpersona_show', array('id' => $abgPersona->getId()));
        }

        return $this->render('abgpersona/new.html.twig', array(
            'abgPersona' => $abgPersona,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AbgPersona entity.
     *
     * @Route("/{id}", name="abgpersona_show")
     * @Method("GET")
     */
    public function showAction(AbgPersona $abgPersona)
    {
        $deleteForm = $this->createDeleteForm($abgPersona);

        return $this->render('abgpersona/show.html.twig', array(
            'abgPersona' => $abgPersona,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AbgPersona entity.
     *
     * @Route("/{id}/edit", name="abgpersona_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AbgPersona $abgPersona)
    {
        $deleteForm = $this->createDeleteForm($abgPersona);
        $editForm = $this->createForm('DGAbgSistemaBundle\Form\AbgPersonaType', $abgPersona);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($abgPersona);
            $em->flush();

            return $this->redirectToRoute('abgpersona_edit', array('id' => $abgPersona->getId()));
        }

        return $this->render('abgpersona/edit.html.twig', array(
            'abgPersona' => $abgPersona,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AbgPersona entity.
     *
     * @Route("/{id}", name="abgpersona_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AbgPersona $abgPersona)
    {
        $form = $this->createDeleteForm($abgPersona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($abgPersona);
            $em->flush();
        }

        return $this->redirectToRoute('abgpersona_index');
    }

    /**
     * Creates a form to delete a AbgPersona entity.
     *
     * @param AbgPersona $abgPersona The AbgPersona entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AbgPersona $abgPersona)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('abgpersona_delete', array('id' => $abgPersona->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
