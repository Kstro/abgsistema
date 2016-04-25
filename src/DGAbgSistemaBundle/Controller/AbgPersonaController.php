<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DGAbgSistemaBundle\Entity\AbgPersona;
use DGAbgSistemaBundle\Entity\CtlUsuario;
use DGAbgSistemaBundle\Entity\AbgSitioWeb;
use DGAbgSistemaBundle\Entity\AbgExperienciaLaboral;
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
        $result_sub = "";
        $result_especialida = "";
        $Experiencia="";
        try {

            $RepositorioPersona = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlUsuario')->findByUsername($username); //->getRhPersona();
            $idPersona = $RepositorioPersona[0]->getRhPersona()->getId();

            $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo "
                    . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
            $result_persona = $em->createQuery($dql_persona)->getArrayResult();
            
            //Esta consulta  es la que jala el src de la foto dejela
            
            $dqlfoto = "SELECT fot.src as src "
                . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and fot.tipoFoto=1";
            $result_foto = $em->createQuery($dqlfoto)->getArrayResult();
            
            

            if (($idPersona != null)) {

                $dql_especialida = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre"
                        . " FROM  DGAbgSistemaBundle:CtlEspecialidad e "
                        . "JOIN  DGAbgSistemaBundle:CtlSubespecialidad sub WHERE e.id=sub.abgEspecialidad "
                        . "JOIN DGAbgSistemaBundle:AbgPersonaSubespecialidad pe WHERE sub.id=pe.abgSubespecialidad AND pe.abgPersona=" . $idPersona
                        . " GROUP by e.id";
                $result_especialida = $em->createQuery($dql_especialida)->getArrayResult();

                if (count($result_especialida) > 0) {
                    $dsql_sub = "SELECT pe.id AS idSub,sub.abgSubespecialidadcol AS nombre, e.id AS idEsp  "
                            . "FROM  DGAbgSistemaBundle:AbgPersonaSubespecialidad pe "
                            . "JOIN  DGAbgSistemaBundle:CtlSubespecialidad sub WHERE  sub.id=pe.abgSubespecialidad AND  pe.abgPersona=" . $idPersona
                            . "JOIN  DGAbgSistemaBundle:CtlEspecialidad e WHERE e.id=sub.abgEspecialidad ";
                    $result_sub = $em->createQuery($dsql_sub)->getArrayResult();
                }
            }
  
            $dql_experiencia = "SELECT  el.id AS id, el.compania AS nombre, el.puesto AS puesto, el.fachaInicio AS fInicio, el.funcion AS funcion"
                    . " FROM  DGAbgSistemaBundle:AbgExperienciaLaboral el"
                    . " JOIN DGAbgSistemaBundle:AbgPersona p WHERE p.id=el.abgPersona and el.abgPersona=".$idPersona;

            $Experiencia= $em->createQuery($dql_experiencia)->getArrayResult();
            
           
            
            return $this->render('abgpersona/panelAdministrativoAbg.html.twig', array(
                        'abgPersona' => $result_persona,
                        'usuario' => $username,
                        'active' => 'perfil',
                        'RegistrosubEsp' => $result_sub,
                        'RegistroEspecialida' => $result_especialida,
                        'RegistradaExperiencia'=>  $Experiencia,
                //Este  array es el que manda el link de la foto
                        'abgFoto' =>$result_foto,
                
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

        try {

            $RepositorioPersona = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlUsuario')->findByUsername($username); //->getRhPersona();
            $idPersona = $RepositorioPersona[0]->getRhPersona()->getId();

            $dql_persona = "SELECT  p.id AS id,p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo "
                    . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
            $result_persona = $em->createQuery($dql_persona)->getArrayResult();




            $dql_departamento = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre"
                    . " FROM  DGAbgSistemaBundle:CtlEspecialidad e "
                    . "JOIN  DGAbgSistemaBundle:CtlSubespecialidad sub WHERE e.id=sub.abgEspecialidad group by e.id";
            $result_especialida = $em->createQuery($dql_departamento)->getArrayResult();
            return $this->render('abgpersona/panelAdministrativoAbg.html.twig', array(
                        'abgPersona' => $result_persona,
                        'usuario' => $username,
                        'active' => 'perfil',
            ));
        } catch (\Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));

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
                        'active' => 'ajuste',
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
                    $idCiudad = $em->getRepository("DGAbgSistemaBundle:CtlCiudad")->find($request->get('ciudad'));
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
     * @Route("/sitio", name="sitio", options={"expose"=true})
     * @Method("POST")
     */
    public function SitioAction() {
        try {
            $em = $this->getDoctrine()->getManager();
            $SitioWeb = new AbgSitioWeb();
            $request = $this->getRequest();
            $Persona = $em->getRepository("DGAbgSistemaBundle:AbgPersona")->find($request->get('hPersona'));

            $SitioWeb->setNombre($request->get('sitio'));
            $SitioWeb->setAbgPersona($Persona);
            $em->persist($SitioWeb);
            $em->flush();
            $data['sitio'] = $SitioWeb->getNombre();

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
                    . " FROM DGAbgSistemaBundle:CtlCiudad c WHERE c.ctlEstado=" . $request->get('estado');
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
            $n = 0;
            $subEspS = "";
            $em = $this->getDoctrine()->getManager();
            $request = $this->getRequest();
            $subEspecialidadesSeleccionadas = "";
            if (($request->get('hPersona') != null)) {
                $dql_especialida = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre"
                        . " FROM  DGAbgSistemaBundle:CtlEspecialidad e "
                        . "JOIN  DGAbgSistemaBundle:CtlSubespecialidad sub WHERE e.id=sub.abgEspecialidad "
                        . "JOIN DGAbgSistemaBundle:AbgPersonaSubespecialidad pe WHERE sub.id=pe.abgSubespecialidad AND pe.abgPersona=" . $request->get('hPersona')
                        . " GROUP by e.id";
                $subEspecialidadesSeleccionadas = $em->createQuery($dql_especialida)->getArrayResult();



                if (count($subEspecialidadesSeleccionadas) > 0) {
                    $sql = "select pe.abg_subespecialidad_id AS idEspSelect, sub.abg_subespecialidadcol AS nombre,sub.id AS idSub, e.id AS idEsp
                        from  marvinvi_abg.abg_persona_subespecialidad pe "
                            . " right join marvinvi_abg.ctl_subespecialidad sub on  sub.id=pe.abg_subespecialidad_id AND pe.abg_persona_id=" . $request->get('hPersona')
                            . " right join marvinvi_abg.ctl_especialidad e on e.id=sub.abg_especialidad_id 
                        group by  pe.id,pe.abg_subespecialidad_id, sub.abg_subespecialidadcol,sub.abg_especialidad_id";

                    $stm = $this->container->get('database_connection')->prepare($sql);
                    $stm->execute();
                    $subEspS = $stm->fetchAll();
                }
            }
            $dql_departamento = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre"
                    . " FROM  DGAbgSistemaBundle:CtlEspecialidad e "
                    . "JOIN  DGAbgSistemaBundle:CtlSubespecialidad sub WHERE e.id=sub.abgEspecialidad group by e.id";
            $result_especialida = $em->createQuery($dql_departamento)->getArrayResult();

            $dsql_sub = "SELECT e.id AS idEsp, e.nombreEspecialidad AS nombreEsp, sub.id AS idSub, sub.abgSubespecialidadcol AS nombreSub "
                    . " FROM  DGAbgSistemaBundle:CtlEspecialidad e "
                    . "JOIN  DGAbgSistemaBundle:CtlSubespecialidad sub WHERE e.id=sub.abgEspecialidad";
            $result_sub = $em->createQuery($dsql_sub)->getArrayResult();
            if ($n == 1) {
                $data['esp'] = $em->createQuery($dql_departamento)->getArrayResult();
                return new Response(json_encode($data));
            } else {
                return $this->render('abgpersona/especialidades.html.twig', array(
                            'abgEspecialida' => $result_especialida,
                            'subEsp' => $result_sub,
                            'subEspS' => $subEspS,
                            'especialidadesS' => $subEspecialidadesSeleccionadas,
                ));
            }
        } catch (\Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/subespecialida", name="subespecialida", options={"expose"=true})
     * @Method("GET")
     */
    public function SubespecialidaAction() {
        try {
            $request = $this->getRequest();
            $em = $this->getDoctrine()->getManager();
            $Persona = $em->getRepository("DGAbgSistemaBundle:AbgPersona")->find($request->get('hPersona'));
            $array = $request->get('SubEspecialida');

            $RepositorioSubEsp = $em->getRepository("DGAbgSistemaBundle:AbgPersonaSubespecialidad");
            if (is_null($RepositorioSubEsp->findBy(array('abgPersona' => $request->get('hPersona'))))) {
                
            } else {
                $PersonaSub = $RepositorioSubEsp->findBy(array('abgPersona' => $request->get('hPersona')));
                foreach ($PersonaSub as $obj) {
                    $em->remove($obj);
                    $em->flush();
                }
            }
            if (is_null($array)) {
                
            } else {
                foreach ($array as $obj) {
                    $PersonaSubespecialidad = new AbgPersonaSubespecialidad();
                    $idSub = $em->getRepository("DGAbgSistemaBundle:CtlSubespecialidad")->find(intval($obj));
                    $PersonaSubespecialidad->setAbgPersona($Persona);
                    $PersonaSubespecialidad->setAbgSubespecialidad($idSub);
                    $em->persist($PersonaSubespecialidad);
                    $em->flush();
                }
            }

            $dql_especialida = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre"
                    . " FROM  DGAbgSistemaBundle:CtlEspecialidad e "
                    . "JOIN  DGAbgSistemaBundle:CtlSubespecialidad sub WHERE e.id=sub.abgEspecialidad "
                    . "JOIN DGAbgSistemaBundle:AbgPersonaSubespecialidad pe WHERE sub.id=pe.abgSubespecialidad AND pe.abgPersona=" . $request->get('hPersona')
                    . " GROUP by e.id";

            $data['Esp'] = $em->createQuery($dql_especialida)->getArrayResult();

            $dsql_sub = "SELECT pe.id AS idSub,sub.abgSubespecialidadcol AS nombre, e.id AS idEsp  "
                    . "FROM  DGAbgSistemaBundle:AbgPersonaSubespecialidad pe "
                    . "JOIN  DGAbgSistemaBundle:CtlSubespecialidad sub WHERE  sub.id=pe.abgSubespecialidad AND  pe.abgPersona=" . $request->get('hPersona')
                    . "JOIN  DGAbgSistemaBundle:CtlEspecialidad e WHERE e.id=sub.abgEspecialidad ";
            $data['subEsp'] = $em->createQuery($dsql_sub)->getArrayResult();

            return new Response(json_encode($data));
        } catch (\Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/from_experiencia", name="from_experiencia", options={"expose"=true})
     * @Method("GET")
     */
    public function FromExperienciaAction() {
        try {
         
            return $this->render('abgpersona/experienciaLaboral.html.twig');
        } catch (\Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/registar_experiencia", name="registrar_experiencia", options={"expose"=true})
     * @Method("POST")
     */
    public function RegistraExperienciaAction() {

        try {
            $em = $this->getDoctrine()->getManager();
            $request = $this->getRequest();

            parse_str($request->get('dato'), $datos);

            $Persona = $em->getRepository("DGAbgSistemaBundle:AbgPersona")->find($request->get('hPersona'));

            $Experiencia = new AbgExperienciaLaboral();

            $fechaIni = date_create($datos['txtFechaIni']);
            $fechaFin = date_create($datos['txtFechaIni']);

            $Experiencia->setAbgPersona($Persona);
            $Experiencia->setCompania($datos['txtnombre']);
            $Experiencia->setFachaInicio($fechaIni);
            $Experiencia->setFechaFin($fechaFin);
            $Experiencia->setFuncion($datos['txtfuncion']);
            $Experiencia->setPuesto($datos['txtpuesto']);
           
            $em->persist($Experiencia);
            $em->flush();

            $dql_experiencia = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre"
                    . " FROM  DGAbgSistemaBundle:AbgExperienciaLaboral el"
                    . " JOIN DGAbgSistemaBundle:AbgPersona p WHERE p.id=el.abgPersona=" . $request->get('hPersona');


            $data['Exp'] = $em->createQuery($dql_experiencia)->getArrayResult();

            $data['msj'] = "Experiencia registrada";
            return new Response(json_encode($data));
        } catch (\Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }
    
    
    /**
     * @Route("/empresas/get", name="empresas", options={"expose"=true})
     * @Method("GET")
     */
    public function getEmpresasAction() {
        $em      = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $clue    = ltrim(strtolower($request->get('clue')), '0');
        $limit = $request->get('page_limit');
        $page = ($request->get('page') - 1) * 10;

        /*****************************************************************************************
         * SQL que obtiene el numero de expediente y nombre del paciente para asignar la cita
         ****************************************************************************************/

        $sql = "SELECT t01.id,
                       CONCAT_WS(' ', CONCAT(COALESCE(t01.numero, ''), ' - '), t02.primer_apellido, t02.segundo_apellido, t02.apellido_casada) || ', ' || CONCAT_WS(' ', t02.primer_nombre, t02.segundo_nombre, t02.tercer_nombre) AS text,
                       count(*) OVER() AS total
                FROM ctl_empresa t01
               
                WHERE t01.nombre_empresa ILIKE '%$clue%'
                    --   OR t02.apellido_completo_fonetico ~~* soundexesp('$clue') OR t02.nombre_completo_fonetico ~~* soundexesp('$clue')
                GROUP BY t01.id,
                       CONCAT_WS(' ', CONCAT(COALESCE(t01.numero, ''), ' - '), t02.primer_apellido, t02.segundo_apellido, t02.apellido_casada) || ', ' || CONCAT_WS(' ', t02.primer_nombre, t02.segundo_nombre, t02.tercer_nombre)
                ORDER BY text
                LIMIT $limit OFFSET $page";

        $stm  = $this->getDoctrine()->getManager()->getConnection()->prepare($sql);
        $stm->execute();
        $result = $stm->fetchAll();

        $citcita['data1'] = $result;
        $citcita['data2'] = count($result) > 0 ? $result[0]['total'] : 0;

        return new Response(json_encode($citcita));
    }

    

}
