<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGAbgSistemaBundle\Entity\AbgEntrada;
use DGAbgSistemaBundle\Entity\CtlUsuario;
use DGAbgSistemaBundle\Entity\AbgImagenBlog;
use DGAbgSistemaBundle\Entity\AbgPregunta;
use DGAbgSistemaBundle\Entity\AbgRespuestaPregunta;
use Symfony\Component\HttpFoundation\Response;
use DGAbgSistemaBundle\Form\AbgPersonaType;

/**
 * Respuesta panel controller.
 *
 * @Route("/admin/panelrespuestacentro")
 */
class AbgPanelCentroRespuestaController extends Controller {

    /**
     * Respuesta
     * @Route("/respuesta_abg", name="admin_pregunta_respuesta", options={"expose"=true})
     * @Method("GET")
     */
    public function PreguntaRespuestaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
       
        try {
            $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();
            $username= $this->container->get('security.context')->getToken()->getUser();
           
            $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                    . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil, p.estado As estado, p.codigo as codigo "
                    . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
            $result_persona = $em->createQuery($dql_persona)->getArrayResult();
            $nombreCorto=split(" ",$result_persona[0]['nombre'])[0]." ".split(" ",$result_persona[0]['apellido'])[0];
            
            $dqlfoto = "SELECT fot.src as src "
                    . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
            $result_foto = $em->createQuery($dqlfoto)->getArrayResult();


            $dqlfoto = "SELECT fot.src as src, fot.estado As estado "
                    . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and fot.tipoFoto=1 ";
            $fotoP = $em->createQuery($dqlfoto)->getArrayResult();

            $id = $request->get('id');

            $em = $this->getDoctrine()->getManager();
            $pregunta = $em->getRepository('DGAbgSistemaBundle:AbgPregunta')->find($id);
            

            $username = $this->container->get('security.context')->getToken()->getUser();
            $personaId = $username->getRhPersona();
            $abgfoto = $em->getRepository('DGAbgSistemaBundle:AbgFoto')->findOneBy(array('abgPersona' => $personaId));
            
                  $abgRespuestaPregunta = $em->getRepository('DGAbgSistemaBundle:AbgRespuestaPregunta');
                
            $Respuesta = $abgRespuestaPregunta->findBy(array('abgPregunta' => $id, 'ctlUsuario' => $username));
       
      $respuesta="";
      $estado="";
      $tiempoRes="";
    
      if(! empty($Respuesta))
      {
      $estado=1;
      $respuesta=$Respuesta[0]->getRespuesta();
      
      }
      else
      {
           $estado=0; 
      }
      if ($pregunta->getFechaPregunta() != NULL) {
        
       //         array_push($tiemposRespuesta, $this->tiempo_transcurrido($pregunta->getFechaPregunta()->format('Y-m-d H:i:s')));
             $tiempo = $this->tiempo_transcurrido($pregunta->getFechaPregunta()->format('Y-m-d H:i:s'));
        } else {
            $tiempo = NULL;
        }
         if(! empty($Respuesta))
      {
            
            if ($Respuesta[0]->getFechaRespuesta() != NULL) {
        
       //         array_push($tiemposRespuesta, $this->tiempo_transcurrido($pregunta->getFechaPregunta()->format('Y-m-d H:i:s')));
             $tiempoRes = $this->tiempo_transcurrido($Respuesta[0]->getFechaRespuesta()->format('Y-m-d H:i:s'));
        } else {
            $tiempoRes = NULL;
        }
         }
            $srcfoto = $abgfoto->getSrc();
            return $this->render('panelcentropregabog/panelRespuestaPregunta.html.twig', array('pregunta' => $pregunta,
            'nombreCorto'=>$nombreCorto,
                        'srcfoto' => $srcfoto,
                        'nombres' => $personaId->getNombres(),
                        'apellidos' => $personaId->getApellido(),
                        'abgPersona' => $result_persona,
                        'usuario' => $idPersona,
                        'abgFoto' => $result_foto,
                        'estado'=>$estado,
                'respuesta'=>$respuesta,
                'tiempo'=>$tiempo,
                'tiempoRes'=>$tiempoRes));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
     
            $em->close();

            // echo $e->getMessage();   
        }
    }

    /**
     * Respuesta desde correo
     * @Route("/respuestapanel", name="admin_respanel_centro", options={"expose"=true})
     * @Method("GET")
     */
    public function respuestacentroAction(Request $request) {
        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();
        $pregunta = $em->getRepository('DGAbgSistemaBundle:AbgPregunta')->find($id);
    $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();
        $username = $this->container->get('security.context')->getToken()->getUser();
        $personaId = $username->getRhPersona();
        $persona = $em->getRepository('DGAbgSistemaBundle:AbgPersona')->find($idPersona)->getEstado();
     
        $abgfoto = $em->getRepository('DGAbgSistemaBundle:AbgFoto')->findOneBy(array('abgPersona' => $personaId));
        
     $tiemposRespuesta = array();
        if ($pregunta->getFechaPregunta() != NULL) {
        
       //array_push($tiemposRespuesta, $this->tiempo_transcurrido($pregunta->getFechaPregunta()->format('Y-m-d H:i:s')));
             $tiempo = $this->tiempo_transcurrido($pregunta->getFechaPregunta()->format('Y-m-d H:i:s'));
        } else {
            $tiempo = NULL;
        }
        $srcfoto = $abgfoto->getSrc();
        return $this->render('panelcentropregabog/panelrespuestacentro.html.twig', 
                array('pregunta' => $pregunta,
                    'srcfoto' => $srcfoto,
                    'nombres' => $personaId->getNombres(), 
                    'apellidos' => $personaId->getApellido(),
                'tiempo'=>$tiempo,
                'estado'=>$persona));
    }

    /**
     * Respuesta Abogado
     *
     * @Route("/enviorespuestapanel", name="envio_respuesta_panel" )
     * @Method("POST")
     */
    public function enviorespuestapanelAction(Request $request) {
        try {
            $parameters = $request->request->all();
            $idpreg = $request->get('idpreg');
            $respuesta = $request->get('respuesta');

            $username = $this->container->get('security.context')->getToken()->getUser();
            $personaId = $username->getRhPersona();

            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->beginTransaction();
            $abgPregunta = $em->getRepository('DGAbgSistemaBundle:AbgPregunta');
            $pregunta = $abgPregunta->find($idpreg);

            $abgRespuestaPregunta = $em->getRepository('DGAbgSistemaBundle:AbgRespuestaPregunta');
            $Respuesta = $abgRespuestaPregunta->findBy(array('abgPregunta' => $idpreg, 'ctlUsuario' => $username));


            if ($abgPregunta->find($idpreg)->getContador() < 4) {
                //  $abogado=$abgPregunta->findBy(array('id'=>$idpreg,'ctlUsuario'=>$username));
                if ($Respuesta == null) {
                    $contador = $abgPregunta->find($idpreg)->getContador() + 1;
                    $correo_anonimo = $abgPregunta->find($idpreg)->getCorreoelectronico();
                    $AbgRespuestaPregunta = new AbgRespuestaPregunta();
                    if ($contador == 3) {
                        $pregunta->setEstado(0);
                    } else {
                        $pregunta->setEstado(1);
                    }
                    $pregunta->setContador($contador);
                    $em->merge($pregunta);
                    $em->flush();

                    $AbgRespuestaPregunta->setPregunta($pregunta);
                    $AbgRespuestaPregunta->setCtlUsuario($username);
                    $AbgRespuestaPregunta->setRespuesta($respuesta);
                    $AbgRespuestaPregunta->setFechaRespuesta(new \DateTime('now'));
                    $em->persist($AbgRespuestaPregunta);
                    $em->flush();

                    $this->get('pregunta_respuesta')->sendEmail($correo_anonimo, "", "", "", "
                    <table style=\"width: 540px; margin: 0 auto;\">
                      <tr>
                        <td class=\"panel\" style=\"border-radius:4px;border:1px #dceaf5 solid; color:#000 ; font-size:11pt;font-family:proxima_nova,'Open Sans','Lucida Grande','Segoe UI',Arial,Verdana,'Lucida Sans Unicode',Tahoma,'Sans Serif'; padding: 30px !important; background-color: #FFF;\">
                        <center>
                          <img style=\"width:50%;\" src=\"http://marvinvigil.info/ab/src/img/logogris.png\">
                        </center>
                            <p>Hola " . $correo_anonimo . ", tu pregunta ha sido respondida</p>
                            <p>Haz click en el enlace para ver la respuesta</p>
                            <a href='http://abg.localhost/app_dev.php/preguntascentro/respuestas_pregunta/" .$idpreg ."'>Clik aqui para ver la respuesta</a> 
                        </td>
                        <td class=\"expander\"></td>
                      </tr>
                    </table>
                ");
                }
            }
            $em->getConnection()->commit();
            return $this->redirect($this->generateUrl('panel_list_pregunta'));
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            $em->close();

            $data['msj'] = $e->getMessage();
            return new Response(json_encode($data));
        }
    }

    /**
     * Muestra la interfaz para hacer una nueva pregunta.
     *
     * @Route("/panelrespuestasuccess", name="panel_respuesta_success", options={"expose"=true})
     * @Method({"GET", "POST"})
     * 
     */
    public function panelrespuestasuccessAction() {
        return $this->render('panelcentropregabog/respuestaenviadasuccess.html.twig');
    }

//    Esta funcion llama a la lista de preguntas sin contestar

    /**
     * Muestra la interfaz para hacer una nueva pregunta.
     *
     * @Route("/panelistpre", name="panel_list_pregunta", options={"expose"=true})
     * @Method({"GET"})
     * 
     */
    public function panelistapreguntasincontAction() {

        $em = $this->getDoctrine()->getManager();
        $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();
        $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil, p.estado As estado, p.tituloProfesional AS tprofesional, p.verificado As verificado "
                . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
        $result_persona = $em->createQuery($dql_persona)->getArrayResult();
        
        $nombreCorto=split(" ",$result_persona[0]['nombre'])[0]." ".split(" ",$result_persona[0]['apellido'])[0];
  

        $dqlfoto = "SELECT fot.src as src "
                . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
        $result_foto = $em->createQuery($dqlfoto)->getArrayResult();

        $dql_ciudad = "SELECT c.nombreCiudad As nombre, es.nombreEstado estado"
                . " FROM DGAbgSistemaBundle:AbgPersona p "
                . " JOIN DGAbgSistemaBundle:CtlCiudad c WHERE p.ctlCiudad=c.id AND p.id=91"
                . " JOIN DGAbgSistemaBundle:CtlEstado es WHERE es.id=c.ctlEstado ";
        $result_ciuda = $em->createQuery($dql_ciudad)->getArrayResult();

        //ESTO ES PARA LA LISTA DE PREGUNTAS SIN CONTESTAR 
        //$em = $this->getDoctrine()->getManager();
        //$preguntas = $em->getRepository('DGAbgSistemaBundle:AbgPregunta')->findBy(array('estado' => 1), array('id' => 'DESC'));
        //DETERMINA LA CANTIDAD DE PREGUNTAS SIN CONTESTAR
        $username = $this->container->get('security.context')->getToken()->getUser();
        $personaId = $username->getRhPersona();
        $idabg = $username->getRhPersona()->getId();

        $sql = "SELECT COUNT(*) as total FROM abg_pregunta, ctl_usuario "
                . " WHERE abg_pregunta.estado=1 AND ctl_usuario_id=ctl_usuario.id AND ctl_usuario.rh_persona_id =" . $idabg;
        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $totsincont = $stmt->fetchAll();
        
        
//        var_dump($username->getId());
//        var_dump($personaId->getId());
//        var_dump($idPersona);
        
        $sql = "SELECT preg.id as idpreg, preg.pregunta, preg.fechapregunta AS fecha, resp.id
                FROM abg_pregunta preg LEFT JOIN abg_respuesta_pregunta resp on preg.id=resp.abg_pregunta 
                JOIN ctl_especialidad esp ON esp.id=preg.ctl_especialidad 
                JOIN abg_persona_especialidad pe ON pe.ctl_especialidad_id=esp.id AND pe.abg_persona_id = ".$idPersona."
                WHERE resp.respuesta is null AND preg.estado=1 and preg.contador < 3 and (resp.ctl_usuario_id <> ".$username->getId()." or resp.id is null)  
                ORDER BY preg.fechapregunta ASC";
        
        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $preguntas = $stmt->fetchAll();
        
        
  
        $fecha = array();
        foreach ($preguntas as $key => $value) {
            $fechaRespuesta = $this->tiempo_transcurrido($value['fecha']);
            array_push($fecha, $fechaRespuesta);
        }            
        
        
        return $this->render('panelcentropregabog/panelistpreguntas.html.twig', 
                array('fechaRespuesta' => $fecha,
                    'nombreCorto'=>$nombreCorto,
                    'abgPersona' => $result_persona, 
                    'abgFoto' => $result_foto,
                    'ciuda' => $result_ciuda, 
                    'usuario' => $username, 
                    'preguntas' => $preguntas,
                    'totsincont' => $totsincont[0]['total']));
    }

    /**
     * Preguntas respondidas.
     *
     * @Route("/preguntas_resps", name="preguntas_resps", options={"expose"=true})
     * @Method({"GET", "POST"})
     * 
     */
    public function PreguntasRespsAction() {
        try {
            $em = $this->getDoctrine()->getManager();
            $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();
            $idUser = $this->container->get('security.context')->getToken()->getUser()->getId();

            $sql = "SELECT preg.id as idpreg, preg.pregunta,  preg.fechapregunta AS fecha, resp.fecha_respuesta as frespuesta
                     FROM abg_respuesta_pregunta resp 
                     INNER JOIN abg_pregunta preg 
                     ON resp.abg_pregunta = preg.id 
                     INNER JOIN ctl_especialidad esp 
                     ON esp.id=preg.ctl_especialidad AND resp.ctl_usuario_id=" . $idUser . "
                     JOIN abg_persona_especialidad pe 
                     ON pe.ctl_especialidad_id=esp.id AND pe.abg_persona_id=" . $idPersona . "
                     ORDER BY  resp.fecha_respuesta DESC ";
            
            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
           
            $data['Respreguntas'] = $stmt->fetchAll();
           
            
            $fecha = array();
            foreach ($data['Respreguntas'] as $key => $value) {
                $fechaRespuesta = $this->tiempo_transcurrido($value['frespuesta']);
                array_push($fecha, $fechaRespuesta);
            }            
        
            $data['fechasRespuesta'] = $fecha;
            
            return new Response(json_encode($data));
        } catch (\Exception $e) {
            $data['error'] = $e->getMessage();
            return new Response(json_encode($data));
        }
    }

    /**
     * Pregunta y respuesta.
     *
     * @Route("/pregunta_resp_abg", name="pregunta_resp_abg", options={"expose"=true})
     * @Method({"GET"})
     * 
     */
    public function PreguntaRespAbgAction() {
        try {
            $em = $this->getDoctrine()->getManager();
            $request = $this->getRequest();
            $id = $request->get('id');
            $user = $this->container->get('security.context')->getToken()->getUser();

            $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();

            $dqlfoto = "SELECT fot.src as src "
                    . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
            $foto = $em->createQuery($dqlfoto)->getArrayResult();
            $data['foto'] = $foto[0]['src'];

//            $sql = "SELECT DISTINCT preg.id as idpreg, preg.pregunta,  preg.fechapregunta AS fecha, resp.respuesta AS respuesta "
//                    . " FROM abg_respuesta_pregunta resp "
//                    . " INNER JOIN abg_pregunta preg "
//                    . " ON resp.abg_pregunta = preg.id "
//                    . " JOIN ctl_especialidad esp "
//                    . " ON esp.id=preg.ctl_especialidad AND resp.ctl_usuario_id=" . $user
//                    . " JOIN abg_persona_especialidad pe "
//                    . " ON pe.ctl_especialidad_id=esp.id "
//                    . " ORDER BY  preg.fechapregunta DESC ";
//            
//            $em = $this->getDoctrine()->getManager();
//            $stmt = $em->getConnection()->prepare($sql);
//            $stmt->execute();
//            $data['Respreguntas'] = $stmt->fetchAll(); 
            
            $abgRespuestaPregunta = $em->getRepository('DGAbgSistemaBundle:AbgRespuestaPregunta');
            $Respreguntas = $abgRespuestaPregunta->findOneBy(array('abgPregunta' => $id, 'ctlUsuario' => $user));

            $data['fechaPregunta'] = $Respreguntas->getPregunta()->getFechaPregunta()->format('Y-m-d H:i:s');
            $data['pregunta'] = $Respreguntas->getPregunta()->getPregunta();
            $data['tiempoPregunta'] = $this->tiempo_transcurrido($data['fechaPregunta']);
            
            $data['fechaRespuesta'] = $Respreguntas->getFechaRespuesta()->format('Y-m-d H:i:s');
            $data['respuesta'] = $Respreguntas->getRespuesta();
            $data['tiempoRespuesta'] = $this->tiempo_transcurrido($data['fechaRespuesta']);
                    
            return new Response(json_encode($data));           
        } catch (\Exception $e) {
            $data['error'] = $e->getMessage();            
            return new Response(json_encode($data));
        }
    }

    function tiempo_transcurrido($fecha) 
    {
        if(empty($fecha)) {
            return "No hay fecha";
        }

        $intervalos = array("segundo", "minuto", "hora", "día", "semana", "mes", "año");
        $duraciones = array("60","60","24","7","4.35","12");

        $ahora = time();
        $Fecha_Unix = strtotime($fecha);

        if(empty($Fecha_Unix)) {   
            return "Fecha incorrecta";
        }
        if($ahora > $Fecha_Unix) {   
            $diferencia = $ahora - $Fecha_Unix;
            $tiempo = "Hace";
        } else {
            $diferencia = $Fecha_Unix -$ahora;
            $tiempo = "Dentro de";
        }
        for($j = 0; $diferencia >= $duraciones[$j] && $j < count($duraciones)-1; $j++) {
            $diferencia /= $duraciones[$j];
        }

        $diferencia = round($diferencia);

        if($diferencia != 1) {
            $intervalos[5].="e"; //MESES
            $intervalos[$j].="s";
        }
        
        
        if($intervalos[$j] == 'meses' and $diferencia >= 12){
            $diferencia /= $duraciones[$j];
            $j++;
            $diferencia = round($diferencia);
            
            if($diferencia != 1) {
                $intervalos[$j].="s";
            }
        }

        return "$tiempo $diferencia $intervalos[$j]";
    }
}

//FIN DEL CONTROLADOR

