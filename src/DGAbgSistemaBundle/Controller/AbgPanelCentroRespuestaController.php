<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     * @Route("/respuestapanel", name="admin_respanel_centro")
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
        $abgfoto = $em->getRepository('DGAbgSistemaBundle:AbgFoto')->find($personaId);
        
        $srcfoto = $abgfoto->getSrc();
        
        
        //var_dump($personaId->getNombres());
        //die();
        return $this->render('panelcentropregabog/panelrespuestacentro.html.twig', array('pregunta'=>$pregunta,'srcfoto'=>$srcfoto, 'nombres'=>$personaId->getNombres(), 'apellidos'=>$personaId->getApellido()));
    }
    
    /**
     * Respuesta Abogado
     *
     * @Route("/enviorespuestapanel", name="envio_respuesta_panel")
     * @Method("POST")
     */
    public function enviorespuestapanelAction(Request $request){
        //$abgPregunta = new AbgPregunta();
        $parameters = $request->request->all();
        $idpreg = $request->get('idpreg');
        $respuesta = $request->get('respuesta');
        
        $username = $this->container->get('security.context')->getToken()->getUser();
        $personaId = $username->getRhPersona();
        
        $em = $this->getDoctrine()->getManager();
        $abgPregunta = $em->getRepository('DGAbgSistemaBundle:AbgPregunta')->find($idpreg);
        
        //Aqui se realizan las actualizaciones
        $abgPregunta->setCtlUsuario($username);
        $abgPregunta->setRespuesta($respuesta);
        $abgPregunta->setEstado(0);
        //var_dump($abgPregunta);
        //die();
        
        $em->merge($abgPregunta);        
        $em->flush();
        
        
        //var_dump($parameters);
        //die();
        return $this->render('panelcentropregabog/respuestaenviadasuccess.html.twig');
    }
         
    /**
     * Muestra la interfaz para hacer una nueva pregunta.
     *
     * @Route("/panelrespuestasuccess", name="panel_respuesta_success", options={"expose"=true})
     * @Method({"GET", "POST"})
     * 
     */
    public function panelrespuestasuccessAction()
    {
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
        $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                    . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil "
                    . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=91";
            $result_persona = $em->createQuery($dql_persona)->getArrayResult();
        
        $dqlfoto = "SELECT fot.src as src "
                . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=91 and fot.estado=1 and fot.tipoFoto=1";
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
    //var_dump($idabg);
    //die();
    $sql = "SELECT COUNT(*) as total FROM abg_pregunta, ctl_usuario WHERE abg_pregunta.estado=1 AND ctl_usuario_id=ctl_usuario.id AND ctl_usuario.rh_persona_id =".$idabg;
        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $totsincont = $stmt->fetchAll();
        //var_dump($totsincont[0]['total']);
        //die();
    $sql = "SELECT preg.id as idpreg, preg.pregunta FROM abg_pregunta preg, ctl_usuario WHERE preg.estado=1 AND ctl_usuario_id=ctl_usuario.id AND ctl_usuario.rh_persona_id =" . $idabg;
    $em = $this->getDoctrine()->getManager();
    $stmt = $em->getConnection()->prepare($sql);
    $stmt->execute();
    $preguntas = $stmt->fetchAll();

        return $this->render('panelcentropregabog/panelistpreguntas.html.twig', array('abgPersona'=>$result_persona, 'abgFoto'=>$result_foto, 'ciuda'=>$result_ciuda,'usuario'=>'adsasd', 'preguntas'=>$preguntas, 'totsincont'=>$totsincont[0]['total']));
    }

}//FIN DEL CONTROLADOR

