<?php

namespace DGAbgSistemaBundle\Controller;

use DateTime;
use DGAbgSistemaBundle\Entity\AbgCertificacion;
use DGAbgSistemaBundle\Entity\AbgEstudio;
use DGAbgSistemaBundle\Entity\AbgExperienciaLaboral;
use DGAbgSistemaBundle\Entity\AbgOrganizacion;
use DGAbgSistemaBundle\Entity\AbgPersona;
use DGAbgSistemaBundle\Entity\AbgPersonaEmpresa;
use DGAbgSistemaBundle\Entity\AbgPersonaEspecialida;
use DGAbgSistemaBundle\Entity\AbgPersonaIdioma;
use DGAbgSistemaBundle\Entity\AbgSitioWeb;
use DGAbgSistemaBundle\Entity\AbgUrlPersonalizada;
use DGAbgSistemaBundle\Entity\CtlUsuario;
use DGAbgSistemaBundle\Entity\Seminario;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * AbgPersona controller.
 *
 * @Route("admin/abgpersona")
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

        return $this->render(':Layout:index.html.twig', array(
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

        /*      $em = $this->getDoctrine()->getManager();
          $dql_persona = "SELECT  p.nombres AS nombre, p.apellido AS apellido "
          . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $abgPersona->getId();'nombres')
          $result_persona = $em->createQuery($dql_persona)->getArrayResult();

          return $this->render('abgpersona/perfil.html.twig', array(
          'abgPersona' => $result_persona,
          ));
         */
    }

    /**
     * Displays a form to edit an existing AbgPersona entity.
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
     * @return Form The form
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
            $abgPersona->setFechaIngreso(new DateTime("now"));
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
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
            $em->getConnection()->rollback();
            $em->close();

// echo $e->getMessage();   
        }
    }

    private function setSecurePassword(&$entity, $contrasenia) {
        $entity->setSalt(md5(time()));
        $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword($contrasenia, $entity->getSalt());
        $entity->setPassword($password);
    }

    /**
     * @Route("/inicio/", name="inicio", options={"expose"=true})
     * @Method("GET")
     */
    public function InicioAction() {
        try {
            $em = $this->getDoctrine()->getManager();
            if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
                $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();
                $username = $this->container->get('security.context')->getToken()->getUser()->getId();

                $sqlRol = "SELECT  r.id As id, r.rol As rol"
                        . " FROM  ctl_rol_usuario ru "
                        . " JOIN ctl_rol r ON r.id=ru.ctl_rol_id AND ru.ctl_usuario_id=" . $username;

                $stm = $this->container->get('database_connection')->prepare($sqlRol);
                $stm->execute();
                $RolUser = $stm->fetchAll();

                $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                        . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil, p.estado As estado, p.tituloProfesional AS tprofesional, p.verificado As verificado "
                        . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
                $result_persona = $em->createQuery($dql_persona)->getArrayResult();

                $nombreCorto = split(" ", $result_persona[0]['nombre'])[0] . " " . split(" ", $result_persona[0]['apellido'])[0];
                $dqlfoto = "SELECT fot.src as src "
                        . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
                $result_foto = $em->createQuery($dqlfoto)->getArrayResult();

                $dql_departamento = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre"
                        . " FROM  DGAbgSistemaBundle:CtlEspecialidad e ";
                $result_especialida = $em->createQuery($dql_departamento)->getArrayResult();

                $dql_departamento = "SELECT  d.id AS id, d.nombreEstado AS nombre"
                        . " FROM DGAbgSistemaBundle:CtlEstado d";
                $depto = $em->createQuery($dql_departamento)->getArrayResult();

                return $this->render('abgpersona/datosGenerales.html.twig', array(
                            'nombreCorto' => $nombreCorto,
                            'abgPersona' => $result_persona,
                            'usuario' => $username,
                            'active' => 'perfil',
                            'depto' => $depto,
                            'abgFoto' => $result_foto,
                            'especialida' => $result_especialida
                ));
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/informacion_general/", name="informacion_general", options={"expose"=true})
     * @Method("POST")
     */
    public function InformacionGeneralAction() {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->beginTransaction();
            $request = $this->getRequest();
            parse_str($request->get('datos'), $datos);
            $array = $request->get('esp');
 $data['msj'] = "";

            $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();
            $Persona = $em->getRepository("DGAbgSistemaBundle:AbgPersona")->find($idPersona);
            $Ciudad = $em->getRepository("DGAbgSistemaBundle:CtlCiudad")->find($datos['Smunicipio']);


            $Persona->setNombres($datos['txtnombre']);
            $Persona->setApellido($datos['txtapellido']);
            $Persona->setTituloProfesional($datos['Stitulo']);
            $Persona->setCtlCiudad($Ciudad);

            $em->merge($Persona);
            $em->flush();

            $RepositorioEsp = $em->getRepository("DGAbgSistemaBundle:AbgPersonaEspecialida");
    
         
            if (is_null($RepositorioEsp->findByAbgPersona($idPersona))) {
                
            } else {
                $PersonaEsp = $RepositorioEsp->findByAbgPersona($idPersona);
              
                foreach ($PersonaEsp as $obj) {
                    $em->remove($obj);
                    $em->flush();
                }
            }
            
            if (is_null($array)) {
                
            } else {
                foreach ($array as $obj) {
                    $PersonaEspecialidad = new AbgPersonaEspecialida();
                    $idSub = $em->getRepository("DGAbgSistemaBundle:CtlEspecialidad")->find(intval($obj['0']));
                    $PersonaEspecialidad->setAbgPersona($Persona);
                    $PersonaEspecialidad->setCtlEspecialidad($idSub);
                    $em->persist($PersonaEspecialidad);
                    $em->flush();
                }
            }
             $em->getConnection()->commit();
            $em->close();
            $data['msj'] = "Datos actualizados.";
            return new Response(json_encode($data));
        } catch (\Exception $e) {
            $data['error'] = $e->getMessage();
           $em->getConnection()->rollback();
            $em->close();
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/perfil/", name="perfil", options={"expose"=true})
     * @Method("GET")
     */
    public function PerfilAction() {
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction();
        $request = $this->getRequest();
        $result_sub = "";
        $result_especialida = "";
        $Experiencia = "";
        $Certificacion = "";
        $Curso = "";

        try {
            if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
                $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();
                $username = $this->container->get('security.context')->getToken()->getUser()->getId();

                $sqlRol = "SELECT  r.id As id, r.rol As rol"
                        . " FROM  ctl_rol_usuario ru "
                        . " JOIN ctl_rol r ON r.id=ru.ctl_rol_id AND ru.ctl_usuario_id=" . $username;

                $stm = $this->container->get('database_connection')->prepare($sqlRol);
                $stm->execute();
                $RolUser = $stm->fetchAll();


                $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                        . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil, p.estado As estado, p.tituloProfesional AS tprofesional, p.verificado As verificado "
                        . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
                $result_persona = $em->createQuery($dql_persona)->getArrayResult();

           
                $nombreCorto = split(" ", $result_persona[0]['nombre'])[0] . " " . split(" ", $result_persona[0]['apellido'])[0];


                $dql_ciudad = "SELECT c.nombreCiudad As nombre, es.nombreEstado estado"
                        . " FROM DGAbgSistemaBundle:AbgPersona p "
                        . " JOIN DGAbgSistemaBundle:CtlCiudad c WHERE p.ctlCiudad=c.id AND p.id=" . $idPersona
                        . " JOIN DGAbgSistemaBundle:CtlEstado es WHERE es.id=c.ctlEstado ";
                $result_ciuda = $em->createQuery($dql_ciudad)->getArrayResult();

                $dql_especialida = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre, pe.descripcion AS descripcion "
                        . " FROM  DGAbgSistemaBundle:CtlEspecialidad e "
                        . " JOIN DGAbgSistemaBundle:AbgPersonaEspecialida pe WHERE e.id=pe.ctlEspecialidad AND pe.abgPersona=" . $idPersona
                        . " GROUP by e.id "
                        . " ORDER BY e.nombreEspecialidad";
                $result_especialida = $em->createQuery($dql_especialida)->getArrayResult();


                $sql = "SELECT  el.id AS id, el.puesto AS puesto, el.compania AS empresa, el.funcion AS funcion,"
                        . "f.src AS src, DATEDIFF(el.fecha_fin,el.facha_inicio) AS dias, date_format(el.facha_inicio, '%M %Y') As fechaIn, "
                        . " date_format(el.fecha_fin, '%M %Y') As fechaFin, el.ubicacion AS hubicacion, urle.url AS url "
                        . " FROM  abg_experiencia_laboral el "
                        . " JOIN abg_persona p on p.id=el.abg_persona_id AND el.abg_persona_id=" . $idPersona
                        . " left JOIN ctl_empresa em on em.id=el.ctl_empresa_id "
                        . " left JOIN abg_foto AS f on f.ctl_empresa_id=em.id "
                        . " left JOIN abg_url_personalizada urle ON em.id=urle.ctl_empresa_id and urle.estado=1 "
                        . " GROUP by el.id,el.abg_persona_id,em.id"
                        . " ORDER BY el.facha_inicio Desc";
                $stm = $this->container->get('database_connection')->prepare($sql);
                $stm->execute();
                $Experiencia = $stm->fetchAll();




                $sqlEdu = "SELECT e.id AS idEs, e.institucion AS institucion, e.titulo AS titulo, e.anio_inicio AS anioIni, e.anio_graduacion AS anio, tp.abg_titulocol AS disciplina "
                        . " FROM abg_estudio e "
                        . " JOIN  abg_persona p ON e.abg_persona_id=p.id AND e.abg_persona_id=" . $idPersona
                        . " JOIN ctl_titulo_profesional tp ON tp.id=e.abg_titulo_profesional_id";
                $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                $stm->execute();
                $Edu = $stm->fetchAll();

                $sqlCert = "SELECT c.id AS id, c.certficacion_nombre AS nombre,c.institucion As institucion, "
                        . " date_format(c.fecha_inicio, '%M %Y') As fechaIn,date_format(c.fecha_fin, '%M %Y') AS fechaFin "
                        . " FROM  abg_certificacion c "
                        . " JOIN abg_persona p on p.id=c.abg_persona_id AND c.abg_persona_id=" . $idPersona
                        . " ORDER BY c.fecha_inicio";
                $stm = $this->container->get('database_connection')->prepare($sqlCert);
                $stm->execute();
                $Certificacion = $stm->fetchAll();

                $sqlCurso = "SELECT s.id AS id, s.nombre AS nombre,s.institucion As institucion, "
                        . " date_format(s.fecha_incio, '%M %Y') As fechaIn,date_format(s.fecha_fin, '%M %Y') AS fechaFin, s.descripcion AS descripcion "
                        . " FROM  seminario s "
                        . " JOIN abg_persona p on p.id=s.abg_persona_id AND s.abg_persona_id=" . $idPersona
                        . " ORDER BY s.fecha_incio";
                $stm = $this->container->get('database_connection')->prepare($sqlCurso);
                $stm->execute();
                $Curso = $stm->fetchAll();

                $sqlOrg = "SELECT org.id AS id, org.nombre AS nombre,org.puesto As puesto,org.descripcion AS descripcion, "
                        . " date_format(org.fecha_inicio, '%M %Y') As fechaIn,date_format(org.fecha_fin, '%M %Y') AS fechaFin"
                        . " FROM  abg_organizacion org "
                        . " JOIN abg_persona p on p.id=org.abg_persona_id AND org.abg_persona_id=" . $idPersona
                        . " ORDER BY org.fecha_inicio";
                $stm = $this->container->get('database_connection')->prepare($sqlOrg);
                $stm->execute();
                $Organizacion = $stm->fetchAll();

                $sqlEdu = "SELECT i.id As idIdioma,pi.id AS idPi,i.idioma As nombre, pi.nivel As nivel "
                        . " FROM abg_persona_idioma pi "
                        . " join ctl_idioma i on i.id=pi.ctl_idioma_id "
                        . " join abg_persona p on p.id=pi.abg_persona_id "
                        . " AND p.id=" . $idPersona
                        . " order by i.idioma";
                $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                $stm->execute();
                $Idiomas = $stm->fetchAll();

                $dql_sitio = "SELECT  w.id AS id, w.nombre AS nombre "
                        . " FROM  DGAbgSistemaBundle:AbgSitioWeb w "
                        . " JOIN DGAbgSistemaBundle:AbgPersona p WHERE p.id=w.abgPersona AND p.id=" . $idPersona;
                $sitio = $em->createQuery($dql_sitio)->getArrayResult();


                $dql_url = "SELECT  u.id AS id, u.url AS url "
                        . " FROM  DGAbgSistemaBundle:AbgUrlPersonalizada u "
                        . " JOIN DGAbgSistemaBundle:AbgPersona p WHERE p.id=u.abgPersona AND u.abgPersona=" . $idPersona;
                $url = $em->createQuery($dql_url)->getArrayResult();
//Esta consulta  es la que jala el src de la foto dejela

                $dqlfoto = "SELECT fot.src as src "
                        . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
                $result_foto = $em->createQuery($dqlfoto)->getArrayResult();


                $dqlfoto = "SELECT fot.src as src, fot.estado As estado "
                        . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and fot.tipoFoto=1";
                $fotoP = $em->createQuery($dqlfoto)->getArrayResult();

                $dqlNotificacion = "SELECT count(p.id) "
                        . " FROM DGAbgSistemaBundle:AbgFoto fot "
                        . " JOIN  DGAbgSistemaBundle:AbgPersona p WHERE p.id=fot.abgPersona  AND fot.tipoFoto=5 AND p.verificado=0";
                $NNotificaciones = $em->createQuery($dqlNotificacion)->getArrayResult();

                $cumplimiento = 0;
                if (count($result_persona) >= 1) {
                    $cumplimiento = 10;
                }
                if (count($result_especialida) >= 1) {
                    $cumplimiento = $cumplimiento + 10;
                }
                if (count($Experiencia) >= 1) {
                    $cumplimiento = $cumplimiento + 10;
                }
                if (count($Edu) >= 1) {
                    $cumplimiento = $cumplimiento + 10;
                }
                if (count($Certificacion) >= 1) {
                    $cumplimiento = $cumplimiento + 10;
                }
                if (count($Curso) >= 1) {
                    $cumplimiento = $cumplimiento + 10;
                }
                if (count($Organizacion) >= 1) {
                    $cumplimiento = $cumplimiento + 10;
                }
                if (count($Idiomas) >= 1) {
                    $cumplimiento = $cumplimiento + 10;
                }
                if (count($fotoP) >= 1) {
                    $cumplimiento = $cumplimiento + 10;
                }
                if (count($sitio) >= 1) {

                    $cumplimiento = $cumplimiento + 10;
                }
     return $this->render('abgpersona/panelAdministrativoAbg.html.twig', array(
                            'nombreCorto' => $nombreCorto,
                            'abgPersona' => $result_persona,
                            'usuario' => $username,
                            'active' => 'perfil',
                            'RegistrosubEsp' => $result_sub,
                            'RegistroEspecialida' => $result_especialida,
                            'RegistradaExperiencia' => $Experiencia,
                            'Edu' => $Edu,
                            'Certificacion' => $Certificacion,
                            'Curso' => $Curso,
                            'Organizacion' => $Organizacion,
                            'Idiomas' => $Idiomas,
                            'sitio' => $sitio,
                            'ciuda' => $result_ciuda,
                            'url' => $url,
                            'abgFoto' => $result_foto,
                            'cumplimiento' => $cumplimiento,
                            'NNotificaciones' => $NNotificaciones[0][1],
                                // 'ru'=>$RolUser[0]['id']
                ));
            } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();

                $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                        . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil, p.estado As estado, p.tituloProfesional AS tprofesional, p.verificado As verificado "
                        . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
                $result_persona = $em->createQuery($dql_persona)->getArrayResult();

                $nombreCorto = split(" ", $result_persona[0]['nombre'])[0] . " " . split(" ", $result_persona[0]['apellido'])[0];

                $dqlfoto = "SELECT fot.src as src "
                        . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
                $result_foto = $em->createQuery($dqlfoto)->getArrayResult();

                $fecha = date('Y-m-j');
                $nuevafecha = strtotime('-8 day', strtotime($fecha));
                $nuevafecha = date('Y-m-j', $nuevafecha);

                $rsm = new ResultSetMapping();
                $sql = "select count(per.id) as total "
                        . "from ctl_usuario usu "
                        . "inner join abg_persona per on usu.rh_persona_id = per.id "
                        . "inner join abg_foto foto on foto.abg_persona_id = per.id "
                        . "inner join abg_url_personalizada uper on uper.abg_persona_id = per.id "
                        . "where per.fecha_ingreso > '" . $nuevafecha . "' and per.fecha_ingreso <= '" . $fecha . "' and foto.estado = 1 and uper.estado = 1";
                //var_dump($sql);
                $rsm->addScalarResult('total', 'total');

                $totalAbogados = $em->createNativeQuery($sql, $rsm)
                        ->getSingleResult();

                $rsm2 = new ResultSetMapping();
                $sql2 = "select count(id) as total from abg_entrada where fecha >= '" . $nuevafecha . "' and fecha <= '" . $fecha . "'";
                $rsm2->addScalarResult('total', 'total');
                $totalBlogs = $em->createNativeQuery($sql2, $rsm2)
                        ->getSingleResult();

                $rsm3 = new ResultSetMapping();
                $sql3 = "select count(id) as total from abg_pregunta where fechapregunta >= '" . $nuevafecha . "' and fechapregunta <= '" . $fecha . "'";
                $rsm3->addScalarResult('total', 'total');
                $totalPreguntas = $em->createNativeQuery($sql3, $rsm3)
                        ->getSingleResult();

                $rsm4 = new ResultSetMapping();

                $sql4 = "select per.nombres as nombres, per.apellido as apellidos, foto.src as src, uper.url as url, fecha_ingreso as fecha
                        from ctl_usuario usu 
                        inner join abg_persona per on usu.rh_persona_id = per.id
                        inner join abg_foto foto on foto.abg_persona_id = per.id
                        inner join abg_url_personalizada uper on uper.abg_persona_id = per.id
                        where foto.estado = 1 and uper.estado = 1
                        order by fecha_ingreso desc, per.id desc
                        limit 0, 12";

                $rsm4->addScalarResult('nombres', 'nombres');
                $rsm4->addScalarResult('apellidos', 'apellidos');
                $rsm4->addScalarResult('src', 'src');
                $rsm4->addScalarResult('url', 'url');
                $rsm4->addScalarResult('fecha', 'fecha');

                $usuarios = $em->createNativeQuery($sql4, $rsm4)
                        ->getResult();

                $rsm5 = new ResultSetMapping();

                $sql5 = "select distinct emp.nombre_empresa as empresa, foto.src as src, uper.url as url, te.tipo_empresa as tipoEmpresa
                        from ctl_empresa emp 
                        inner join abg_foto foto on foto.ctl_empresa_id = emp.id
                        inner join abg_url_personalizada uper on uper.ctl_empresa_id = emp.id
                        left outer join ctl_tipo_empresa te on emp.ctl_tipo_empresa_id = te.id
                        where foto.estado = 1 and emp.nombre_empresa <> 'Nombre de la empresa' and uper.estado = 1
                        order by emp.id desc
                        limit 0, 8";

                $rsm5->addScalarResult('empresa', 'empresa');
                $rsm5->addScalarResult('src', 'src');
                $rsm5->addScalarResult('url', 'url');
                $rsm5->addScalarResult('tipoEmpresa', 'tipoEmpresa');

                $empresas = $em->createNativeQuery($sql5, $rsm5)
                        ->getResult();

                return $this->render('abgpersona/panelAdministrativoAbg.html.twig', array(
                            'nombreCorto' => $nombreCorto,
                            'abgPersona' => $result_persona,
                            'abgFoto' => $result_foto,
                            'totalAbogados' => $totalAbogados,
                            'totalBlogs' => $totalBlogs,
                            'totalPreguntas' => $totalPreguntas,
                            'usuarios' => $usuarios,
                            'empresas' => $empresas,
                ));
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));

// echo $e->getMessage();   
        }
    }

//Busqueda de perfiles de la empresa en base a la URL

    /**
     * Lists all CtlEmpresa entities.
     *
     * @Route("/empresaab/{url}", name="perfil_empresa_abogado", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function perfilEmpresAbogadoAction($url) {

        $em = $this->getDoctrine()->getManager();
        $ObjetoUrl = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:AbgUrlPersonalizada')->findByUrl($url);
        if (!empty($ObjetoUrl)) {

            $persona = $ObjetoUrl[0]->getAbgPersona();
            $empresa = $ObjetoUrl[0]->getCtlEmpresa();

            if ($empresa != null) {

//Completo los elementos de las visitas
                $entity = $em->getRepository('DGAbgSistemaBundle:AbgVisitas')->findBy(array("ctlEmpresa" => $empresa->getId()));
                $valor = $entity[0]->getVisita();

                $ctlEmpresaId = $empresa->getId();
                $contador = $valor + 1;
                $entity[0]->setVisita($contador);
                $em->merge($entity[0]);
                $em->flush();

//Coleccion de datos de la empresa

                $dqlempresa = "SELECT  e.nombreEmpresa AS nombreEmpresa, e.correoelectronico as correoEmpresa, e.direccion, e.sitioWeb,e.movil, e.telefono, e.color,e.cantidadEmpleados ,e.latitude, e.longitude ,"
                        . "date_format(e.fechaFundacion, '%Y') As fechaFundacion"
                        . " FROM DGAbgSistemaBundle:CtlEmpresa e WHERE e.id=" . $ctlEmpresaId;

                $result_empresa = $em->createQuery($dqlempresa)->getArrayResult();

//Valor de la foto de la empresa

                $dqlfoto = "SELECT fot.src as src "
                        . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.ctlEmpresa=" . $ctlEmpresaId . " and fot.estado=1 and fot.tipoFoto=1";
                $result_foto = $em->createQuery($dqlfoto)->getArrayResult();


//Array de si se lista o no dentro del perfil de la empresa
                $RepositorioListaEmpresa = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlEmpresa')->find($ctlEmpresaId); //->getRhPersona();
                $lista = $RepositorioListaEmpresa->getListaEmpleado();

                if ($lista) {
//Listar los empleados de la empresa
                    $sqlEmpEmp = "SELECT  emp.ctl_empresa_id, per.id as idPersona, per.nombres as nombres, per.apellido as apellido, per.correoelectronico as correoelectronico, "
                            . " per.telefono_fijo as telefonoFijo, per.telefono_movil as telefonoMovil, per.titulo_profesional AS tituloProfesional, "
                            . " per.id, fot.src, sw.nombre AS sitioWeb, per.verificado AS verificado, exp.puesto AS puesto, "
                            . " pesp.ctl_especialidad_id,GROUP_CONCAT(distinct (esp.nombre_especialidad)) AS especialida "
                            . " FROM  abg_foto fot "
                            . "JOIN  abg_persona per "
                            . "ON fot.abg_persona_id=per.id AND fot.tipo_foto=0 AND fot.tipo_foto <> 5 "
                            . " JOIN  abg_persona_empresa emp "
                            . " ON  emp.ctl_empresa_id=" . $ctlEmpresaId . " AND emp.abg_persona_id=per.id "
                            . "JOIN  abg_experiencia_laboral exp "
                            . " ON exp.ctl_empresa_id=" . $ctlEmpresaId
                            . " LEFT JOIN  abg_sitio_web sw "
                            . " ON per.id = sw.abg_persona_id "
                            . " LEFT JOIN  abg_persona_especialidad pesp "
                            . "ON pesp.abg_persona_id = per.id "
                            . "LEFT JOIN  ctl_especialidad esp "
                            . "ON pesp.ctl_especialidad_id = esp.id AND esp.id = pesp.ctl_especialidad_id "
                            . " GROUP BY pesp.abg_persona_id "
                            . " ORDER  BY per.nombres ASC";
                    $stm = $this->container->get('database_connection')->prepare($sqlEmpEmp);
                    $stm->execute();
                    $registro_empleados = $stm->fetchAll();
                } else {

                    $registro_empleados = null;
                }

//valor de los tipos de empresa  
                $dqlTipoEmpresa = "SELECT tipo.tipoEmpresa as tipoEmpresa  "
                        . "FROM DGAbgSistemaBundle:CtlEmpresa emp "
                        . "JOIN emp.ctlTipoEmpresa tipo "
                        . "WHERE emp.id =" . $ctlEmpresaId;

                $registro_tipoempresa = $em->createQuery($dqlTipoEmpresa)->getResult();

//metodo que me retorna Especialidades
                $dql_especialida = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre, pe.descripcion AS descripcion "
                        . " FROM  DGAbgSistemaBundle:CtlEspecialidad e "
                        . " JOIN DGAbgSistemaBundle:AbgPersonaEspecialida pe WHERE e.id=pe.ctlEspecialidad AND pe.ctlEmpresa=" . $ctlEmpresaId
                        . " GROUP by e.id "
                        . " ORDER BY e.nombreEspecialidad";
                $result_especialida = $em->createQuery($dql_especialida)->getArrayResult();

//Selccion de las URL personalizadas de los abogados de las empresas
                $dqlUrl = "SELECT per.id as idpersonaUrl, per.nombres, url.url  FROM DGAbgSistemaBundle:AbgUrlPersonalizada url "
                        . "JOIN url.abgPersona per  "
                        . "JOIN per.ctlEmpresa emp "
                        . " WHERE emp.id=" . $ctlEmpresaId
                        . " AND url.estado=1";

                $result_url = $em->createQuery($dqlUrl)->getArrayResult();


                return $this->render('ctlempresa/perfilGeneral.html.twig', array(
                            'ctlEmpresa' => $result_empresa,
                            'abgFoto' => $result_foto,
                            'ctlEmpresaId' => $ctlEmpresaId,
                            'empleados' => $registro_empleados,
                            'tipoEmpresa' => $registro_tipoempresa,
                            'visitas' => $valor,
                            'RegistroEspecialida' => $result_especialida,
                            'url' => $result_url
                ));
            } else {
// perfil persona
                $em = $this->getDoctrine()->getManager();
                $result_sub = "";
                $result_especialida = "";
                $Experiencia = "";
                $Certificacion = "";
                $Curso = "";

                try {
                    $idPersona = $ObjetoUrl[0]->getAbgPersona()->getId();
                    $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                            . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil, p.estado As estado,  p.tituloProfesional AS tprofesional,"
                            . " p.verificado As verificado "
                            . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
                    $result_persona = $em->createQuery($dql_persona)->getArrayResult();



                    $dql_ciudad = "SELECT c.nombreCiudad As nombre, es.nombreEstado estado"
                            . " FROM DGAbgSistemaBundle:AbgPersona p "
                            . " JOIN DGAbgSistemaBundle:CtlCiudad c WHERE p.ctlCiudad=c.id AND p.id=" . $idPersona
                            . " JOIN DGAbgSistemaBundle:CtlEstado es WHERE es.id=c.ctlEstado ";
                    $result_ciuda = $em->createQuery($dql_ciudad)->getArrayResult();

                    $dql_especialida = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre, pe.descripcion AS descripcion "
                            . " FROM  DGAbgSistemaBundle:CtlEspecialidad e "
                            . " JOIN DGAbgSistemaBundle:AbgPersonaEspecialida pe WHERE e.id=pe.ctlEspecialidad AND pe.abgPersona=" . $idPersona
                            . " GROUP by e.id "
                            . " ORDER BY e.nombreEspecialidad";
                    $result_especialida = $em->createQuery($dql_especialida)->getArrayResult();


                    $sql = "SELECT  el.id AS id, el.puesto AS puesto, el.compania AS empresa, el.funcion AS funcion,"
                            . "f.src AS src, DATEDIFF(el.fecha_fin,el.facha_inicio) AS dias, date_format(el.facha_inicio, '%M %Y') As fechaIn, "
                            . " date_format(el.fecha_fin, '%M %Y') As fechaFin, el.ubicacion AS hubicacion, urle.url AS url "
                            . " FROM   abg_experiencia_laboral el "
                            . " JOIN  abg_persona p on p.id=el.abg_persona_id AND el.abg_persona_id=" . $idPersona
                            . " left JOIN  ctl_empresa em on em.id=el.ctl_empresa_id "
                            . " left JOIN  abg_foto AS f on f.ctl_empresa_id=em.id"
                            . " left JOIN  abg_url_personalizada urle ON em.id=urle.ctl_empresa_id "
                            . " GROUP by el.id,el.abg_persona_id,em.id"
                            . " ORDER BY el.facha_inicio Desc";
                    $stm = $this->container->get('database_connection')->prepare($sql);
                    $stm->execute();
                    $Experiencia = $stm->fetchAll();

                    $sqlEdu = "SELECT e.id AS idEs, e.institucion AS institucion, e.titulo AS titulo, e.anio_inicio AS anioIni, e.anio_graduacion AS anio, tp.abg_titulocol AS disciplina "
                            . " FROM  abg_estudio e "
                            . " JOIN   abg_persona p ON e.abg_persona_id=p.id AND e.abg_persona_id=" . $idPersona
                            . " JOIN  ctl_titulo_profesional tp ON tp.id=e.abg_titulo_profesional_id "
                            . " ORDER BY e.anio_inicio Asc";
                    $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                    $stm->execute();
                    $Edu = $stm->fetchAll();

                    $sqlCert = "SELECT c.id AS id, c.certficacion_nombre AS nombre,c.institucion As institucion, "
                            . " date_format(c.fecha_inicio, '%M %Y') As fechaIn,date_format(c.fecha_fin, '%M %Y') AS fechaFin "
                            . " FROM   abg_certificacion c "
                            . " JOIN  abg_persona p on p.id=c.abg_persona_id AND c.abg_persona_id=" . $idPersona
                            . " ORDER BY c.fecha_inicio";
                    $stm = $this->container->get('database_connection')->prepare($sqlCert);
                    $stm->execute();
                    $Certificacion = $stm->fetchAll();

                    $sqlCurso = "SELECT s.id AS id, s.nombre AS nombre,s.institucion As institucion, "
                            . " date_format(s.fecha_incio, '%M %Y') As fechaIn,date_format(s.fecha_fin, '%M %Y') AS fechaFin, s.descripcion AS descripcion "
                            . " FROM   seminario s "
                            . " JOIN  abg_persona p on p.id=s.abg_persona_id AND s.abg_persona_id=" . $idPersona
                            . " ORDER BY s.fecha_incio";
                    $stm = $this->container->get('database_connection')->prepare($sqlCurso);
                    $stm->execute();
                    $Curso = $stm->fetchAll();

                    $sqlOrg = "SELECT org.id AS id, org.nombre AS nombre,org.puesto As puesto,org.descripcion AS descripcion, "
                            . " date_format(org.fecha_inicio, '%M %Y') As fechaIn,date_format(org.fecha_fin, '%M %Y') AS fechaFin"
                            . " FROM   abg_organizacion org "
                            . " JOIN  abg_persona p on p.id=org.abg_persona_id AND org.abg_persona_id=" . $idPersona
                            . " ORDER BY org.fecha_inicio";
                    $stm = $this->container->get('database_connection')->prepare($sqlOrg);
                    $stm->execute();
                    $Organizacion = $stm->fetchAll();

                    $sqlEdu = "SELECT i.id As idIdioma,pi.id AS idPi,i.idioma As nombre, pi.nivel As nivel "
                            . " FROM  abg_persona_idioma pi "
                            . " join  ctl_idioma i on i.id=pi.ctl_idioma_id "
                            . " join  abg_persona p on p.id=pi.abg_persona_id "
                            . " AND p.id=" . $idPersona
                            . " order by i.idioma";
                    $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                    $stm->execute();
                    $Idiomas = $stm->fetchAll();

                    $dql_sitio = "SELECT  w.id AS id, w.nombre AS nombre "
                            . " FROM  DGAbgSistemaBundle:AbgSitioWeb w "
                            . " JOIN DGAbgSistemaBundle:AbgPersona p WHERE p.id=w.abgPersona AND p.id=" . $idPersona;
                    $sitio = $em->createQuery($dql_sitio)->getArrayResult();

                    $dql_url = "SELECT  u.id AS id, u.url AS url "
                            . " FROM  DGAbgSistemaBundle:AbgUrlPersonalizada u "
                            . " JOIN DGAbgSistemaBundle:AbgPersona p WHERE p.id=u.abgPersona AND u.abgPersona=" . $idPersona;
                    $url = $em->createQuery($dql_url)->getArrayResult();

//Esta consulta  es la que jala el src de la foto dejela

                    $dqlfoto = "SELECT fot.src as src, fot.estado As estado "
                            . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
                    $result_foto = $em->createQuery($dqlfoto)->getArrayResult();

                    $dqlfoto = "SELECT fot.src as src, fot.estado As estado "
                            . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and fot.tipoFoto=1 ";
                    $fotoP = $em->createQuery($dqlfoto)->getArrayResult();


                    return $this->render('abgpersona/perfilGeneral.html.twig', array(
                                'abgPersona' => $result_persona,
                                'active' => 'verperfil',
                                'RegistrosubEsp' => $result_sub,
                                'RegistroEspecialida' => $result_especialida,
                                'RegistradaExperiencia' => $Experiencia,
                                'Edu' => $Edu,
                                'Certificacion' => $Certificacion,
                                'Curso' => $Curso,
                                'Organizacion' => $Organizacion,
                                'Idiomas' => $Idiomas,
                                'sitio' => $sitio,
                                'ciuda' => $result_ciuda,
                                'url' => $url,
                                'abgFoto' => $result_foto
                    ));
                } catch (\Exception $e) {
                    $data['msj'] = $e->getMessage();
                    return new Response(json_encode($data));
                }
            }
        } else {
            var_dump("Lo sentimos mucho esa url no existe");
        }
    }

    /**
     * @Route("/ver_perfil", name="ver_perfil", options={"expose"=true})
     * @Method("GET")
     */
    public function VerPerfilAction() {
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction();
        $request = $this->getRequest();
        $result_sub = "";
        $result_especialida = "";
        $Experiencia = "";
        $Certificacion = "";
        $Curso = "";

        try {
            $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();
            $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                    . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil, p.estado As estado,  p.tituloProfesional AS tprofesional,"
                    . " p.verificado As verificado "
                    . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
            $result_persona = $em->createQuery($dql_persona)->getArrayResult();

            $nombreCorto = split(" ", $result_persona[0]['nombre'])[0] . " " . split(" ", $result_persona[0]['apellido'])[0];

            $dql_ciudad = "SELECT c.nombreCiudad As nombre, es.nombreEstado estado"
                    . " FROM DGAbgSistemaBundle:AbgPersona p "
                    . " JOIN DGAbgSistemaBundle:CtlCiudad c WHERE p.ctlCiudad=c.id AND p.id=" . $idPersona
                    . " JOIN DGAbgSistemaBundle:CtlEstado es WHERE es.id=c.ctlEstado ";
            $result_ciuda = $em->createQuery($dql_ciudad)->getArrayResult();

            $dql_especialida = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre, pe.descripcion AS descripcion "
                    . " FROM  DGAbgSistemaBundle:CtlEspecialidad e "
                    . " JOIN DGAbgSistemaBundle:AbgPersonaEspecialida pe WHERE e.id=pe.ctlEspecialidad AND pe.abgPersona=" . $idPersona
                    . " GROUP by e.id "
                    . " ORDER BY e.nombreEspecialidad";
            $result_especialida = $em->createQuery($dql_especialida)->getArrayResult();


            $sql = "SELECT  el.id AS id, el.puesto AS puesto, el.compania AS empresa, el.funcion AS funcion,"
                    . "f.src AS src, DATEDIFF(el.fecha_fin,el.facha_inicio) AS dias, date_format(el.facha_inicio, '%M %Y') As fechaIn, "
                    . " date_format(el.fecha_fin, '%M %Y') As fechaFin, el.ubicacion AS hubicacion, urle.url AS url "
                    . " FROM  abg_experiencia_laboral el "
                    . " JOIN abg_persona p on p.id=el.abg_persona_id AND el.abg_persona_id=" . $idPersona
                    . " left JOIN ctl_empresa em on em.id=el.ctl_empresa_id "
                    . " left JOIN abg_foto AS f on f.ctl_empresa_id=em.id "
                    . " left JOIN abg_url_personalizada urle ON em.id=urle.ctl_empresa_id"
                    . " GROUP by el.id,el.abg_persona_id,em.id"
                    . " ORDER BY el.facha_inicio Desc";
            $stm = $this->container->get('database_connection')->prepare($sql);
            $stm->execute();
            $Experiencia = $stm->fetchAll();

            $sqlEdu = "SELECT e.id AS idEs, e.institucion AS institucion, e.titulo AS titulo, e.anio_inicio AS anioIni, e.anio_graduacion AS anio, tp.abg_titulocol AS disciplina "
                    . " FROM abg_estudio e "
                    . " JOIN  abg_persona p ON e.abg_persona_id=p.id AND e.abg_persona_id=" . $idPersona
                    . " JOIN ctl_titulo_profesional tp ON tp.id=e.abg_titulo_profesional_id "
                    . " ORDER BY e.anio_inicio Asc";
            $stm = $this->container->get('database_connection')->prepare($sqlEdu);
            $stm->execute();
            $Edu = $stm->fetchAll();

            $sqlCert = "SELECT c.id AS id, c.certficacion_nombre AS nombre,c.institucion As institucion, "
                    . " date_format(c.fecha_inicio, '%M %Y') As fechaIn,date_format(c.fecha_fin, '%M %Y') AS fechaFin "
                    . " FROM  abg_certificacion c "
                    . " JOIN abg_persona p on p.id=c.abg_persona_id AND c.abg_persona_id=" . $idPersona
                    . " ORDER BY c.fecha_inicio";
            $stm = $this->container->get('database_connection')->prepare($sqlCert);
            $stm->execute();
            $Certificacion = $stm->fetchAll();

            $sqlCurso = "SELECT s.id AS id, s.nombre AS nombre,s.institucion As institucion, "
                    . " date_format(s.fecha_incio, '%M %Y') As fechaIn,date_format(s.fecha_fin, '%M %Y') AS fechaFin, s.descripcion AS descripcion "
                    . " FROM  seminario s "
                    . " JOIN abg_persona p on p.id=s.abg_persona_id AND s.abg_persona_id=" . $idPersona
                    . " ORDER BY s.fecha_incio";
            $stm = $this->container->get('database_connection')->prepare($sqlCurso);
            $stm->execute();
            $Curso = $stm->fetchAll();

            $sqlOrg = "SELECT org.id AS id, org.nombre AS nombre,org.puesto As puesto,org.descripcion AS descripcion, "
                    . " date_format(org.fecha_inicio, '%M %Y') As fechaIn,date_format(org.fecha_fin, '%M %Y') AS fechaFin"
                    . " FROM  abg_organizacion org "
                    . " JOIN abg_persona p on p.id=org.abg_persona_id AND org.abg_persona_id=" . $idPersona
                    . " ORDER BY org.fecha_inicio";
            $stm = $this->container->get('database_connection')->prepare($sqlOrg);
            $stm->execute();
            $Organizacion = $stm->fetchAll();

            $sqlEdu = "SELECT i.id As idIdioma,pi.id AS idPi,i.idioma As nombre, pi.nivel As nivel "
                    . " FROM abg_persona_idioma pi "
                    . " join ctl_idioma i on i.id=pi.ctl_idioma_id "
                    . " join abg_persona p on p.id=pi.abg_persona_id "
                    . " AND p.id=" . $idPersona
                    . " order by i.idioma";
            $stm = $this->container->get('database_connection')->prepare($sqlEdu);
            $stm->execute();
            $Idiomas = $stm->fetchAll();

            $dql_sitio = "SELECT  w.id AS id, w.nombre AS nombre "
                    . " FROM  DGAbgSistemaBundle:AbgSitioWeb w "
                    . " JOIN DGAbgSistemaBundle:AbgPersona p WHERE p.id=w.abgPersona AND p.id=" . $idPersona;
            $sitio = $em->createQuery($dql_sitio)->getArrayResult();

            $dql_url = "SELECT  u.id AS id, u.url AS url "
                    . " FROM  DGAbgSistemaBundle:AbgUrlPersonalizada u "
                    . " JOIN DGAbgSistemaBundle:AbgPersona p WHERE p.id=u.abgPersona AND u.abgPersona=" . $idPersona;
            $url = $em->createQuery($dql_url)->getArrayResult();

//Esta consulta  es la que jala el src de la foto dejela

            $dqlfoto = "SELECT fot.src as src, fot.estado As estado "
                    . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
            $result_foto = $em->createQuery($dqlfoto)->getArrayResult();

            $dqlfoto = "SELECT fot.src as src, fot.estado As estado "
                    . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and fot.tipoFoto=1 ";
            $fotoP = $em->createQuery($dqlfoto)->getArrayResult();


            return $this->render('abgpersona/verPerfil.html.twig', array(
                        'nombreCorto' => $nombreCorto,
                        'abgPersona' => $result_persona,
                        'active' => 'verperfil',
                        'RegistrosubEsp' => $result_sub,
                        'RegistroEspecialida' => $result_especialida,
                        'RegistradaExperiencia' => $Experiencia,
                        'Edu' => $Edu,
                        'Certificacion' => $Certificacion,
                        'Curso' => $Curso,
                        'Organizacion' => $Organizacion,
                        'Idiomas' => $Idiomas,
                        'sitio' => $sitio,
                        'ciuda' => $result_ciuda,
                        'url' => $url,
                        'abgFoto' => $result_foto
            ));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/ajustes/{username}", name="ajustes", options={"expose"=true})
     * @Method("GET")
     */
    public function AjustesAction($username) {
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction();
        try {
            $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();
            $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                    . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil, p.estado As estado, p.codigo as codigo "
                    . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
            $result_persona = $em->createQuery($dql_persona)->getArrayResult();
            $nombreCorto = split(" ", $result_persona[0]['nombre'])[0] . " " . split(" ", $result_persona[0]['apellido'])[0];

            $dql_tipoPago = "SELECT p.id as id, p.tipoPago As nombre "
                    . " FROM DGAbgSistemaBundle:CtlTipoPago p ORDER BY p.tipoPago ASC";
            $TipoPago = $em->createQuery($dql_tipoPago)->getArrayResult();


            $dqlfoto = "SELECT fot.src as src "
                    . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
            $result_foto = $em->createQuery($dqlfoto)->getArrayResult();


            $dqlfoto = "SELECT fot.src as src, fot.estado As estado "
                    . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and fot.tipoFoto=1 ";
            $fotoP = $em->createQuery($dqlfoto)->getArrayResult();


            return $this->render('abgpersona/panelAjustes.html.twig', array(
                        'nombreCorto' => $nombreCorto,
                        'abgPersona' => $result_persona,
                        'usuario' => $idPersona,
                        'TipoPago' => $TipoPago,
                        'abgFoto' => $result_foto,
            ));
        } catch (Exception $e) {
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
        $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();

        try {
            $Persona = $em->getRepository("DGAbgSistemaBundle:AbgPersona")->find($idPersona);
            switch ($request->get('n')) {
                case 0:
                    parse_str($request->get('dato'), $datos);

                    $Persona->setTelefonoFijo($datos['txtFijo']);
                    if ((($datos['txtMovil'] !== ""))) {
                        $Persona->setTelefonoMovil($datos['txtMovil']);
                    } else {
                        $Persona->setTelefonoMovil(null);
                    }
                    $Persona->setCorreoelectronico($datos['txtEmail']);
                    $Persona->setDireccion($datos['txtDirecion']);


                    if ((($datos['txtsitio'] !== ""))) {
                        $IdSitio = $em->getRepository("DGAbgSistemaBundle:AbgSitioWeb")->findByAbgPersona($Persona->getId());
                        $SitioRegistrado = $em->getRepository("DGAbgSistemaBundle:AbgSitioWeb")->find($IdSitio[0]->getId());
                        $SitioRegistrado->setNombre($datos['txtsitio']);
                        $em->merge($SitioRegistrado);
                        $em->flush();
                    }

                    $data['msj'] = "Datos actualizados";
                    break;
                case 1:
                    $Persona->setNombres($request->get('nombres')['txtnombre']);
                    $Persona->setApellido($request->get('nombres')['txtApellido']);
                    break;
                case 2:
                    $Persona->setEstado($request->get('estado'));
                    break;

                case 3:
                    $Persona->setTituloProfesional($request->get('tituloProfesional'));

                    break;
                case 4:


                    $Persona = $em->getRepository("DGAbgSistemaBundle:AbgPersona")->find($request->get('hPersona'));

                    $Persona->setVerificado($request->get('estado'));
                    if ($request->get('estado') === '1') {
                        $data['msj'] = "Abogado verificado";
                    } else {
                        $data['msj'] = "Abogado no verificado";
                    }

                    break;
                case 5:
                    $Persona->setCorreoelectronico($request->get('correo'));
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
                    $Ciudad = $em->getRepository("DGAbgSistemaBundle:CtlCiudad");
                    $idCiudad = $Ciudad->find($request->get('ciudad'));

                    $Persona->setCtlCiudad($idCiudad);
                    $data['ciu'] = $Persona->getCtlCiudad()->getNombreCiudad();
                    $data['dept'] = $Ciudad->find($request->get('ciudad'))->getCtlEstado()->getNombreEstado();
                    break;
                case 10:
                    $Persona->setDescripcion($request->get('descripcion'));
                    $data['msj'] = "Dato actualizado";
                    break;
            }

            $em->merge($Persona);
            $em->flush();

            return new Response(json_encode($data));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/datos_contacto", name="datos_contacto", options={"expose"=true})
     * @Method("GET")
     */
    public function getDatosContactoAction() {

        try {
            $em = $this->getDoctrine()->getManager();

//  $idPersona = 93;
            $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();
            $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                    . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil "
                    . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
            $result_persona = $em->createQuery($dql_persona)->getArrayResult();

            $dql_sitio = "SELECT  w.id AS id, w.nombre AS nombre "
                    . " FROM  DGAbgSistemaBundle:AbgSitioWeb w "
                    . " JOIN DGAbgSistemaBundle:AbgPersona p WHERE p.id=w.abgPersona AND p.id=" . $idPersona;
            $sitio = $em->createQuery($dql_sitio)->getArrayResult();

            return $this->render('abgpersona/datos_contacto.html.twig', array(
                        'result_persona' => $result_persona,
                        'sitio' => $sitio,
            ));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));


// echo $e->getMessage();   
        }
    }

    /**
     * @Route("/sobremi", name="sobremi", options={"expose"=true})
     * @Method("GET")
     */
    public function getSobremiAction() {

        try {
            $em = $this->getDoctrine()->getManager();


//  $idPersona = 93;
            $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();
            $dql_persona = "SELECT  p.id AS id, p.descripcion AS  descripcion,"
                    . " p.direccion AS direccion "
                    . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
            $result_persona = $em->createQuery($dql_persona)->getArrayResult();


            return $this->render('abgpersona/sobreMi.html.twig', array(
                        'result_persona' => $result_persona,
            ));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));


// echo $e->getMessage();   
        }
    }

    /**
     * @Route("/url_persona", name="url_persona", options={"expose"=true})
     * @Method("POST")
     */
    public function UrlPersonaAction() {
        try {
            $em = $this->getDoctrine()->getManager();
            $Url = new AbgUrlPersonalizada();
            $request = $this->getRequest();
            $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();

            if (($request->get('url') != null)) {
                $urlReg = $em->getRepository("DGAbgSistemaBundle:AbgUrlPersonalizada");
                $array = $urlReg->findBy(array('abgPersona' => $request->get('hPersona')));

                if (count($array) > 0) {

                    $UrlPerReg = $this->getDoctrine()->getRepository("DGAbgSistemaBundle:AbgUrlPersonalizada")->findByUrl($array[0]->getUrl());

                    $id = $UrlPerReg[0]->getId();

                    $UrlPersonalizadaReg = $em->getRepository("DGAbgSistemaBundle:AbgUrlPersonalizada")->find($id);
                    $UrlPersonalizadaReg->setUrl($request->get('url'));
                    $em->merge($UrlPersonalizadaReg);
                    $em->flush();
                } else {
                    $Url->setUrl($request->get('url'));
                    $Url->setAbgPersona($Persona);
                    $Url->setEstado('1');
                    $em->persist($Url);
                    $em->flush();
                    $data['msj'] = "registrado";
                }
            }


            return new Response(json_encode($data));
        } catch (Exception $e) {
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

            $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();

            $Persona = $em->getRepository("DGAbgSistemaBundle:AbgPersona")->find($idPersona);
            if (($request->get('sitio') != null)) {
                $urlReg = $em->getRepository("DGAbgSistemaBundle:AbgSitioWeb");
                $array = $urlReg->findBy(array('abgPersona' => $request->get('hPersona')));

                if (count($array) > 0) {

                    $SitioPerReg = $this->getDoctrine()->getRepository("DGAbgSistemaBundle:AbgSitioWeb")->findByNombre($array[0]->getNombre());

                    $id = $SitioPerReg[0]->getId();

                    $SitioRegistrado = $em->getRepository("DGAbgSistemaBundle:AbgSitioWeb")->find($id);
                    $SitioRegistrado->setNombre($request->get('sitio'));
                    $em->merge($SitioRegistrado);
                    $em->flush();
                } else {
                    $SitioWeb->setNombre($request->get('sitio'));
                    $SitioWeb->setAbgPersona($Persona);
                    $em->persist($SitioWeb);
                    $em->flush();
                    $data['msj'] = "registrado";
                }
            }

            return new Response(json_encode($data));
        } catch (Exception $e) {
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
        } catch (Exception $e) {
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
        } catch (Exception $e) {
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

                $dql_departamento = "SELECT  c.id AS id"
                        . " FROM DGAbgSistemaBundle:AbgPersonaEspecialida c WHERE c.abgPersona=" . $request->get('hPersona');
                $esp = $em->createQuery($dql_departamento)->getArrayResult();
                if (count($esp) > 0) {

                    $sql = "SELECT  e.id AS id, e.nombre_especialidad AS nombre, pe.descripcion AS descripcion, pe.id As idPE, pe.ctl_especialidad_id AS idEsp "
                            . " FROM  ctl_especialidad e "
                            . " left JOIN abg_persona_especialidad pe ON e.id=pe.ctl_especialidad_id AND pe.abg_persona_id=" . $request->get('hPersona')
                            . " ORDER BY e.nombre_especialidad";
                    $stm = $this->container->get('database_connection')->prepare($sql);
                    $stm->execute();
                    $subEspecialidadesSeleccionadas = $stm->fetchAll();
                }
            }
            $dql_departamento = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre"
                    . " FROM  DGAbgSistemaBundle:CtlEspecialidad e ";
            $result_especialida = $em->createQuery($dql_departamento)->getArrayResult();



            return $this->render('abgpersona/especialidades.html.twig', array(
                        'abgEspecialida' => $result_especialida,
                        'especialidadesS' => $subEspecialidadesSeleccionadas,
            ));
        } catch (Exception $e) {
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
            $array = $request->get('DataEspecialida');
            parse_str($request->get('dato'), $datos);


            $RepositorioSubEsp = $em->getRepository("DGAbgSistemaBundle:AbgPersonaEspecialida");
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

                    $PersonaEspecialidad = new AbgPersonaEspecialida();
                    $idSub = $em->getRepository("DGAbgSistemaBundle:CtlEspecialidad")->find(intval($obj['0']));
                    $PersonaEspecialidad->setAbgPersona($Persona);
                    $PersonaEspecialidad->setCtlEspecialidad($idSub);
                    $PersonaEspecialidad->setDescripcion($datos[$obj['1']]);
                    $em->persist($PersonaEspecialidad);
                    $em->flush();
                }
                $data['msj'] = "Especialida registrada";
                $dql_especialida = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre, pe.descripcion AS descripcion "
                        . " FROM  DGAbgSistemaBundle:CtlEspecialidad e "
                        . "JOIN DGAbgSistemaBundle:AbgPersonaEspecialida pe WHERE e.id=pe.ctlEspecialidad AND pe.abgPersona=" . $request->get('hPersona')
                        . " GROUP by e.id";
                $data['Esp'] = $em->createQuery($dql_especialida)->getArrayResult();
            }




            return new Response(json_encode($data));
        } catch (Exception $e) {
            $data['error'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/from_experiencia", name="from_experiencia", options={"expose"=true})
     * @Method("GET")
     */
    public function FromExperienciaAction() {
        try {

            $em = $this->getDoctrine()->getManager();
            $request = $this->getRequest();
            $Experiencia = "";
            $fechaFin = "";
            if ((($request->get('experiencia') != null))) {
                $sql = "SELECT  el.id AS id, el.puesto AS puesto, el.compania AS empresa, el.funcion AS funcion, em.id idEmp, "
                        . " f.src AS src, date_format(el.facha_inicio, '%d-%m-%Y') As fechaIn, date_format(el.fecha_fin, '%d-%m-%Y') As fechaFin, "
                        . " el.ubicacion AS hubicacion "
                        . " FROM  abg_experiencia_laboral el "
                        . " JOIN abg_persona p on p.id=el.abg_persona_id AND  el.id=" . $request->get('experiencia')
                        . " left JOIN ctl_empresa em on em.id=el.ctl_empresa_id "
                        . " left JOIN abg_foto AS f on f.ctl_empresa_id=em.id GROUP by el.id,el.abg_persona_id,em.id";
                $stm = $this->container->get('database_connection')->prepare($sql);
                $stm->execute();
                $Experiencia = $stm->fetchAll();
                $fechaFin = $Experiencia[0]['fechaFin'];
            }


            return $this->render('abgpersona/experienciaLaboral.html.twig', array(
                        'Experiencia' => $Experiencia,
                        'fechaFin' => $fechaFin
            ));
        } catch (Exception $e) {
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
            $Empresa = $em->getRepository("DGAbgSistemaBundle:CtlEmpresa");
            $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();

            $idEmpresa = "";
            $IdExperiencia = "";
            $Persona = $em->getRepository("DGAbgSistemaBundle:AbgPersona")->find($request->get('hPersona'));
            $fechaIni = date_create($datos['txtFechaIni']);

            $ExpPersona = "SELECT el.id AS id, el.fechaFin AS fecha_fin, el.compania As compania "
                    . " FROM DGAbgSistemaBundle:AbgExperienciaLaboral el WHERE el.fechaFin IS NULL "
                    . " AND el.abgPersona=" . $request->get('hPersona');
            $dqlPersonaExp = $em->createQuery($ExpPersona);
            $resulExp = $dqlPersonaExp->getResult();

            $idEXp = "";
            if ($datos['hidExp'] == "") {
                if ($request->get('tipo') == "1") {
                    $idEmpresa = $Empresa->find(intval($request->get('empresa')));

                    if ($idEmpresa !== null) {
                        $nombre = $Empresa->find(intval($request->get('empresa')))->getNombreEmpresa();
                    }
                } else {

                    $nombre = $request->get('empresa');
                }
                if ((count($resulExp) > 0) && ($datos['txtFechaFin'] == "")) {
                    foreach ($resulExp as $row) {

                        $compania = $row['compania'];
                        $id = $row['id'];
                    }
                    $data['msj'] = "Actualmente Trabaja en " . $compania;
                    $data['val'] = 1;
                } else {

                    $Experiencia = new AbgExperienciaLaboral();

                    $Experiencia->setAbgPersona($Persona);
                    $Experiencia->setCompania($nombre);
                    $Experiencia->setFachaInicio($fechaIni);

                    $Experiencia->setFuncion($datos['txtfuncion']);
                    $Experiencia->setPuesto($datos['txtpuesto']);
                    $Experiencia->setUbicacion($datos['txthubicacion']);

                    if (($datos['txtFechaFin']) != "") {
                        $fechaFin = date_create($datos['txtFechaFin']);
                        $Experiencia->setFechaFin($fechaFin);
                    } else {
                        $fechaFin = null;
                        $Experiencia->setFechaFin($fechaFin);
                    }
                    if ($idEmpresa != null) {

                        $Empresa = $em->getRepository("DGAbgSistemaBundle:CtlEmpresa")->find($idEmpresa->getId());
                        $Experiencia->setCtlEmpresa($Empresa);
                    }
                    $em->persist($Experiencia);
                    $em->flush();
                    $IdExperiencia = $Experiencia->getId();

                    if ($Experiencia->getFechaFin() == null) {
                        try {
                            $AbgPersonaEmpresa = new AbgPersonaEmpresa();
                            $Empresa = $em->getRepository("DGAbgSistemaBundle:CtlEmpresa")->find($idEmpresa->getId());

                            if ($Empresa) {
                                $AbgPersonaEmpresa->setAbgPersona($Persona);
                                $AbgPersonaEmpresa->setCtlEmpresa($Empresa);
                                $em->persist($AbgPersonaEmpresa);
                                $em->flush();
                            }
                        } catch (Exception $e) {
                            $data['error'] = $e->getMessage(); //"Falla al Registrar ";
                            return new Response(json_encode($data));
                        }
                    }
                    $data['msj'] = "Experiencia registrada";
                }
            }// Actualizar 
            else {
                if ($request->get('tipo') == "1") {
                    $idEmpresa = $Empresa->find(intval($request->get('empresa')));

                    if ($idEmpresa !== null) {
                        $nombre = $Empresa->find(intval($request->get('empresa'))); //->getNombreEmpresa();
                    }
                } else {

                    $nombre = $request->get('empresa');
                }

                foreach ($resulExp as $row) {

                    $compania = $row['compania'];
                    $idEXp = $row['id'];
                }
                if ($idEXp == $datos['hidExp']) { // hay experiencia registrada para una empresa registrada en el directorio y actualmente trabajando
                    $Experiencia = $em->getRepository("DGAbgSistemaBundle:AbgExperienciaLaboral")->find($datos['hidExp']);
                    if ($nombre != "") {
                        $Experiencia->setCompania($nombre);
                    }

                    $Experiencia->setFachaInicio($fechaIni);
                    $Experiencia->setFuncion($datos['txtfuncion']);
                    $Experiencia->setPuesto($datos['txtpuesto']);
                    $Experiencia->setUbicacion($datos['txthubicacion']);
                    if (($datos['txtFechaFin']) == "") {

                        $fechaFin = null;
                        $Experiencia->setFechaFin($fechaFin);
                        if ($request->get('tipo') == "1") {

                            $Experiencia->setCompania($idEmpresa->getNombreEmpresa());
                            $Experiencia->setCtlEmpresa($idEmpresa);
// Inserta en persona_empresa si la fecha final es null
                            $sqlEdu = "DELETE FROM abg_persona_empresa  WHERE abg_persona_id= " . $idPersona;
                            $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                            $stm->execute();

                            $sqlEdu = "INSERT INTO abg_persona_empresa (abg_persona_id,ctl_empresa_id) "
                                    . " VALUES (" . $idPersona . "," . intval($request->get('empresa')) . ")";
                            $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                            $stm->execute();
                        } elseif ($request->get('tipo') == "0" && $datos['hidEmp'] !== "" && $request->get('empresa') == "") {

// elimina de la tabla persona_empresa porq la nueva empresa no esta registrada en el directorio de abogados 
// y actualiza personaExperiencia CtlEmpresa null porq la nueva empresa no esta registrada


                            $sqlEdu = "DELETE FROM abg_persona_empresa  WHERE abg_persona_id= " . $idPersona;
                            $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                            $stm->execute();
                            $sqlEdu = "INSERT INTO abg_persona_empresa (abg_persona_id,ctl_empresa_id) "
                                    . " VALUES (" . $idPersona . "," . intval($datos['hidEmp']) . ")";
                            $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                            $stm->execute();
                        } elseif ($request->get('tipo') == "0" && $datos['hidEmp'] !== "" && $request->get('empresa') !== "") {


                            $fechaFin = null;
                            $Experiencia->setFechaFin($fechaFin);
                            $Experiencia->setCtlEmpresa(null);
                            $Experiencia->setCompania($request->get('empresa'));

                            $sqlEdu = "DELETE FROM abg_persona_empresa  WHERE abg_persona_id= " . $idPersona;
                            $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                            $stm->execute();
                        } else {
                            $fechaFin = null;
                            $Experiencia->setFechaFin($fechaFin);
                            $Experiencia->setCtlEmpresa(null);

                            $sqlEdu = "DELETE FROM abg_persona_empresa  WHERE abg_persona_id= " . $idPersona;
                            $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                            $stm->execute();
                        }
                        $em->merge($Experiencia);
                        $em->flush();
                        $IdExperiencia = $Experiencia->getId();
                        $data['msj'] = "Experiencia actualizada";
                    } else {

                        if (date_create($datos['txtFechaFin']) > $fechaIni) {
                            if ($datos['hidEmp'] !== "" && $request->get('tipo') == '0') {
                                $fechaFin = date_create($datos['txtFechaFin']);
                                $Experiencia->setFechaFin($fechaFin);
                                $Empre = $em->getRepository("DGAbgSistemaBundle:CtlEmpresa")->find($datos['hidEmp']);
                                if ($datos['hidEmp'] == "" && $request->get('empresa') !== "") {
                                    $Experiencia->setCtlEmpresa(null);
                                }

                                $nombrempresa = $Empresa->find($datos['hidEmp'])->getnombreEmpresa();
                                $Experiencia->setCompania($nombrempresa);

                                $sqlEdu = "DELETE FROM  abg_persona_empresa  WHERE abg_persona_id= " . $idPersona;
                                $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                                $stm->execute();
                            } elseif ($request->get('tipo') == '1') {

                                $nombrempresa = $Empresa->find($request->get('empresa'))->getnombreEmpresa();
                                $idEmpresaComp = $Empresa->find(intval($request->get('empresa')));

                                $fechaFin = date_create($datos['txtFechaFin']);
                                $Experiencia->setFechaFin($fechaFin);
                                $Experiencia->setCompania($nombrempresa);
                                $Experiencia->setCtlEmpresa($idEmpresaComp);

                                $sqlEdu = "DELETE FROM  abg_persona_empresa  WHERE abg_persona_id= " . $idPersona;
                                $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                                $stm->execute();
                            }
                            if ($datos['hidEmp'] == "" && $request->get('tipo') == '0') {

                                if ($request->get('empresa') != "") {
                                    $Experiencia->setCompania($request->get('empresa'));
                                }
                                $fechaFin = date_create($datos['txtFechaFin']);
                                $Experiencia->setFechaFin($fechaFin);
                            }
                            $em->merge($Experiencia);
                            $em->flush();
                            $IdExperiencia = $Experiencia->getId();
                            $data['msj'] = "Experiencia actualizada";
                        } else {
                            $data['msj'] = false;
                            $data['error'] = "Fecha inicio mayor a fecha fin";
                        }
                    }
                }

// si hay experiencia registrada pero la empresa no esta registrada como bufete
                else {

                    if ((count($resulExp) > 0) && ($datos['txtFechaFin'] == "")) {
                        foreach ($resulExp as $row) {

                            $compania = $row['compania'];
                        }
                        $data['msj'] = "Actualmente Trabaja en " . $compania;
                        $data['val'] = 1;
                    } else {

                        $Experiencia = $em->getRepository("DGAbgSistemaBundle:AbgExperienciaLaboral")->find($datos['hidExp']);
                        if ($nombre != "") {
                            $Experiencia->setCompania($nombre);
                        }
                        $Experiencia->setFachaInicio($fechaIni);

                        $Experiencia->setFuncion($datos['txtfuncion']);
                        $Experiencia->setPuesto($datos['txtpuesto']);
                        $Experiencia->setUbicacion($datos['txthubicacion']);
                        if (($datos['txtFechaFin']) == "") {

                            $fechaFin = null;
                            $Experiencia->setFechaFin($fechaFin);
//  $Experiencia->setFechaFin($fechaFin);

                            if ($request->get('tipo') == "1") {

                                /*     var_dump(intval($datos['hidEmp']) . " empresa");
                                  var_dump($Empresa->find(intval($request->get('empresa')))->getId());
                                  exit(); */
                                $sqlEdu = "DELETE FROM abg_persona_empresa  WHERE abg_persona_id= " . $idPersona;
                                $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                                $stm->execute();

                                if ($request->get('empresa') !== "") {
                                    $idEmpresa = $Empresa->find(intval($request->get('empresa')));
                                    $Experiencia->setCtlEmpresa($idEmpresa);
                                    $Experiencia->setCompania($Empresa->find(intval($request->get('empresa')))->getNombreEmpresa());
                                    $sqlEdu = "INSERT INTO abg_persona_empresa (abg_persona_id,ctl_empresa_id) "
                                            . " VALUES (" . $idPersona . "," . $Empresa->find(intval($request->get('empresa')))->getId() . ")";
                                    $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                                    $stm->execute();
                                }
                            } elseif ($request->get('tipo') == "0" && $datos['hidEmp'] !== "") {

                                /*     var_dump("hhhhhhhh");
                                  exit(); */
                                $sqlEdu = "DELETE FROM abg_persona_empresa  WHERE abg_persona_id= " . $idPersona;
                                $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                                $stm->execute();
                                if ($request->get('empresa') !== "") {
                                    $Experiencia->setCompania($request->get('empresa'));
                                    $Experiencia->setCtlEmpresa(null);
                                } else {
                                    $Emp = $em->getRepository("DGAbgSistemaBundle:CtlEmpresa")->find($datos['hidEmp']);
                                    $sqlEdu = "INSERT INTO abg_persona_empresa (abg_persona_id,ctl_empresa_id) "
                                            . " VALUES (" . $idPersona . "," . intval($datos['hidEmp']) . ")";
                                    $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                                    $stm->execute();
                                    $Experiencia->setCtlEmpresa($Emp);
                                }

                                $fechaFin = null;
                                $Experiencia->setFechaFin($fechaFin);
                            } elseif ($request->get('tipo') == "0" && $datos['hidEmp'] !== "" && $request->get('empresa') !== "") {

                                $Experiencia->setCompania($request->get('empresa'));
                                $fechaFin = null;
                                $Experiencia->setFechaFin($fechaFin);
                                $Experiencia->setCtlEmpresa(null);
                                $sqlEdu = "DELETE FROM abg_persona_empresa  WHERE abg_persona_id= " . $idPersona;
                                $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                                $stm->execute();
                            }
                            $em->merge($Experiencia);
                            $em->flush();
                            $IdExperiencia = $Experiencia->getId();
                            $data['msj'] = "Experiencia actualizada";
                        } else {
                            if (date_create($datos['txtFechaFin']) > $fechaIni) {

                                $fechaFin = date_create($datos['txtFechaFin']);
                                if ($request->get('tipo') == "1") {

//    var_dump($Empresa->find(intval($request->get('empresa'))));

                                    $IdCTLEMPRESA = $Empresa->find(intval($request->get('empresa')));


                                    if ($request->get('empresa') !== "") {
                                        $Experiencia->setCompania($Empresa->find(intval($request->get('empresa')))->getnombreEmpresa());
                                    }
                                    $Experiencia->setCtlEmpresa($IdCTLEMPRESA);

// var_dump($Empresa->find(intval($request->get('empresa'))));
                                    /*        var_dump($datos['hidEmp']);
                                      exit(); */


                                    $Experiencia->setFechaFin($fechaFin);

                                    $sqlEdu = "DELETE FROM abg_persona_empresa  WHERE abg_persona_id= " . $idPersona;
                                    $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                                    $stm->execute();
                                    /*
                                      $sqlEdu = "INSERT INTO  abg_persona_empresa (abg_persona_id,ctl_empresa_id) "
                                      . " VALUES (" . $idPersona . "," . $Empresa->find(intval($request->get('empresa')))->getId() . ")";
                                      $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                                      $stm->execute(); */
                                } elseif ($request->get('tipo') == "0" && $datos['hidEmp'] !== "" && $datos['txtFechaFin'] == "") {

                                    $sqlEdu = "DELETE FROM abg_persona_empresa  WHERE abg_persona_id= " . $idPersona;
                                    $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                                    $stm->execute();
//   $Experiencia->setCtlEmpresa(null);
                                } elseif ($request->get('tipo') == "0" && $datos['hidEmp'] !== "" && $datos['txtFechaFin'] !== "" && $request->get('empresa') !== "") {

                                    $sqlEdu = "DELETE FROM abg_persona_empresa  WHERE abg_persona_id= " . $idPersona;
                                    $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                                    $stm->execute();
                                    $Experiencia->setCtlEmpresa(null);
                                    $Experiencia->setFechaFin($fechaFin);
                                    $Experiencia->setCompania($request->get('empresa'));
                                } elseif ($request->get('tipo') == "0" && $datos['hidEmp'] !== "" && $datos['txtFechaFin'] !== "" && $request->get('empresa') == "") {

                                    $sqlEdu = "DELETE FROM abg_persona_empresa  WHERE abg_persona_id= " . $idPersona;
                                    $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                                    $stm->execute();
                                } elseif ($request->get('tipo') == "0" && $datos['hidEmp'] == "" && $datos['txtFechaFin'] !== "" && $request->get('empresa') == "") {

                                    $Experiencia->setCtlEmpresa(null);
                                    $Experiencia->setFechaFin($fechaFin);
                                    $sqlEdu = "DELETE FROM abg_persona_empresa  WHERE abg_persona_id= " . $idPersona;
                                    $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                                    $stm->execute();
                                } elseif ($request->get('tipo') == "0" && $datos['hidEmp'] == "" && $datos['txtFechaFin'] !== "" && $request->get('empresa') !== "") {

                                    $Experiencia->setCompania($request->get('empresa'));
                                    $Experiencia->setCtlEmpresa(null);
                                    $Experiencia->setFechaFin($fechaFin);
                                    $sqlEdu = "DELETE FROM abg_persona_empresa  WHERE abg_persona_id= " . $idPersona;
                                    $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                                    $stm->execute();
                                }


                                $em->merge($Experiencia);
                                $em->flush();
                                $IdExperiencia = $Experiencia->getId();
                                $data['msj'] = "Experiencia actualizada";
                            } else {
                                $data['msj'] = false;
                                $data['error'] = "Fecha inicio mayor a fecha fin";
                            }
                        }
                    }
                }
            }



            $ObjetoUrl = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:AbgUrlPersonalizada')->findByCtlEmpresa($idEmpresa);

            if ($IdExperiencia != "") {
                $sql = "SELECT  el.id AS id, el.puesto AS puesto, el.compania AS empresa, el.funcion AS funcion, em.id idEmp, "
                        . " f.src AS src, DATEDIFF(el.fecha_fin,el.facha_inicio) AS dias, date_format(el.facha_inicio, '%M %Y') As fechaIn, date_format(el.fecha_fin, '%M %Y') As fechaFin, "
                        . " el.ubicacion AS hubicacion, urle.url AS url "
                        . " FROM  abg_experiencia_laboral el "
                        . " JOIN abg_persona p on p.id=el.abg_persona_id AND el.id=" . $IdExperiencia
                        . " left JOIN ctl_empresa em on em.id=el.ctl_empresa_id "
                        . " left JOIN abg_foto AS f on f.ctl_empresa_id=em.id "
                        . " left JOIN abg_url_personalizada urle ON em.id=urle.ctl_empresa_id "
                        . " GROUP by el.id,el.abg_persona_id,em.id";
                $stm = $this->container->get('database_connection')->prepare($sql);
                $stm->execute();
                $data['Exp'] = $stm->fetchAll();
            }
            return new Response(json_encode($data));
        } catch (Exception $e) {
            $data['error'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/remove_experiencia", name="remove_experiencia", options={"expose"=true})
     * @Method("GET")
     */
    public function RemoveExperienciaAction() {
        try {
            $em = $this->getDoctrine()->getEntityManager();

            $request = $this->getRequest();
            $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();
            $Experiencia = $em->getRepository("DGAbgSistemaBundle:AbgExperienciaLaboral")->find(intval($request->get('experiencia')));
            if ($Experiencia->getCtlEmpresa() != NULL) {
                $sqlEdu = "DELETE FROM  abg_persona_empresa  WHERE abg_persona_id= " . $idPersona
                        . "  AND ctl_empresa_id=" . $Experiencia->getCtlEmpresa()->getId();
                $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                $stm->execute();
            }
            $em->remove($Experiencia);
            $em->flush();
            $data['msj'] = "Experiencia eliminada";

            return new Response(json_encode($data));
        } catch (Exception $e) {
            $data['error'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/empresas/get", name="empresas", options={"expose"=true})
     * @Method("GET")
     */
    public function getEmpresasAction() {
        $request = $this->getRequest();
        $busqueda = $request->query->get('q');

        $em = $this->getDoctrine()->getEntityManager();
        $dql = "SELECT e.id AS idEmp, e.nombreEmpresa AS nombre, f.src AS src "
                . "FROM DGAbgSistemaBundle:CtlEmpresa e "
                . " JOIN DGAbgSistemaBundle:AbgFoto f "
                . "WHERE e.id=f.ctlEmpresa AND e.urlPermiso=1 "
                . " AND upper(e.nombreEmpresa) LIKE upper(:busqueda) "
                . "ORDER BY e.nombreEmpresa ASC ";

        $array = $em->createQuery($dql)
                ->setParameters(array('busqueda' => "%" . $busqueda . "%"))
                ->setMaxResults(10)
                ->getResult();
        if (count($array > 0)) {
            $data['data'] = $array;
        }/* else {
          $data['data'] = array('id' => 0, 'nombre' => '<a onclick="CambioEmp()"> Nueva empresa</a>');
          } */
        return new Response(json_encode($data));
    }

    /**
     * @Route("/from_educacion", name="from_educacion", options={"expose"=true})
     * @Method("GET")
     */
    public function FromEducacionAction() {
        try {

            $em = $this->getDoctrine()->getManager();
            $request = $this->getRequest();
            $Educacion = "";
            if ((($request->get('educacion') != null))) {
                $sqlEdu = "SELECT e.id AS idEs, e.institucion AS institucion, e.titulo AS titulo, e.anio_inicio AS anioIni, e.anio_graduacion AS anio,"
                        . " tp.abg_titulocol AS disciplina, tp.id idDis "
                        . " FROM abg_estudio e "
                        . " JOIN  abg_persona p ON e.abg_persona_id=p.id AND e.id=" . $request->get('educacion')
                        . " JOIN ctl_titulo_profesional tp ON tp.id=e.abg_titulo_profesional_id";
                $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                $stm->execute();
                $Educacion = $stm->fetchAll();
            }

            $dql = "SELECT tp.id AS id, tp.abgTitulocol AS nombre "
                    . "FROM DGAbgSistemaBundle:CtlTituloProfesional tp "
                    . "ORDER BY tp.abgTitulocol ASC ";

            $disciplina = $em->createQuery($dql)->getArrayResult();


            return $this->render('abgpersona/educacion.html.twig', array(
                        'disciplina' => $disciplina,
                        'Educacion' => $Educacion,
            ));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/remove_educacion", name="remove_educacion", options={"expose"=true})
     * @Method("POST")
     */
    public function RemoveEducacionAction() {
        try {
            $em = $this->getDoctrine()->getManager();
            $request = $this->getRequest();

            $Educacion = $em->getRepository("DGAbgSistemaBundle:AbgEstudio")->find(($request->get('edu')));

            $em->remove($Educacion);
            $em->flush();
            $data['id'] = $request->get('edu');
            $data['msj'] = "Educación eliminada";

            return new Response(json_encode($data));
        } catch (Exception $e) {
            $data['error'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/registar_edu", name="registrar_edu", options={"expose"=true})
     * @Method("POST")
     */
    public function RegistraEduAction() {

        try {
            $em = $this->getDoctrine()->getManager();
            $request = $this->getRequest();

            parse_str($request->get('dato'), $datos);
            $Disciplina = $em->getRepository("DGAbgSistemaBundle:CtlTituloProfesional");
            $idDisciplina = $Disciplina->find(intval($datos['Sdisciplina']));
            $Persona = $em->getRepository("DGAbgSistemaBundle:AbgPersona")->find($request->get('hPersona'));

            $IdEducacion = "";
            if ((($datos['hidEdu'] == ""))) {

                $Estudio = new AbgEstudio();

                $Estudio->setAbgPersona($Persona);
                $Estudio->setAbgTituloProfesional($idDisciplina);
                $Estudio->setAnioGraduacion(strval($datos['txtAnioFin']));
                $Estudio->setInstitucion($datos['txtCentro']);
                $Estudio->setTitulo($datos['txtTitulo']);
                $Estudio->setAnioInicio($datos['txtAnioIni']);

                $em->persist($Estudio);
                $em->flush();
                $data['msj'] = "Educación registrada";
                $IdEducacion = $Estudio->getId();
            } else {


                $Estudio = $em->getRepository("DGAbgSistemaBundle:AbgEstudio")->find($datos['hidEdu']);
                $Estudio->setAbgPersona($Persona);
                $Estudio->setAbgTituloProfesional($idDisciplina);

                $Estudio->setInstitucion($datos['txtCentro']);
                $Estudio->setAnioInicio($datos['txtAnioIni']);

                $Estudio->setTitulo($datos['txtTitulo']);
                $Estudio->setAnioGraduacion(strval($datos['txtAnioFin']));
                $em->merge($Estudio);
                $em->flush();
                $IdEducacion = $Estudio->getId();
                $data['msj'] = "Educación actualizada";
            }

            $sqlEdu = "SELECT e.id AS idEs, e.institucion AS institucion, e.titulo AS titulo, e.anio_inicio AS anioIni, e.anio_graduacion AS anio, tp.abg_titulocol AS disciplina "
                    . " FROM abg_estudio e "
                    . " JOIN  abg_persona p ON e.abg_persona_id=p.id AND e.id=" . $IdEducacion . " AND e.abg_persona_id=" . $request->get('hPersona')
                    . " JOIN ctl_titulo_profesional tp ON tp.id=e.abg_titulo_profesional_id "
                    . " ORDER BY e.anio_inicio ";
            $stm = $this->container->get('database_connection')->prepare($sqlEdu);
            $stm->execute();
            $data['Edu'] = $stm->fetchAll();

            return new Response(json_encode($data));
        } catch (Exception $e) {
            $data['error'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/from_certificacion", name="from_certificacion", options={"expose"=true})
     * @Method("GET")
     */
    public function FromCertificacionAction() {
        try {
            $request = $this->getRequest();
            $Certificacion = "";

            if ((($request->get('certificacion') != null))) {

                $sqlCert = "SELECT c.id AS id, c.certficacion_nombre AS nombre,c.institucion As institucion, "
                        . " c.fecha_inicio As fechaIn, c.fecha_fin AS fechaFin "
                        . " FROM  abg_certificacion c "
                        . " JOIN abg_persona p on p.id=c.abg_persona_id AND c.id=" . $request->get('certificacion')
                        . " ORDER BY c.fecha_inicio";
                $stm = $this->container->get('database_connection')->prepare($sqlCert);
                $stm->execute();
                $Certificacion = $stm->fetchAll();
            }

            return $this->render('abgpersona/certificacion.html.twig', array(
                        'Certificacion' => $Certificacion,
            ));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/registrar_certi", name="registrar_certi", options={"expose"=true})
     * @Method("POST")
     */
    public function RegistraCertiAction() {

        try {
            $em = $this->getDoctrine()->getManager();
            $request = $this->getRequest();

            parse_str($request->get('dato'), $datos);
            /*  $Disciplina = $em->getRepository("DGAbgSistemaBundle:CtlTituloProfesional");
              $idDisciplina = $Disciplina->find(intval($datos['Sdisciplina'])); */
            $Persona = $em->getRepository("DGAbgSistemaBundle:AbgPersona")->find($request->get('hPersona'));

            $IdEducacion = "";


            $fechaIni = date_create($datos['txtFechIniC']);
            $fechaFin = date_create($datos['txtFechFinC']);

            if ((($datos['hidCert'] == ""))) {

                $AbgCertificacion = new AbgCertificacion();

                $AbgCertificacion->setAbgPersona($Persona);
                $AbgCertificacion->setCertficacionNombre($datos['txtCerti']);
                $AbgCertificacion->setFechaFin($fechaFin);
                $AbgCertificacion->setFechaInicio($fechaIni);
                $AbgCertificacion->setInstitucion($datos['txtAutorida']);


                $em->persist($AbgCertificacion);
                $em->flush();
                $data['msj'] = "Certificación registrada";
                $IdCertificacion = $AbgCertificacion->getId();
            } else {

                $AbgCertificacion = $em->getRepository("DGAbgSistemaBundle:AbgCertificacion")->find($datos['hidCert']);
                $AbgCertificacion->setCertficacionNombre($datos['txtCerti']);
                $AbgCertificacion->setFechaFin($fechaFin);
                $AbgCertificacion->setFechaInicio($fechaIni);
                $AbgCertificacion->setInstitucion($datos['txtAutorida']);
                /*
                  if ($request->get('tipo') == "1") {
                  $Experiencia->setCtlEmpresa($idEmpresa);
                  } */

                $em->merge($AbgCertificacion);
                $em->flush();
                $IdCertificacion = $AbgCertificacion->getId();
                $data['msj'] = "Certificación actualizada";
            }

            $sqlEdu = "SELECT c.id AS id, c.certficacion_nombre AS nombre,c.institucion As institucion, "
                    . "date_format(c.fecha_inicio, '%M %Y') As fechaIn,date_format(c.fecha_fin, '%M %Y') AS fechaFin "
                    . " FROM  abg_certificacion c "
                    . " JOIN abg_persona p on p.id=c.abg_persona_id AND c.id=" . $IdCertificacion;
            $stm = $this->container->get('database_connection')->prepare($sqlEdu);
            $stm->execute();
            $data['Cert'] = $stm->fetchAll();

            return new Response(json_encode($data));
        } catch (Exception $e) {
            $data['error'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/remove_certificacion", name="remove_certificacion", options={"expose"=true})
     * @Method("POST")
     */
    public function RemoveCertificacionAction() {
        try {
            $em = $this->getDoctrine()->getManager();
            $request = $this->getRequest();

            $Certificacion = $em->getRepository("DGAbgSistemaBundle:AbgCertificacion")->find(($request->get('org')));

            $em->remove($Certificacion);
            $em->flush();

            $data['msj'] = "Certificación eliminada";

            return new Response(json_encode($data));
        } catch (Exception $e) {
            $data['error'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/from_curso", name="from_curso", options={"expose"=true})
     * @Method("GET")
     */
    public function FromCursoAction() {
        try {

            $em = $this->getDoctrine()->getManager();
            $request = $this->getRequest();
            $Curso = "";
            if ((($request->get('curso') != null))) {
                $sqlCurso = "SELECT s.id AS id, s.nombre AS nombre,s.institucion As institucion, "
                        . " s.fecha_incio As fechaIn,s.fecha_fin AS fechaFin, s.descripcion AS descripcion "
                        . " FROM  seminario s "
                        . " JOIN abg_persona p on p.id=s.abg_persona_id AND s.id=" . $request->get('curso')
                        . " ORDER BY s.fecha_incio";
                $stm = $this->container->get('database_connection')->prepare($sqlCurso);
                $stm->execute();
                $Curso = $stm->fetchAll();
            }

            return $this->render('abgpersona/cursos_seminarios.html.twig', array(
                        'Curso' => $Curso,
            ));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/registrar_curso", name="registrar_curso", options={"expose"=true})
     * @Method("POST")
     */
    public function RegistraCursoAction() {

        try {
            $em = $this->getDoctrine()->getManager();
            $request = $this->getRequest();

            parse_str($request->get('dato'), $datos);

            $Persona = $em->getRepository("DGAbgSistemaBundle:AbgPersona")->find($request->get('hPersona'));

            $IdEducacion = "";

            $fechaIni = date_create($datos['txtFechIniCM']);
            $fechaFin = date_create($datos['txtFechFinCM']);

            if ((($datos['hidCurso'] == ""))) {

                $Seminario = new Seminario();
                $Seminario->setAbgPersona($Persona);
                $Seminario->setDescripcion($datos['txtDescripcionCM']);
                $Seminario->setNombre($datos['txtCurso']);
                $Seminario->setInstitucion($datos['txtAutoridaCM']);
                $Seminario->setFechaIncio($fechaIni);
                $Seminario->setFechaFin($fechaFin);


                $em->persist($Seminario);
                $em->flush();
                $data['msj'] = "Seminario registrado";
                $IdSeminario = $Seminario->getId();
            } else {

                $Seminario = $em->getRepository("DGAbgSistemaBundle:Seminario")->find($datos['hidCurso']);
                $Seminario->setDescripcion($datos['txtDescripcionCM']);
                $Seminario->setNombre($datos['txtCurso']);
                $Seminario->setInstitucion($datos['txtAutoridaCM']);
                $Seminario->setFechaIncio($fechaIni);
                $Seminario->setFechaFin($fechaFin);
                /*
                  if ($request->get('tipo') == "1") {
                  $Experiencia->setCtlEmpresa($idEmpresa);
                  } */

                $em->merge($Seminario);
                $em->flush();
                $IdSeminario = $Seminario->getId();
                $data['msj'] = "Seminario actualizado";
            }

            $sqlCurso = "SELECT s.id AS id, s.nombre AS nombre,s.institucion As institucion, "
                    . " date_format(s.fecha_incio, '%M %Y') As fechaIn,date_format(s.fecha_fin, '%M %Y') AS fechaFin, s.descripcion AS descripcion "
                    . " FROM  seminario s "
                    . " JOIN abg_persona p on p.id=s.abg_persona_id AND s.id=" . $IdSeminario
                    . " ORDER BY s.fecha_incio";
            $stm = $this->container->get('database_connection')->prepare($sqlCurso);
            $stm->execute();
            $data['Curso'] = $stm->fetchAll();

            return new Response(json_encode($data));
        } catch (Exception $e) {
            $data['error'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/remove_seminario", name="remove_seminario", options={"expose"=true})
     * @Method("POST")
     */
    public function RemoveSeminarioAction() {
        try {
            $em = $this->getDoctrine()->getManager();
            $request = $this->getRequest();

            $Seminario = $em->getRepository("DGAbgSistemaBundle:Seminario")->find(($request->get('sem')));

            $em->remove($Seminario);
            $em->flush();

            $data['msj'] = "Seminario eliminado";

            return new Response(json_encode($data));
        } catch (Exception $e) {
            $data['error'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/from_organizacion", name="from_organizacion", options={"expose"=true})
     * @Method("GET")
     */
    public function FromOrganizacionAction() {
        try {

            $em = $this->getDoctrine()->getManager();
            $request = $this->getRequest();
            $Organizacion = "";
            if ((($request->get('organizacion') != null))) {
                $sqlOrg = "SELECT org.id AS id, org.nombre AS nombre,org.puesto As puesto,org.descripcion AS descripcion, "
                        . " org.fecha_inicio As fechaIn, org.fecha_fin AS fechaFin"
                        . " FROM  abg_organizacion org "
                        . " JOIN abg_persona p on p.id=org.abg_persona_id AND org.id=" . $request->get('organizacion');
                $stm = $this->container->get('database_connection')->prepare($sqlOrg);
                $stm->execute();
                $Organizacion = $stm->fetchAll();
            }

            return $this->render('abgpersona/organizacion.html.twig', array(
                        'Organizacion' => $Organizacion,
            ));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/registrar_org", name="registrar_org", options={"expose"=true})
     * @Method("POST")
     */
    public function RegistraOrgAction() {

        try {
            $em = $this->getDoctrine()->getManager();
            $request = $this->getRequest();

            parse_str($request->get('dato'), $datos);

            $Persona = $em->getRepository("DGAbgSistemaBundle:AbgPersona")->find($request->get('hPersona'));

            $IdEducacion = "";

            $fechaIni = date_create($datos['txtFechIniOrg']);
            $fechaFin = date_create($datos['txtFechFinOrg']);

            if ((($datos['hidOrg'] == ""))) {

                $AbgOrganizacion = new AbgOrganizacion();
                $AbgOrganizacion->setAbgPersona($Persona);
                $AbgOrganizacion->setNombre($datos['txtOrg']);

                $AbgOrganizacion->setPuesto($datos['txtPuestoOrg']);
                $AbgOrganizacion->setDescripcion($datos['txtDescripcion']);
                $AbgOrganizacion->setFechaInicio($fechaIni);
                $AbgOrganizacion->setFechaFin($fechaFin);

                $em->persist($AbgOrganizacion);
                $em->flush();

                $data['msj'] = "Organización registrada";
                $IdOrganizacion = $AbgOrganizacion->getId();
            } else {

                $AbgOrganizacion = $em->getRepository("DGAbgSistemaBundle:AbgOrganizacion")->find($datos['hidOrg']);
                $AbgOrganizacion->setNombre($datos['txtOrg']);

                $AbgOrganizacion->setPuesto($datos['txtPuestoOrg']);
                $AbgOrganizacion->setDescripcion($datos['txtDescripcion']);
                $AbgOrganizacion->setFechaInicio($fechaIni);
                $AbgOrganizacion->setFechaFin($fechaFin);
                /*
                  if ($request->get('tipo') == "1") {
                  $Experiencia->setCtlEmpresa($idEmpresa);
                  } */

                $em->merge($AbgOrganizacion);
                $em->flush();
                $IdOrganizacion = $AbgOrganizacion->getId();
                $data['msj'] = "Organización actualizada";
            }

            $sqlEdu = "SELECT org.id AS id, org.nombre AS nombre,org.puesto As puesto, org.descripcion AS descripcion, "
                    . " date_format(org.fecha_inicio, '%M %Y') As fechaIn, date_format(org.fecha_fin, '%M %Y') AS fechaFin"
                    . " FROM  abg_organizacion org "
                    . " JOIN abg_persona p on p.id=org.abg_persona_id AND org.id=" . $IdOrganizacion
                    . " ORDER BY org.fecha_inicio";
            $stm = $this->container->get('database_connection')->prepare($sqlEdu);
            $stm->execute();
            $data['Organizacion'] = $stm->fetchAll();

            return new Response(json_encode($data));
        } catch (Exception $e) {
            $data['error'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/remove_org", name="remove_org", options={"expose"=true})
     * @Method("POST")
     */
    public function RemoveOrgAction() {
        try {
            $em = $this->getDoctrine()->getManager();
            $request = $this->getRequest();

            $Organizacion = $em->getRepository("DGAbgSistemaBundle:AbgOrganizacion")->find(($request->get('org')));

            $em->remove($Organizacion);
            $em->flush();

            $data['msj'] = "Organización eliminada";

            return new Response(json_encode($data));
        } catch (Exception $e) {
            $data['error'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/from_idioma", name="from_idioma", options={"expose"=true})
     * @Method("GET")
     */
    public function FromIdiomaAction() {
        try {
            $em = $this->getDoctrine()->getManager();
            $request = $this->getRequest();
            $IdiomaRegistrado = "";

            $dql = "SELECT i.id AS id, i.idioma AS nombre "
                    . "FROM DGAbgSistemaBundle:CtlIdioma i Order By i.idioma Asc";
            $idioma = $em->createQuery($dql)->getArrayResult();

            $sqlEdu = "SELECT i.id As idIdioma,pi.id AS idPi,i.idioma As nombre, pi.nivel As nivel "
                    . " FROM abg_persona_idioma pi "
                    . " join ctl_idioma i on i.id=pi.ctl_idioma_id "
                    . " join abg_persona p on p.id=pi.abg_persona_id "
                    . " AND p.id=" . $request->get('hPersona')
                    . " order by i.idioma";
            $stm = $this->container->get('database_connection')->prepare($sqlEdu);
            $stm->execute();
            $IdiomaRegistrado = $stm->fetchAll();

            return $this->render('abgpersona/idiomas.html.twig', array(
                        'IdiomaRegistrado' => $IdiomaRegistrado,
                        'idioma' => $idioma,
            ));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/registrar_idioma", name="registrar_idioma", options={"expose"=true})
     * @Method("POST")
     */
    public function RegistraIdiomaAction() {

        try {
            $em = $this->getDoctrine()->getManager();
            $request = $this->getRequest();

            parse_str($request->get('dato'), $datos);
            $array = $request->get('DatosIdiomas');

            $RepositorioPI = $em->getRepository('DGAbgSistemaBundle:AbgPersonaIdioma');
            if (is_null($RepositorioPI->findBy(array('abgPersona' => $request->get('hPersona'))))) {
                
            } else {
                $PersonaIdioma = $RepositorioPI->findBy(array('abgPersona' => $request->get('hPersona')));
                foreach ($PersonaIdioma as $objPersonaIdioma) {
                    $em->remove($objPersonaIdioma);
                    $em->flush();
                }
            }

            if (is_null($array)) {
                
            } else {
                $Persona = $em->getRepository("DGAbgSistemaBundle:AbgPersona")->find($request->get('hPersona'));

                foreach ($array as $obj) {
                    $AbgPersonaIdioma = new AbgPersonaIdioma();
                    $IdIdioma = $em->getRepository("DGAbgSistemaBundle:CtlIdioma")->find($datos[$obj['0']]);
                    $AbgPersonaIdioma->setAbgPersona($Persona);
                    $AbgPersonaIdioma->setNivel($datos[$obj['1']]);
                    $AbgPersonaIdioma->setCtlioma($IdIdioma);
                    $em->persist($AbgPersonaIdioma);
                    $em->flush();
                    $data['msj'] = "Idioma registrado";
                }
            }

            $sqlEdu = "SELECT i.id As idIdioma,pi.id AS idPi,i.idioma As nombre, pi.nivel As nivel "
                    . " FROM abg_persona_idioma pi "
                    . " join ctl_idioma i on i.id=pi.ctl_idioma_id "
                    . " join abg_persona p on p.id=pi.abg_persona_id "
                    . " AND p.id=" . $request->get('hPersona')
                    . " order by i.idioma";
            $stm = $this->container->get('database_connection')->prepare($sqlEdu);
            $stm->execute();
            $data['Idiomas'] = $stm->fetchAll();

            return new Response(json_encode($data));
        } catch (Exception $e) {
            $data['error'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/disciplinas/get", name="disciplinas", options={"expose"=true})
     * @Method("GET")
     */
    public function getDisciplinasAction() {
        $request = $this->getRequest();
        $busqueda = $request->query->get('q');

        $em = $this->getDoctrine()->getEntityManager();
        $dql = "SELECT tp.id AS id, tp.abgTitulocol AS nombre "
                . "FROM DGAbgSistemaBundle:CtlTituloProfesional tp "
                . "WHERE upper(tp.abgTitulocol) LIKE upper(:busqueda) "
                . "ORDER BY tp.abgTitulocol ASC ";

        $data['data'] = $em->createQuery($dql)
                ->setParameters(array('busqueda' => "%" . $busqueda . "%"))
                ->setMaxResults(10)
                ->getResult();

        return new Response(json_encode($data));
    }

    /**
     * @Route("/idioma/get", name="idioma", options={"expose"=true})
     * @Method("GET")
     */
    public function getIdiomaAction() {
        try {
            $em = $this->getDoctrine()->getEntityManager();
            $dql = "SELECT i.id AS id, i.idioma AS nombre "
                    . "FROM DGAbgSistemaBundle:CtlIdioma i";

            $data['data'] = $em->createQuery($dql)->getArrayResult();

            return new Response(json_encode($data));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/edit/persona", name="edit_perfil_persona", options={"expose"=true})
     * @Method("POST")
     */
    public function EditPerfilPersonaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        try {
            $Persona = $em->getRepository("DGAbgSistemaBundle:AbgPersona")->find($request->get('hPersona'));
            switch ($request->get('n')) {
                case 0:
                    parse_str($request->get('dato'), $datos);

                    $Persona->setTelefonoFijo($datos['txtFijo']);
                    if ((($datos['txtMovil'] == ""))) {
                        $Persona->setTelefonoMovil(null);
                    }
                    $Persona->setCorreoelectronico($datos['txtEmail']);
                    $Persona->setDireccion($datos['txtDirecion']);



                    $data['msj'] = "Datos actualizados";
                    break;
                case 1:
                    $Persona->setNombres($request->get('nombres')['txtnombre']);
                    $Persona->setApellido($request->get('nombres')['txtApellido']);
                    break;
                case 2:
                    $Persona->setEstado($request->get('estado'));
                    break;

                case 3:

                    break;
                case 4:

                    break;
                case 5:
                    $Persona->setCorreoelectronico($request->get('correo'));
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
                    $Ciudad = $em->getRepository("DGAbgSistemaBundle:CtlCiudad");
                    $idCiudad = $Ciudad->find($request->get('ciudad'));

                    $Persona->setCtlCiudad($idCiudad);
                    $data['ciu'] = $Persona->getCtlCiudad()->getNombreCiudad();
                    $data['dept'] = $Ciudad->find($request->get('ciudad'))->getCtlEstado()->getNombreEstado();
                    break;
                case 10:
                    $Persona->setDescripcion($request->get('descripcion'));
                    $data['msj'] = "Dato actualizado";
                    break;
            }

            $em->merge($Persona);
            $em->flush();

            return new Response(json_encode($data));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/verificar_abg", name="verificar_abg", options={"expose"=true})
     * @Method("GET")
     */
    public function VerificarAbgAction() {
        $em = $this->getDoctrine()->getManager();

        $request = $this->getRequest();
        $result_sub = "";
        $result_especialida = "";
        $Experiencia = "";
        $Certificacion = "";
        $Curso = "";
        try {
            $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();
            $username = $this->container->get('security.context')->getToken()->getUser()->getId();

            $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                    . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil, p.estado As estado "
                    . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
            $result_persona = $em->createQuery($dql_persona)->getArrayResult();
            $nombreCorto = split(" ", $result_persona[0]['nombre'])[0] . " " . split(" ", $result_persona[0]['apellido'])[0];

            $dql_solict_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                    . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil, p.estado As estado "
                    . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.verificado=0 AND p.id=" . $idPersona;
            $result_solict_persona = $em->createQuery($dql_solict_persona)->getArrayResult();


            $dql_tipoPago = "SELECT p.id as id, p.tipoPago As nombre "
                    . " FROM DGAbgSistemaBundle:CtlTipoPago p ORDER BY p.tipoPago ASC";
            $TipoPago = $em->createQuery($dql_tipoPago)->getArrayResult();


            $dqlfoto = "SELECT fot.src as src "
                    . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
            $result_foto = $em->createQuery($dqlfoto)->getArrayResult();


            $dqlfoto = "SELECT fot.src as src, fot.estado As estado "
                    . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and fot.tipoFoto=1 ";
            $fotoP = $em->createQuery($dqlfoto)->getArrayResult();


            $sqlRol = "SELECT  r.id As id, r.rol As rol"
                    . " FROM  ctl_rol_usuario ru "
                    . " JOIN ctl_rol r ON r.id=ru.ctl_rol_id AND ru.ctl_usuario_id=" . $username;
            $stm = $this->container->get('database_connection')->prepare($sqlRol);
            $stm->execute();
            $RolUser = $stm->fetchAll();

            switch ($RolUser[0]['rol']) {
                case 'ROLE_USER':
                    return $this->render(':abgpersona:panelVerificacion.html.twig', array(
                                'nombreCorto' => $nombreCorto,
                                'abgPersona' => $result_persona,
                                'usuario' => $idPersona,
                                'TipoPago' => $TipoPago,
                                'abgFoto' => $result_foto,
                    ));

                    break;
                case 'ROLE_ADMIN':

                    $sqlverificacion = "SELECT p.id AS id, CONCAT(p.nombres, '  ', p.apellido)   AS nombre, fot.src AS src, "
                            . " p.direccion AS direccion, p.telefono_fijo AS Tfijo, p.telefono_movil AS movil, p.correoelectronico AS correo, "
                            . " p.titulo_profesional AS tituloProfesional "
                            . " FROM  abg_foto fot"
                            . " JOIN abg_persona p ON p.id=fot.abg_persona_id AND p.verificado=0 AND fot.tipo_foto=5 ";
                    $stm = $this->container->get('database_connection')->prepare($sqlverificacion);
                    $stm->execute();
                    $solicitud_verificacion = $stm->fetchAll();





                    return $this->render(':administracion:panelVerificacion.html.twig', array(
                                'nombreCorto' => $nombreCorto,
                                'abgPersona' => $result_solict_persona,
                                'usuario' => $idPersona,
                                'TipoPago' => $TipoPago,
                                'abgFoto' => $result_foto,
                                'solicitVerificacion' => $solicitud_verificacion,
                    ));
                    break;
            }
        } catch (Exception $e) {

            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }

        return $this->render('abgfacturacion/panelFacturacion.html.twig', array(
                    'abgFacturacions' => $abgFacturacions,
        ));
    }

    /**
     * @Route("/solicitud_verificar/get", name="solicitud_verificar", options={"expose"=true})
     * @Method("GET")
     */
    function SolicitudVerificarAction() {

        try {

            $sqlverificacion = "SELECT p.id AS id, CONCAT(p.nombres, '  ', p.apellido)   AS nombre, fot.src AS src, "
                    . " p.direccion AS direccion, p.telefono_fijo AS Tfijo, p.telefono_movil AS movil, p.correoelectronico AS correo, "
                    . " p.titulo_profesional AS tituloProfesional "
                    . " FROM  abg_foto fot"
                    . " JOIN abg_persona p ON p.id=fot.abg_persona_id AND p.verificado=0 AND fot.tipo_foto=5 ";
            $stm = $this->container->get('database_connection')->prepare($sqlverificacion);
            $stm->execute();
            $data['solverificado'] = $stm->fetchAll();

            return new Response(json_encode($data));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/cons_abg/get", name="cons_abg", options={"expose"=true})
     * @Method("GET")
     */
    public function getConsbgAction() {
        try {
            $em = $this->getDoctrine()->getEntityManager();
            $request = $this->getRequest();
            $idPersona = $request->get('abg');

//    $idPersona = $this->container->get('security.context')->getToken()->getUser()->getId();
            $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                    . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil,p.estado As estado, p.tituloProfesional AS tituloProfesional, "
                    . " p.verificado As verificado "
                    . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona . " AND p.verificado=0";
            $result_persona = $em->createQuery($dql_persona)->getArrayResult();

//Esta consulta  es la que jala el src de la foto dejela
            $dqlfoto = "SELECT fot.src as src "
                    . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
            $result_foto = $em->createQuery($dqlfoto)->getArrayResult();


            $dql_especialida = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre, pe.descripcion AS descripcion "
                    . " FROM  DGAbgSistemaBundle:CtlEspecialidad e "
                    . " JOIN DGAbgSistemaBundle:AbgPersonaEspecialida pe WHERE e.id=pe.ctlEspecialidad AND pe.abgPersona=" . $idPersona
                    . " GROUP by e.id "
                    . " ORDER BY e.nombreEspecialidad";
            $result_especialida = $em->createQuery($dql_especialida)->getArrayResult();

            $dql_sitio = "SELECT  w.id AS id, w.nombre AS nombre "
                    . " FROM  DGAbgSistemaBundle:AbgSitioWeb w "
                    . " JOIN DGAbgSistemaBundle:AbgPersona p WHERE p.id=w.abgPersona AND p.id=" . $idPersona;
            $sitio = $em->createQuery($dql_sitio)->getArrayResult();

            $dql_url = "SELECT  u.id AS id, u.url AS url "
                    . " FROM  DGAbgSistemaBundle:AbgUrlPersonalizada u "
                    . " JOIN DGAbgSistemaBundle:AbgPersona p WHERE p.id=u.abgPersona AND u.abgPersona=" . $idPersona;
            $url = $em->createQuery($dql_url)->getArrayResult();

            $data['datosP'] = $result_persona;
            $data['especialida'] = $sitio;
//  $data['sitioweb'] = $sitio[0]['nombre'];
            $data['foto'] = $result_foto[0]['src'];


            return new Response(json_encode($data));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/verificar/get", name="verificar", options={"expose"=true})
     * @Method("GET")
     */
    public function getVerificarAction() {
        try {
            $em = $this->getDoctrine()->getEntityManager();
            $request = $this->getRequest();
            $idPersona = $request->get('abg');




//    $idPersona = $this->container->get('security.context')->getToken()->getUser()->getId();

            $sqlverificacion = "SELECT p.id AS id, CONCAT(p.nombres, '  ', p.apellido)   AS nombre, fot.src AS src, "
                    . " p.direccion AS direccion, p.telefono_fijo AS Tfijo, p.telefono_movil AS movil, p.correoelectronico AS correo, "
                    . " p.titulo_profesional AS tituloProfesional, p.verificado  AS verificado"
                    . " FROM  abg_foto fot"
                    . " JOIN abg_persona p ON p.id=fot.abg_persona_id and fot.tipo_foto=5 AND p.id=" . $idPersona;
            $stm = $this->container->get('database_connection')->prepare($sqlverificacion);
            $stm->execute();
            $solicitud_verificacion = $stm->fetchAll();

            $dqlfoto = "SELECT fot.src as src "
                    . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
            $fotoPerfil = $em->createQuery($dqlfoto)->getArrayResult();

            $dqlfoto = "SELECT fot.src as src "
                    . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " AND fot.tipoFoto=5";
            $result_foto = $em->createQuery($dqlfoto)->getArrayResult();

            if ($request->get('ban') != null) {
                return $this->render('administracion/detalleVerificado.html.twig', array(
                            'datosP' => $solicitud_verificacion,
                            'carnet' => $result_foto[0]['src'],
                            'fotoPerfil' => $fotoPerfil
                ));
            } else {
                return $this->render('administracion/verificar.html.twig', array(
                            'datosP' => $solicitud_verificacion,
                            'carnet' => $result_foto[0]['src'],
                            'fotoPerfil' => $fotoPerfil
                ));
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/verificados/get", name="verificados", options={"expose"=true})
     * @Method("GET")
     */
    public function getVerificadosAction() {
        try {


            $sqlverificados = "SELECT p.id AS id, CONCAT(p.nombres, '  ', p.apellido)   AS nombre, fot.src AS src, "
                    . " p.direccion AS direccion, p.telefono_fijo AS Tfijo, p.telefono_movil AS movil, p.correoelectronico AS correo, "
                    . " p.titulo_profesional AS tituloProfesional "
                    . " FROM  abg_foto fot"
                    . " JOIN abg_persona p ON p.id=fot.abg_persona_id and fot.tipo_foto=5 AND p.verificado=1 ";
            $stm = $this->container->get('database_connection')->prepare($sqlverificados);
            $stm->execute();
            $verificados = $stm->fetchAll();

            return $this->render(':administracion:verificados.html.twig', array(
                        'verificados' => $verificados,
                        'val' => 1
            ));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/suscripcion_abg/get", name="suscripcion_abg", options={"expose"=true})
     * @Method("GET")
     */
    function SuscripcionAbgActions() {
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction();
        $request = $this->getRequest();
        $result_sub = "";
        $result_especialida = "";
        $Experiencia = "";
        $Certificacion = "";
        $Curso = "";
        try {

            $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();
            $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                    . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil, p.estado As estado, p.tituloProfesional AS tprofesional "
                    . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
            $result_persona = $em->createQuery($dql_persona)->getArrayResult();


            $nombreCorto = split(" ", $result_persona[0]['nombre'])[0] . " " . split(" ", $result_persona[0]['apellido'])[0];

            $dqlfoto = "SELECT fot.src as src "
                    . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
            $result_foto = $em->createQuery($dqlfoto)->getArrayResult();


            $dqlfoto = "SELECT fot.src as src, fot.estado As estado "
                    . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and fot.tipoFoto=1 ";
            $fotoP = $em->createQuery($dqlfoto)->getArrayResult();



            $sql = "SELECT p.id AS idAbg, CONCAT(p.nombres, '  ', p.apellido)   AS nombre, f.id As idFact, f.monto AS monto, f.plazo As plazo, "
                    . " f.servicio As servicio, tp.tipo_pago As tipoPago, f.descripcion As descripcion, date_format(f.fecha_pago, '%d/%m/%Y') As fechaPago "
                    . " FROM abg_persona p "
                    . " JOIN abg_facturacion f"
                    . " ON  p.id=f.abg_persona_id AND p.id=" . $idPersona
                    . " JOIN ctl_tipo_pago tp "
                    //   . " ON  tp.id=f.abg_tipo_pago_id"
                    . " ORDER BY p.nombres ASC ";

            $sqlPersona = "select per.id AS idAbg, per.codigo AS codigo, CONCAT(per.nombres, '  ', per.apellido) as nombre, foto.src as src, uper.url as url, date_format(fecha_ingreso, '%d/%m/%Y') as fecha,
        date_format(fac.fecha_pago, '%d/%m/%Y') AS fechaPago, fac.servicio AS servicio, fac.plazo,TIMESTAMPDIFF(DAY,CURDATE(),fac.fecha_pago) AS caducidad, fac.monto AS monto, 
        fac.descripcion As descripcion
                        from ctl_usuario usu 
                        inner join abg_persona per on usu.rh_persona_id = per.id AND per.id=" . $idPersona .
                    " inner join abg_foto foto on foto.abg_persona_id = per.id
                        inner join abg_url_personalizada uper on uper.abg_persona_id = per.id
                        inner join abg_facturacion fac on per.id=fac.abg_persona_id 
                       where foto.estado = 1 and uper.estado = 1
                        order by caducidad desc
                        limit 0, 12";

            $stm = $this->container->get('database_connection')->prepare($sqlPersona);
            $stm->execute();
            $suscripcion = $stm->fetchAll();


//  return $this->render('abgpersona/panelAdministrativoAbg.html.twig', array(
            return $this->render(':abgpersona:panelSuscripcion.html.twig', array(
                        'nombreCorto' => $nombreCorto,
                        'abgPersona' => $result_persona,
                        'usuario' => $idPersona,
                        'abgFoto' => $result_foto,
                        'suscripcion' => $suscripcion
            ));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));

// echo $e->getMessage();   
        }
    }

}
