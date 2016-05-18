<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGAbgSistemaBundle\Entity\AbgPregunta;
use DGAbgSistemaBundle\Entity\AbgSubespecialidad;
use DGAbgSistemaBundle\Entity\CtlEspecialidad;


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
     * @Method({"GET", "POST"})
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
        $ctlespecialidad = $em->getRepository('DGAbgSistemaBundle:CtlEspecialidad')->findAll();
        return $this->render('preyres/pregunta_detalle.html.twig', array('pregunta'=>$pregunta, 'ctlEspecialidad'=>$ctlespecialidad));
    }
    
    /**
     * Muestra la interfaz para hacer una nueva pregunta.
     *
     * @Route("/enviopregunta", name="envio_pregunta", options={"expose"=true})
     * @Method("POST")
     * @Template()
     */
    public function envioPreguntaAction(Request $request) {
        
//        $username = $this->container->get('security.context')->getToken()->getUser();
        //var_dump($username);
        //die();
//       $personaId = $username->getRhPersona();
        //$idabg = $username->getRhPersona()->getId();
        
        $fechapregunta = date('Y-m-d');  
        $parameters = $request->request->all();
                        
        $pregunta =  $parameters['pregunta'];
        $detalle =  $parameters['detalle'];
        $especialidad =  $parameters['especialidad'];
        $email =  $parameters['email'];
        
        $abgPregunta = new AbgPregunta();
        //$ctlSubespecialidad = new \DGAbgSistemaBundle\Entity\CtlSubespecialidad();
        $abgPregunta->setPregunta($pregunta);
        $abgPregunta->setDetalle($detalle);
        $abgPregunta->setEstado("1");
        $abgPregunta->setCorreoelectronico($email);
        $abgPregunta->setFechaPregunta($fechapregunta);
//      $abgPregunta->setCtlUsuario($username);
        
        
        $em = $this->getDoctrine()->getManager();
        $espeid = $em->getRepository('DGAbgSistemaBundle:CtlEspecialidad')->find($especialidad);
                
        //var_dump($subespeid);
        //die();
        
        $abgPregunta->setAbgEspecialidad($espeid);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($abgPregunta);        
        $em->flush();
        
        $lastpreg = $em->getRepository('DGAbgSistemaBundle:AbgPregunta')->findOneBy(array() ,array('id' => 'DESC'));
        $lastidpreg = $lastpreg->getId();
        
        //var_dump($lastidpreg);
        //die();        
              
        $sql = "SELECT * FROM abg_pregunta ,
                              ctl_usuario ,
                              abg_persona
                              WHERE abg_pregunta.ctl_usuario_id=ctl_usuario.id AND ctl_usuario.rh_persona_id=abg_persona.id AND abg_pregunta.abg_subespecialidad_id =1";
        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $coenv = $stmt->fetchAll();
        
        foreach ($coenv as $value) {
            //var_dump($value['correoelectronico']);
            $email = $value['correoelectronico'];
            //$email="elman.ortiz@gmail.com";
            $this->get('envio_correo')->sendEmail($email, "", "", "", "
                                        <table style=\"width: 540px; margin: 0 auto;\">
                                          <tr>
                                            <td class=\"panel\" style=\"border-radius:4px;border:1px #dceaf5 solid; color:#000 ; font-size:11pt;font-family:proxima_nova,'Open Sans','Lucida Grande','Segoe UI',Arial,Verdana,'Lucida Sans Unicode',Tahoma,'Sans Serif'; padding: 30px !important; background-color: #FFF;\">
                                            <center>
                                              <img style=\"width:50%;\" src=\"http://marvinvigil.info/ab/src/img/logogris.png\">
                                            </center>                                                
                                                <p>Hola ".$email." hay una nueva pregunta en la que puedes participar dando tu opinion</p>
                                                <p>Haz click en el enlace y se el primero en contestar</p>
                                                <a href='http://localhost/abgsistema/web/app_dev.php/admin/panelrespuestacentro/respuestapanel?id=".$lastidpreg."'>Clik aqui para responder</a> 
                                                
                                            </td>
                                            <td class=\"expander\"></td>
                                          </tr>
                                        </table>
                                    ");         
        }
                        
//        $this->get('envio_correo')->sendEmail($email, "", "", "", "
//                                        <table style=\"width: 540px; margin: 0 auto;\">
//                                          <tr>
//                                            <td class=\"panel\" style=\"border-radius:4px;border:1px #dceaf5 solid; color:#000 ; font-size:11pt;font-family:proxima_nova,'Open Sans','Lucida Grande','Segoe UI',Arial,Verdana,'Lucida Sans Unicode',Tahoma,'Sans Serif'; padding: 30px !important; background-color: #FFF;\">
//                                            <center>
//                                              <img style=\"width:50%;\" src=\"http://expressionsprint.com/img/logo.jpg\">
//                                            </center>
//                                                <p>Su orden
//                                                <b>#1</b> Hola ".$email." hay una nueva pregunta en la que puedes participar dando tu opinion
//                                                </p>
//                                            </td>
//                                            <td class=\"expander\"></td>
//                                          </tr>
//                                        </table>
//                                    ");
        return $this->render('enviopregsuccess/success.html.twig');
    }

}