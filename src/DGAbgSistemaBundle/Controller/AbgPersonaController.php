<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DGAbgSistemaBundle\Entity\AbgPersona;
use DGAbgSistemaBundle\Entity\CtlUsuario;
use Symfony\Component\HttpFoundation\Response;
use DGAbgSistemaBundle\Form\AbgPersonaType;

/**
 * AbgPersona controller.
 *
 * @Route("/abgpersona")
 */
class AbgPersonaController extends Controller {

    /**
     * Lists all AbgPersona entities.
     *
     * @Route("/", name="abgpersona_index")
     * @Method("GET")
     */
    public function indexAction() {
        //$em = $this->getDoctrine()->getManager();
        //  $abgPersonas = $em->getRepository('DGAbgSistemaBundle:AbgPersona')->findAll();

        return $this->render('abgpersona/index.html.twig', array(
                        //   'abgPersonas' => $abgPersonas,
        ));
    }

    /**
     * Creates a new AbgPersona entity.
     *
     * @Route("/new", name="abgpersona_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
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
     * @Route("/perfil/{id}", name="abgpersona_show", options={"expose" =true})
     * @Method("GET")
     */
    public function showAction(AbgPersona $abgPersona) {
        $deleteForm = $this->createDeleteForm($abgPersona);
      
        $em = $this->getDoctrine()->getManager();
        $dql_persona = "SELECT  p.nombres AS nombre, p.apellido AS apellido "
                    . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=".$abgPersona->getId();
        $result_persona = $em->createQuery($dql_persona)->getArrayResult();
        
        return $this->render('abgpersona/perfil.html.twig', array(
                    'abgPersona' => $result_persona,
                   
        ));
    }

    /**
     * Displays a form to edit an existing AbgPersona entity.
     *
     * @Route("/{id}/edit", name="abgpersona_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AbgPersona $abgPersona) {
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
    public function deleteAction(Request $request, AbgPersona $abgPersona) {
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
    private function createDeleteForm(AbgPersona $abgPersona) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('abgpersona_delete', array('id' => $abgPersona->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

    /**
     * @Route("/usuario/get", name="usuario", options={"expose"=true})
     * @Method("GET")
     */
    public function UsuarioAction() {
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction();
        $request = $this->getRequest();
        try {

            parse_str($request->get('dato'), $datos);

            $abgPersona = new AbgPersona();
            $ctlUsuario = new CtlUsuario();
            $abgPersona->setNombres($datos['txtnombre']);
            $abgPersona->setApellido($datos['txtapellido']);
            $abgPersona->setFechaIngreso(new \DateTime("now"));
            $abgPersona->setEstado('1');
            $em->persist($abgPersona);
            $em->flush();
            $idPersona = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:AbgPersona')->find($abgPersona->getId());

            $ctlUsuario->setUsername($datos['txtEmail']);
            $ctlUsuario->setPassword($datos['txtPassword']);

            $ctlUsuario->setEstado('1');
            $ctlUsuario->setRhPersona($idPersona);

            $this->setSecurePassword($ctlUsuario, $datos['txtPassword']);
            $em->persist($ctlUsuario);
            $em->flush();
            $em->getConnection()->commit();
            $em->close();
            $data['msj'] = "Registrado";
            $data['username'] = $ctlUsuario->getUsername();
            return new Response(json_encode($data));
        } catch (\Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
            $em->getConnection()->rollback();
            $em->close();

            // echo $e->getMessage();   
        }
    }

    private function setSecurePassword(&$entity, $contrasenia) {
        $entity->setSalt(md5(time()));
        $encoder = new \Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword($contrasenia, $entity->getSalt());
        $entity->setPassword($password);
    }

}
