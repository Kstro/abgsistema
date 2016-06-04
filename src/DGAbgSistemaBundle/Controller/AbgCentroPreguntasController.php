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
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * AbgEntrada controller.
 *
 * @Route("/preguntascentro")
 */
class AbgCentroPreguntasController extends Controller {

    /**
     * Lists all Preguntas entities.
     *
     * @Route("/", name="pregunta_index")
     * @Method("GET")
     */
    public function indexAction() {
        //$em = $this->getDoctrine()->getManager();
        //  $abgPersonas = $em->getRepository('DGAbgSistemaBundle:AbgPersona')->findAll();
        
        $em = $this->getDoctrine()->getManager();
        $preguntas = $em->getRepository('DGAbgSistemaBundle:AbgPregunta')->findBy(array('estado' => 1), array('id' => 'DESC'), 10, 0);
        $rsm = new ResultSetMapping();
        
        $sql = "select per.nombres as nombres, per.apellido as apellidos, uper.url as url, count(pre.respuesta) as totalrespuestas
                from abg_respuesta_pregunta pre inner join ctl_usuario usu on pre.ctl_usuario_id = usu.id
                inner join abg_persona per on usu.rh_persona_id = per.id
                inner join abg_url_personalizada uper on uper.abg_persona_id = per.id
                group by per.nombres, per.apellido, uper.url
                order by count(pre.respuesta) desc
                limit 0, 10";
        
        $rsm->addScalarResult('nombres','nombres');
        $rsm->addScalarResult('apellidos','apellidos');
        $rsm->addScalarResult('src','src');
        $rsm->addScalarResult('url','url');
        $rsm->addScalarResult('totalrespuestas','totalrespuestas');
        
        $topUsuarios = $em->createNativeQuery($sql, $rsm)
                                  ->getResult();
        
        $prom = $this->busquedaPublicidad(1);
        $prom2 = $this->busquedaPublicidad(2);
        $prom3 = $this->busquedaPublicidad(3);
        $prom4 = $this->busquedaPublicidad(4);
        
        return $this->render('centropreg/indexcentro.html.twig', array('prom1'=> $prom, 'prom2'=> $prom2, 'prom3'=> $prom3, 'prom4'=> $prom4, 'preguntas'=>$preguntas, 'top'=>$topUsuarios));
    }
        
    /**
     * Respuesta
     *
     * @Route("/respuestacentro", name="respuesta_centro")
     * @Method("GET")
     */
    public function respuestacentroAction(Request $request) {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $pregunta = $em->getRepository('DGAbgSistemaBundle:AbgPregunta')->find($id);
        
        if($pregunta->getRespuesta() != ''){
            $foto = $em->getRepository('DGAbgSistemaBundle:AbgFoto')->findOneBy(array("abgPersona" => $pregunta->getCtlUsuario()->getRhPersona()));
            
            $fechaRespuesta = $this->tiempo_transcurrido($pregunta->getFechaRespuesta());
            var_dump($fechaRespuesta);
        } else {
            $foto = null;
        }
            
        $rsm = new ResultSetMapping();
        
        $sql = "select per.nombres as nombres, per.apellido as apellidos, foto.src as src, uper.url as url, count(pre.respuesta) as totalrespuestas
                from abg_pregunta pre inner join ctl_usuario usu on pre.ctl_usuario_id = usu.id
                inner join abg_persona per on usu.rh_persona_id = per.id
                inner join abg_foto foto on foto.abg_persona_id = per.id
                inner join abg_url_personalizada uper on uper.abg_persona_id = per.id
                group by per.nombres, per.apellido, foto.src, uper.url
                order by count(pre.respuesta) desc
                limit 0, 10";
        
        $rsm->addScalarResult('nombres','nombres');
        $rsm->addScalarResult('apellidos','apellidos');
        $rsm->addScalarResult('src','src');
        $rsm->addScalarResult('url','url');
        $rsm->addScalarResult('totalrespuestas','totalrespuestas');
        
        $topUsuarios = $em->createNativeQuery($sql, $rsm)
                                  ->getResult();
        
        $prom = $this->busquedaPublicidad(1);
        $prom2 = $this->busquedaPublicidad(2);
        $prom3 = $this->busquedaPublicidad(3);
        $prom4 = $this->busquedaPublicidad(4);
        
        return $this->render('centropreg/respuestacentro.html.twig', array('foto'=> $foto, 'prom1'=> $prom, 'prom2'=> $prom2, 'prom3'=> $prom3, 'prom4'=> $prom4, 'pregunta'=>$pregunta, 'top'=>$topUsuarios));
    }
    
    /**
     * Respuesta
     *
     * @Route("/respuestas", name="respuesta_centro")
     * @Method("GET")
     */
    public function respuestasPersonaAction(Request $request) {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $pregunta = $em->getRepository('DGAbgSistemaBundle:AbgPregunta')->find($id);
        $respuestas = $em->getRepository('DGAbgSistemaBundle:AbgRespuestaPregunta')->findBy(array('abgPregunta' => $pregunta), array('id' => 'DESC'));
        
//        if($pregunta->getRespuesta() != ''){
//            $foto = $em->getRepository('DGAbgSistemaBundle:AbgFoto')->findOneBy(array("abgPersona" => $pregunta->getCtlUsuario()->getRhPersona()));
//        } else {
//            $foto = null;
//        }
        
        $fotos = array();
        $tiemposRespuesta = array();
        if($respuestas != NULL){
            foreach ($respuestas as $key => $value) {
                $foto = $em->getRepository('DGAbgSistemaBundle:AbgFoto')->findOneBy(array("abgPersona" => $value->getCtlUsuario()->getRhPersona()));
                
                array_push($fotos,$foto);
                array_push($tiemposRespuesta, $this->tiempo_transcurrido($value->getFechaRespuesta()->format('Y-m-d H:i:s')));
            }
        } else {
            $tiemposRespuesta = NULL;
        }
        //var_dump($fotos);
        $rsm = new ResultSetMapping();
        
        $sql = "select per.nombres as nombres, per.apellido as apellidos, uper.url as url, count(pre.respuesta) as totalrespuestas
                from abg_respuesta_pregunta pre inner join ctl_usuario usu on pre.ctl_usuario_id = usu.id
                inner join abg_persona per on usu.rh_persona_id = per.id
                inner join abg_url_personalizada uper on uper.abg_persona_id = per.id
                group by per.nombres, per.apellido, uper.url
                order by count(pre.respuesta) desc
                limit 0, 10";
        
        $rsm->addScalarResult('nombres','nombres');
        $rsm->addScalarResult('apellidos','apellidos');
        $rsm->addScalarResult('src','src');
        $rsm->addScalarResult('url','url');
        $rsm->addScalarResult('totalrespuestas','totalrespuestas');
        
        $topUsuarios = $em->createNativeQuery($sql, $rsm)
                                  ->getResult();
        
                          //var_dump($topUsuarios);
        
        $tiempo = $this->tiempo_transcurrido($pregunta->getFechaPregunta()->format('Y-m-d H:i:s'));
        
        $prom = $this->busquedaPublicidad(1);
        $prom2 = $this->busquedaPublicidad(2);
        $prom3 = $this->busquedaPublicidad(3);
        $prom4 = $this->busquedaPublicidad(4);
        
        return $this->render('centropreg/respuestacentro.html.twig', array('tiempo'=>$tiempo, 'tiemposRespuesta'=>$tiemposRespuesta, 'fotos'=> $fotos, 'prom1'=> $prom, 'prom2'=> $prom2, 'prom3'=> $prom3, 'prom4'=> $prom4, 'pregunta'=>$pregunta, 'respuestas' => $respuestas, 'top'=>$topUsuarios));
    }
         
    /**
     * 
     *
     * @Route("/busqueda/data/pregunta", name="busqueda_pregunta")
     */
    public function databusquedaAction(Request $request) {

        $inicio = $request->get('inicio');
        $longitud = $request->get('longitud');
        $paginaActual = $request->get('paginaActual');
        $busqueda = $request->get('busqueda');
         

        $inicioRegistro = ($longitud * ($paginaActual - 1));

        $response = new JsonResponse();

        $em = $this->getDoctrine()->getEntityManager();
        //$abogadosTotal = $em->getRepository('DGAbgSistemaBundle:AbgPersona')->findBy(array('estado' => 1));
        $preguntasTotal = $em->getRepository('DGAbgSistemaBundle:AbgPregunta')->findAll();
        
        $reg['inicio'] = $inicio++;
        $reg['longitud'] = $longitud;
        $reg['paginaActual'] = $paginaActual;
        $reg['inicioRegistro'] = $inicioRegistro;
        $reg['data'] = array();

        if ($busqueda != '') {
            $reg['numRegistros'] = 0;
           
            $sql = "SELECT * FROM abg_pregunta WHERE CONCAT(upper(pregunta),' ',upper(detalle)) LIKE '%" . strtoupper($busqueda) . "%' ORDER BY id DESC LIMIT " . $inicioRegistro . "," . $longitud;
            //echo $sql;            
            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $reg['data'] = $stmt->fetchAll();
            //var_dump($reg);
            //die();

            $sql = "SELECT COUNT(*) as total FROM abg_pregunta WHERE CONCAT(upper(pregunta),' ',detalle) LIKE '%" . strtoupper($busqueda) . "%' ORDER BY id DESC LIMIT 0,10";
            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $totales = $stmt->fetchAll();
            $reg['numRegistros'] = $totales[0]['total'];
            

            $reg['pages'] = floor(($reg['numRegistros'] / 10)) + 1;

            $reg['filtroRegistros'] = count($reg['data']);
            $esp = array();

            $i = 0;
                                               
        } else {
            $reg['numRegistros'] = 0;
            $reg['pages'] = 0;
            $reg['filtroRegistros'] = 0;
            $reg['data'] = array();
            $reg['pages'] = 0;
        }
        
        //var_dump($reg);
        //die();
        
        $response->setData($reg);
        return $response;
        //return new Response(json_encode($reg));
    }//Fin de la funcion databusqueda
    
//    ESTA PARTE ES PARA CUANDO EL SEARCH ESTA VACIO
    /**
     * 
     *
     * @Route("/busqueda/data/preguntavacia", name="busquedavacia_pregunta")
     */
    public function databusquedavaciaAction(Request $request) {

        $inicio = $request->get('inicio');
        $longitud = $request->get('longitud');
        $paginaActual = $request->get('paginaActual');
        $busqueda = $request->get('busqueda');


        $inicioRegistro = ($longitud * ($paginaActual - 1));

        $response = new JsonResponse();

        $em = $this->getDoctrine()->getEntityManager();
        //$abogadosTotal = $em->getRepository('DGAbgSistemaBundle:AbgPersona')->findBy(array('estado' => 1));
        $preguntasTotal = $em->getRepository('DGAbgSistemaBundle:AbgPregunta')->findAll();

        $reg['inicio'] = $inicio++;
        $reg['longitud'] = $longitud;
        $reg['paginaActual'] = $paginaActual;
        $reg['inicioRegistro'] = $inicioRegistro;
        $reg['data'] = array();

        if ($busqueda == '') {
            $reg['numRegistros'] = 0;

            $sql = "SELECT * FROM abg_pregunta ORDER BY id DESC LIMIT " . $inicioRegistro . "," . $longitud;
            //echo $sql;            
            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $reg['data'] = $stmt->fetchAll();
            //var_dump($reg);
            //die();

            $sql = "SELECT COUNT(*) as total FROM abg_pregunta ORDER BY id DESC LIMIT 0,10";
            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $totales = $stmt->fetchAll();
            $reg['numRegistros'] = $totales[0]['total'];


            $reg['pages'] = floor(($reg['numRegistros'] / 10)) + 1;

            $reg['filtroRegistros'] = count($reg['data']);
            $esp = array();

            $i = 0;
        } else {
            $reg['numRegistros'] = 0;
            $reg['pages'] = 0;
            $reg['filtroRegistros'] = 0;
            $reg['data'] = array();
            $reg['pages'] = 0;
        }

        //var_dump($reg);
        //die();

        $response->setData($reg);
        return $response;
        //return new Response(json_encode($reg));
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

//Fin de la funcion databusqueda
}//FIN DEL CONTROLADOR