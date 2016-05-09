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
                
        return $this->render('centropreg/indexcentro.html.twig', array('preguntas'=>$preguntas));
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
        //var_dump($pregunta);
        //die();
        return $this->render('centropreg/respuestacentro.html.twig', array('pregunta'=>$pregunta));
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

//Fin de la funcion databusqueda
}//FIN DEL CONTROLADOR