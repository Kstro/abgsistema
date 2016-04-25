<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGAbgSistemaBundle\Entity\AbgPregunta;
use DGAbgSistemaBundle\Entity\AbgSubespecialidad;


/**
 * Success controller.
 *
 * @Route("/preyres")
 */
class AbgPreyResController extends Controller{
    
    /**
     * Muestra la interfaz para hacer una nueva pregunta.
     *
     * @Route("/pregunta", name="pregunta", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return $this->render('preyres/pregunta.html.twig');     
    }
    
    /**
     * Esta funcion recibe el contenido de la pregunta en la interfaz inicial
     *
     * @Route("/preg_det", name="preg_deta", options={"expose"=true})
     * @Method("POST")
     * @Template()
     */
    public function preguntaDetalleAction(Request $request) {
               
        $parameters = $request->request->all();
        $pregunta =  $parameters['pregunta'];
        $em = $this->getDoctrine()->getManager();
        $ctlSubespecialidad = $em->getRepository('DGAbgSistemaBundle:CtlSubespecialidad')->findAll();
        return $this->render('preyres/pregunta_detalle.html.twig', array('pregunta'=>$pregunta, 'ctlSubespecialidad'=>$ctlSubespecialidad));
    }
    
    /**
     * Muestra la interfaz para hacer una nueva pregunta.
     *
     * @Route("/enviopregunta", name="envio_pregunta", options={"expose"=true})
     * @Method("POST")
     * @Template()
     */
    public function envioPreguntaAction(Request $request) {
        
        
        $fechapregunta = date('Y-m-d');  
        $parameters = $request->request->all();
        
        $pregunta =  $parameters['pregunta'];
        $detalle =  $parameters['detalle'];
        $subespecialidad =  $parameters['subespecialidad'];
        $email =  $parameters['email'];
        
        $abgPregunta = new AbgPregunta();
        //$ctlSubespecialidad = new \DGAbgSistemaBundle\Entity\CtlSubespecialidad();
        $abgPregunta->setPregunta($pregunta);
        $abgPregunta->setDetalle($detalle);
        $abgPregunta->setEstado("1");
        $abgPregunta->setCorreoelectronico($email);
        $abgPregunta->setFechaPregunta($fechapregunta);
        
        
        $em = $this->getDoctrine()->getManager();
        $subespeid = $em->getRepository('DGAbgSistemaBundle:CtlSubespecialidad')->find($subespecialidad);
        
        $abgPregunta->setAbgSubespecialidad($subespeid);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($abgPregunta);        
        $em->flush();
          
        $this->get('envio_correo')->sendEmail($email, "", "", "", "
                                        <table style=\"width: 540px; margin: 0 auto;\">
                                          <tr>
                                            <td class=\"panel\" style=\"border-radius:4px;border:1px #dceaf5 solid; color:#000 ; font-size:11pt;font-family:proxima_nova,'Open Sans','Lucida Grande','Segoe UI',Arial,Verdana,'Lucida Sans Unicode',Tahoma,'Sans Serif'; padding: 30px !important; background-color: #FFF;\">
                                            <center>
                                              <img style=\"width:50%;\" src=\"http://expressionsprint.com/img/logo.jpg\">
                                            </center>
                                                <p>Su orden
                                                <b>#1</b> Hola ".$email." hay una nueva pregunta en la que puedes participar dando tu opinion
                                                </p>
                                            </td>
                                            <td class=\"expander\"></td>
                                          </tr>
                                        </table>
                                    ");
        return $this->render('enviopregsuccess/success.html.twig');
    }

}
