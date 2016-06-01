<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query\ResultSetMapping;

class DefaultController extends Controller
{
    //pagina home
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
//        $especialidades = $em->getRepository('DGAbgSistemaBundle:CtlEspecialidad')->findAll();
        $rsm4 = new ResultSetMapping();
       
        $sql4 = "select *
                from directorio
                where tipo = 1
                order by id desc 
                limit 0, 5";

        $rsm4->addScalarResult('nombres','nombres');
        $rsm4->addScalarResult('apellido','apellido');
        $rsm4->addScalarResult('foto','foto');
        $rsm4->addScalarResult('url','url');
        
        $usuarios = $em->createNativeQuery($sql4, $rsm4)
                                  ->getResult();
        //var_dump($usuarios);
        
        
        //$preguntas = $em->getRepository('DGAbgSistemaBundle:AbgPregunta')->findAll();
        
        
        return $this->render('DGAbgSistemaBundle:Default:home.html.twig',array(
            'usuarios' =>$usuarios
        ));
    }
    
    
    //Pagina landing
    /**
     * @Route("/landing/")
     */
    public function landingAction()
    {
//        $em = $this->getDoctrine()->getManager();
//        $especialidades = $em->getRepository('DGAbgSistemaBundle:CtlEspecialidad')->findAll();
        
        
        return $this->render('DGAbgSistemaBundle:Default:landing.html.twig',array(
//            'especialidades' =>$especialidades
        ));
    }
    
    
    /**
    * Ajax utilizado para buscar informacion de un abogado
    *  
    * @Route("/busqueda-ab-select/data", name="busqueda_ab_select")
    */
    public function busquedaAbAction(Request $request)
    {
        $busqueda = $request->query->get('q');
        $page = $request->query->get('page');
        
//        var_dump($busqueda);
        $rsm = new ResultSetMapping();
        $em = $this->getDoctrine()->getEntityManager();
        $sql = "SELECT * FROM directorio WHERE CONCAT(upper(nombres),' ',upper(apellido),' ',upper(sub),' ',upper(especialidad)) LIKE '%".strtoupper($busqueda)."%' ORDER BY nombres ASC";
//        var_dump($sql);
        $rsm->addScalarResult('id','id');
        $rsm->addScalarResult('nombres','nombres');
        $rsm->addScalarResult('apellido','apellido');
        $abogado['data'] = $em->createNativeQuery($sql, $rsm)
                                ->getResult();
                        
        
        
        return new Response(json_encode($abogado));
    }
    
    
    
    
    
    /**
    * Ajax utilizado para buscar informacion de un abogado
    *  
    * @Route("/busqueda-ab-input/data", name="busqueda_ab_input", options={"expose"=true}))
    */
    public function busquedaAbInputAction(Request $request)
    {
        $busqueda = $request->query->get('query');
        $page = $request->query->get('page');
        
        //var_dump($busqueda);
        $rsm = new ResultSetMapping();
        $em = $this->getDoctrine()->getEntityManager();
        $sql = "SELECT concat(nombres,' ',apellido) as value FROM directorio WHERE CONCAT(upper(nombres),' ',upper(apellido),' ',upper(sub),' ',upper(especialidad)) LIKE '%".strtoupper($busqueda)."%' ORDER BY value ASC";
        //var_dump($sql);
        
        //$rsm->addScalarResult('suggestions','suggestions');
        $rsm->addScalarResult('id','id');
        $rsm->addScalarResult('value','value');
        $rsm->addScalarResult('data','data');
        $abogado['suggestions'] = $em->createNativeQuery($sql, $rsm)
                                ->getResult();
                        
                        //var_dump($abogado['data']);
        
        return new Response(json_encode($abogado));
    }
    
    /**
    * Ajax utilizado para buscar informacion de un abogado
    *  
    * @Route("/busqueda-ciudad-input/data", name="busqueda_ciudad_input", options={"expose"=true}))
    */
    public function busquedaCiudadInputAction(Request $request)
    {
        $busqueda = $request->query->get('query');
        $page = $request->query->get('page');
        
        //var_dump($busqueda);
        $rsm = new ResultSetMapping();
        $em = $this->getDoctrine()->getEntityManager();
        $sql = "SELECT concat(est.nombre_estado,', ', ciu.nombre_ciudad) as value,nombre_estado as estado,nombre_ciudad as ciudad "
                . "FROM ctl_ciudad ciu join ctl_estado est on (ciu.ctl_estado_id=est.id)"
                . " WHERE concat(est.nombre_estado,', ', ciu.nombre_ciudad) LIKE '%".$busqueda."%'";
        //var_dump($sql);
        
        //$rsm->addScalarResult('suggestions','suggestions');
        $rsm->addScalarResult('value','value');
        $rsm->addScalarResult('nombre','nombre');
        $rsm->addScalarResult('ciudad','ciudad');
        $abogado['suggestions'] = $em->createNativeQuery($sql, $rsm)
                                ->getResult();
                        
                        //var_dump($abogado['data']);
        
        return new Response(json_encode($abogado));
    }
    
}
