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
     * @Route("/abgpersona_show/{id}", name="abgpersona_show", options={"expose" =true})
     * @Method("GET")
     */
    public function showAction(AbgPersona $abgPersona) {

        //   $deleteForm = $this->createDeleteForm($abgPersona);

        $em = $this->getDoctrine()->getManager();
        $dql_persona = "SELECT  p.nombres AS nombre, p.apellido AS apellido "
                . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $abgPersona->getId();
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
            $abgPersona->setCorreoelectronico($datos['txtEmail']);
            $abgPersona->setFechaIngreso(new \DateTime("now"));
            $abgPersona->setEstado('1');
            $em->persist($abgPersona);
            $em->flush();
            $idPersona = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:AbgPersona')->find($abgPersona->getId());

            $ctlUsuario->setUsername($datos['txtUsername']);
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
    
    
   
    /**
     * @Route("/admin/{username}", name="admin_abg", options={"expose"=true})
     * @Method("GET")
     */
    public function AdminAbgAction($username) {
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction();
        $request = $this->getRequest();
        try {

            $RepositorioPersona = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlUsuario')->findByUsername($username); //->getRhPersona();
            $idPersona = $RepositorioPersona[0]->getRhPersona()->getId();

            $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo "
                    . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
            $result_persona = $em->createQuery($dql_persona)->getArrayResult();


            return $this->render('abgpersona/panelAdministrativoAbg.html.twig', array(
                        'abgPersona' => $result_persona,
                        'usuario' => $username,
            ));
        } catch (\Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
            $em->getConnection()->rollback();
            $em->close();

            // echo $e->getMessage();   
        }
    }

    /**
     * @Route("/admin/perfil/{username}", name="perfil", options={"expose"=true})
     * @Method("GET")
     */
    public function PerfilAction($username) {
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction();
        $request = $this->getRequest();
        try {

            $RepositorioPersona = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlUsuario')->findByUsername($username); //->getRhPersona();
            $idPersona = $RepositorioPersona[0]->getRhPersona()->getId();

            $dql_persona = "SELECT  p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo "
                    . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
            $result_persona = $em->createQuery($dql_persona)->getArrayResult();


            return $this->render('abgpersona/perfil.html.twig', array(
                        'abgPersona' => $result_persona,
                  'usuario' => $username,
            ));
        } catch (\Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
            $em->getConnection()->rollback();
            $em->close();

            // echo $e->getMessage();   
        }
    }
     /**
     * @Route("/admin/ajustes/{username}", name="ajustes", options={"expose"=true})
     * @Method("GET")
     */
    public function AjustesAction($username) {
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction();
               try {

            $RepositorioPersona = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlUsuario')->findByUsername($username); //->getRhPersona();
            $idPersona = $RepositorioPersona[0]->getRhPersona()->getId();

            $dql_persona = "SELECT  p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo "
                    . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
            $result_persona = $em->createQuery($dql_persona)->getArrayResult();


            return $this->render('abgpersona/panelAjustes.html.twig', array(
                        'abgPersona' => $result_persona,
                  'usuario' => $username,
            ));
        } catch (\Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
            $em->getConnection()->rollback();
            $em->close();

            // echo $e->getMessage();   
        }
    }

    /**
     * @Route("/edit/persona", name="edit_persona", options={"expose"=true})
     * @Method("POST")
     */
    public function EditPersonaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        try {
            $Persona = $em->getRepository("DGAbgSistemaBundle:AbgPersona")->find($request->get('hPersona'));
            switch ($request->get('n')) {
                case 0:
                    $Persona->setNombres($request->get('oficina'));
                    break;
                case 1:
                    $Persona->setApellido($request->get('oficina'));
                    break;
                case 2:
                    $Persona->setGenero($request->get('oficina'));
                    break;
                case 3:
                    $Persona->setDui($request->get('oficina'));
                    break;
                case 4:
                    $Persona->setNit($request->get('oficina'));
                    break;
                case 5:
                    $Persona->setCorreoelectronico($request->get('oficina'));
                    break;
                case 6:
                    $Persona->setDireccion($request->get('direccion'));
                    break;
                case 7:
                    $Persona->setTelefonoFijo($request->get('oficina'));
                    break;
                case 8:
                    $Persona->setTelefonoMovil($request->get('movil'));
                    break;
                case 9:
                     $idCiudad= $em->getRepository("DGAbgSistemaBundle:CtlCiudad")->find($request->get('ciudad'));
                    $Persona->setCtlCiudad($idCiudad);
                     $data['ciu'] = $Persona->getCtlCiudad()->getNombreCiudad();
                    break;
                case 10:
                    $Persona->setDescripcion($request->get('descripcion'));
                    break;
            }

            $em->merge($Persona);
            $em->flush();
           
            return new Response(json_encode($data));
        } catch (\Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/departamento", name="departamento", options={"expose"=true})
     * @Method("GET")
     */
    public function DepartamentoAction() {
        try {
            
            $em = $this->getDoctrine()->getManager();
            $dql_departamento = "SELECT  d.id AS id, d.nombreEstado AS nombre"
                              . " FROM DGAbgSistemaBundle:CtlEstado d";
            $data['depto'] = $em->createQuery($dql_departamento)->getArrayResult();

            return new Response(json_encode($data));
        } catch (\Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));  
        }
    }
    /**
     * @Route("/ciudad", name="ciudad", options={"expose"=true})
     * @Method("GET")
     */
    public function CiudadAction() {
        try {
            $request = $this->getRequest();
            $em = $this->getDoctrine()->getManager();
            $dql_departamento = "SELECT  c.id AS id, c.nombreCiudad AS nombre"
                              . " FROM DGAbgSistemaBundle:CtlCiudad c WHERE c.ctlEstado=".$request->get('estado');
            $data['ciudad'] = $em->createQuery($dql_departamento)->getArrayResult();

            return new Response(json_encode($data));
        } catch (\Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));  
        }
    }
     /**
     * @Route("/especialida", name="especialida", options={"expose"=true})
     * @Method("GET")
     */
    public function EspecialidaAction() {
        try {
            
            $em = $this->getDoctrine()->getManager();
            $dql_departamento = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre"
                              . " FROM DGAbgSistemaBundle:CtlEspecialidad e";
            $data['esp'] = $em->createQuery($dql_departamento)->getArrayResult();
           
            return new Response(json_encode($data));
        } catch (\Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));  
        }
    }
}
