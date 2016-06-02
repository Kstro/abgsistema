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
     *
     * @Route("/respuestapanel", name="admin_respanel_centro", options={"expose"=true})
     * @Method("GET")
     */
    public function respuestacentroAction(Request $request) {
        $id = $request->get('id');
        //var_dump($id);
        //die();
        $em = $this->getDoctrine()->getManager();
        $pregunta = $em->getRepository('DGAbgSistemaBundle:AbgPregunta')->find($id);


        $username = $this->container->get('security.context')->getToken()->getUser();
        $personaId = $username->getRhPersona();
        $abgfoto = $em->getRepository('DGAbgSistemaBundle:AbgFoto')->findOneBy(array('abgPersona'=>$personaId));
        
        $srcfoto = $abgfoto->getSrc();


        //var_dump($personaId->getNombres());
        //die();
        return $this->render('panelcentropregabog/panelrespuestacentro.html.twig', array('pregunta' => $pregunta, 'srcfoto' => $srcfoto, 'nombres' => $personaId->getNombres(), 'apellidos' => $personaId->getApellido()));
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
        $abgPregunta = $em->getRepository('DGAbgSistemaBundle:AbgPregunta');
        $pregunta= $abgPregunta->find($idpreg);
        $correo_anonimo= $abgPregunta->find($idpreg)->getCorreoelectronico();
        
        $this->get('pregunta_respuesta')->sendEmail($correo_anonimo, "", "", "", "
                    <table style=\"width: 540px; margin: 0 auto;\">
                      <tr>
                        <td class=\"panel\" style=\"border-radius:4px;border:1px #dceaf5 solid; color:#000 ; font-size:11pt;font-family:proxima_nova,'Open Sans','Lucida Grande','Segoe UI',Arial,Verdana,'Lucida Sans Unicode',Tahoma,'Sans Serif'; padding: 30px !important; background-color: #FFF;\">
                        <center>
                          <img style=\"width:50%;\" src=\"http://marvinvigil.info/ab/src/img/logogris.png\">
                        </center>                                                
                            <p>Hola ".$correo_anonimo." tu pregunta ha sido respondida</p>
                            <p>Haz click en el enlace y se el primero en contestar</p>
                            <a href='http://http://abg.localhost/app_dev.php/preguntascentro/respuestas?id=".$idpreg."'>Clik aqui para responder</a> 

                        </td>
                        <td class=\"expander\"></td>
                      </tr>
                    </table>
                "); 
             
        $pregunta->setCtlUsuario($username);
        $pregunta->setRespuesta($respuesta);
        $pregunta->setFechaRespuesta(new \DateTime ('now'));
        $pregunta->setEstado(0);
        $em->merge($pregunta);
        $em->flush();
        return $this->redirect($this->generateUrl('panel_list_pregunta'));
        } catch (Exception $e) {
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

        $sql = "SELECT COUNT(*) as total FROM abg_pregunta, ctl_usuario WHERE abg_pregunta.estado=1 AND ctl_usuario_id=ctl_usuario.id AND ctl_usuario.rh_persona_id =" . $idabg;
        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $totsincont = $stmt->fetchAll();

        $sql = "SELECT preg.id as idpreg, preg.pregunta,  preg.fechapregunta AS fecha "
                . " FROM abg_pregunta preg "
                . " JOIN ctl_especialidad esp "
                . " ON esp.id=preg.ctl_especialidad "
                . " JOIN abg_persona_especialidad pe "
                . " ON pe.ctl_especialidad_id=esp.id AND pe.abg_persona_id=" . $idPersona
                . " WHERE preg.estado=1"
                . " ORDER BY  preg.fechapregunta DESC ";
        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $preguntas = $stmt->fetchAll();



        return $this->render('panelcentropregabog/panelistpreguntas.html.twig', array('abgPersona' => $result_persona, 'abgFoto' => $result_foto, 'ciuda' => $result_ciuda, 'usuario' => $username, 'preguntas' => $preguntas, 'totsincont' => $totsincont[0]['total']));
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
$idUser=$this->container->get('security.context')->getToken()->getUser()->getId();
            
                      
            $sql = "SELECT preg.id as idpreg, preg.pregunta,  preg.fechapregunta AS fecha "
                    . " FROM abg_pregunta preg "
                    . " JOIN ctl_especialidad esp "
                    . " ON esp.id=preg.ctl_especialidad AND preg.ctl_usuario_id=". $idUser
                    . " JOIN abg_persona_especialidad pe "
                    . " ON pe.ctl_especialidad_id=esp.id AND pe.abg_persona_id=" . $idPersona
                    . " WHERE preg.estado=0"
                    . " ORDER BY  preg.fechapregunta DESC ";
            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $data['Respreguntas'] = $stmt->fetchAll();
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
                        $user= $this->container->get('security.context')->getToken()->getUser()->getId();
                   
            $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();
            
               $dqlfoto = "SELECT fot.src as src "
                    . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
            $foto= $em->createQuery($dqlfoto)->getArrayResult();
            $data['foto']=$foto[0]['src'];

            
            $sql = "SELECT preg.id as idpreg, preg.pregunta,  preg.fechapregunta AS fecha, preg.respuesta AS respuesta "
                    . " FROM abg_pregunta preg "
                    . " JOIN ctl_especialidad esp "
                    . " ON esp.id=preg.ctl_especialidad AND preg.ctl_usuario_id=" . $user
                    . " JOIN abg_persona_especialidad pe "
                    . " ON pe.ctl_especialidad_id=esp.id "
                    . " WHERE preg.estado=0"
                    . " ORDER BY  preg.fechapregunta DESC ";
            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $data['Respreguntas'] = $stmt->fetchAll();
            return new Response(json_encode($data));
        } catch (\Exception $e) {
            $data['error'] = $e->getMessage();
            return new Response(json_encode($data));
        }
    }

}

//FIN DEL CONTROLADOR

