<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGAbgSistemaBundle\Entity\AbgPregunta;
use Doctrine\ORM\Query\ResultSetMapping;
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
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function preguntaDetalleAction(Request $request) {
               
        $parameters = $request->request->all();
        //$pregunta =  $parameters['pregunta'];
        $pregunta = $request->get('txtpregunta');
        $prom = $this->busquedaPublicidad(1);
        $prom2 = $this->busquedaPublicidad(2);
        $prom3 = $this->busquedaPublicidad(3);
        $prom4 = $this->busquedaPublicidad(4);
        
        $rsm = new ResultSetMapping();
        
        $sql = "select per.nombres as nombres, per.apellido as apellidos, foto.src as src, uper.url as url, count(pre.respuesta) as totalrespuestas
                from abg_pregunta pre inner join ctl_usuario usu on pre.ctl_usuario_id = usu.id
                inner join abg_persona per on usu.rh_persona_id = per.id
                inner join abg_foto foto on foto.abg_persona_id = per.id
                inner join abg_url_personalizada uper on uper.abg_persona_id = per.id and uper.estado=1
                group by per.nombres, per.apellido, foto.src, uper.url
                order by count(pre.respuesta) desc
                limit 0, 10";
        
        $rsm->addScalarResult('nombres','nombres');
        $rsm->addScalarResult('apellidos','apellidos');
        $rsm->addScalarResult('src','src');
        $rsm->addScalarResult('url','url');
        $rsm->addScalarResult('totalrespuestas','totalrespuestas');
        
        $em = $this->getDoctrine()->getManager();
        $topUsuarios = $em->createNativeQuery($sql, $rsm)
                                  ->getResult();
        
        $ctlespecialidad = $em->getRepository('DGAbgSistemaBundle:CtlEspecialidad')->findAll();
        return $this->render('preyres/pregunta_detalle.html.twig', 
                array('pregunta'=>$pregunta,
                    'prom1'=> $prom,
                    'prom2'=> $prom2, 
                    'prom3'=> $prom3, 
                    'prom4'=> $prom4, 
                    'ctlEspecialidad'=>$ctlespecialidad, 
                    'top'=>$topUsuarios));
    }
    
    /**
     * Muestra la interfaz para hacer una nueva pregunta.
     *
     * @Route("/enviopregunta", name="envio_pregunta", options={"expose"=true})
     * @Method("POST")
     * @Template()
     */
    public function envioPreguntaAction(Request $request) {
        
        try {
           $em = $this->getDoctrine()->getManager();
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
        $abgPregunta->setContador(0);
        $abgPregunta->setCorreoelectronico($email);
        $abgPregunta->setFechaPregunta(new \DateTime ('now'));

        $em = $this->getDoctrine()->getManager();
        $espeid = $em->getRepository('DGAbgSistemaBundle:CtlEspecialidad')->find($especialidad);
        $abgPregunta->setAbgEspecialidad($espeid);

          $em->persist($abgPregunta);
        $em->flush();
        $lastpreg = $em->getRepository('DGAbgSistemaBundle:AbgPregunta')->findOneBy(array() ,array('id' => 'DESC'));
        $lastidpreg = $lastpreg->getId();
      
        $sql = "SELECT abg_persona.correoelectronico FROM 
                       ctl_usuario
                JOIN    abg_persona
                       on  ctl_usuario.rh_persona_id=abg_persona.id
                JOIN ctl_rol_usuario
                ON ctl_usuario.id=ctl_rol_usuario.ctl_usuario_id 
                          AND ctl_rol_usuario.ctl_rol_id=2
                JOIN abg_persona_especialidad 
                ON abg_persona_especialidad.abg_persona_id=abg_persona.id 
                AND abg_persona_especialidad.ctl_especialidad_id=".$especialidad;
        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $coenv = $stmt->fetchAll();
       
        foreach ($coenv as $value) {
            $email = $value['correoelectronico'];
            $this->get('envio_correo')->sendEmail($email, "", "", "", "
                                        <table style=\"width: 540px; margin: 0 auto;\">
                                          <tr>
                                            <td class=\"panel\" style=\"border-radius:4px;border:1px #dceaf5 solid; color:#000 ; font-size:11pt;font-family:proxima_nova,'Open Sans','Lucida Grande','Segoe UI',Arial,Verdana,'Lucida Sans Unicode',Tahoma,'Sans Serif'; padding: 30px !important; background-color: #FFF;\">
                                            <center>
                                              <img style=\"width:50%;\" src=\"http://marvinvigil.info/ab/src/img/logogris.png\">
                                            </center>                                                
                                                <p>Hola ".$email." hay una nueva pregunta en la que puedes participar dando tu opinion</p>
                                                <p>Haz click en el enlace y se el primero en contestar</p>
                                                <a href='http://abg.localhost/app_dev.php/admin/panelrespuestacentro/respuesta_abg?id=".$lastidpreg."'>Clik aqui para responder</a> 

                                            </td>
                                            <td class=\"expander\"></td>
                                          </tr>
                                        </table>
                                    ");         
        }

        return $this->render('enviopregsuccess/success.html.twig');
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }
    
    private function busquedaPublicidad($posicion) {
       $em = $this->getDoctrine()->getManager();

        $i = 0;
        $recuperados = array();
        $prom = array();

        $dql = "Select fot.idargFoto, fot.src From DGAbgSistemaBundle:AbgFoto fot Join fot.promocion pro"
                . " WHERE pro.posicion = :posicion  and pro.estado = 1 and fot.fechaExpiracion > :fecha"
                . " ORDER BY fot.idargFoto DESC ";

        $fecha = new \DateTime ('now');
        
        $promotions = $em->createQuery($dql)
                          ->setParameter('posicion',$posicion)
                          ->setParameter('fecha', $fecha)
                          ->getResult();  
        
        if(!empty($promotions)){
            $max = count($promotions);

            if($max > 20){
                while ($i < 20){
                    $random = rand(1, ($max - 1));

                    if (!in_array($random, $recuperados)) {
                        $recuperados[$i] = $random;
                        $prom[$i]['idargFoto'] = $promotions[$random]['idargFoto'];
                        $prom[$i]['src'] = $promotions[$random]['src'];
                        $i++;
                    }    
                }
            } else {
                foreach ($promotions as $key => $value) {
                    $prom[$key]['idargFoto'] = $value['idargFoto'];
                    $prom[$key]['src'] = $value['src'];
                }
            }
        } else {
            $prom = NULL;
        }
        
        return $prom; 
    }

}
