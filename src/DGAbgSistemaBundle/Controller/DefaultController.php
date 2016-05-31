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
//        $em = $this->getDoctrine()->getManager();
//        $especialidades = $em->getRepository('DGAbgSistemaBundle:CtlEspecialidad')->findAll();
        
        
        return $this->render('DGAbgSistemaBundle:Default:home.html.twig',array(
//            'especialidades' =>$especialidades
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
    
}
