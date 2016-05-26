<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Blog controller.
 *
 * @Route("/admin/blog")
 */
class AbgBlogController extends Controller{
    
    /**
     * Presenta el detalle de un blog especifico.
     *
     * @Route("/", name="admin_blog", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $request = $this->getRequest();
        $identrada = $request->get('id');
        
        $em = $this->getDoctrine()->getManager();            
        $dql = "SELECT en.id as identrada, en.tituloEntrada as titulo, en.fecha as fecha, en.contenido as contenido, im.src as src, ctlblog.nombreCategoria as catblognombre, per.nombres as nombres, per.apellido as apellidos "
                . "FROM DGAbgSistemaBundle:AbgImagenBlog im "
                . "JOIN im.abgEntrada en "
                . "JOIN en.abgCategoriaEntradaId ctlblog "
                . "JOIN en.ctlUsuario us "
                . "JOIN us.rhPersona per "
                . "WHERE en.id =  :identrada";
        $parametros = $em->createQuery($dql)
                        ->setParameter('identrada', $identrada)
                        ->getSingleResult();
        
                //var_dump($parametros);
                //die();
        
        
        $em = $this->getDoctrine()->getManager();
        $ctlCategoriasBlog = $em->getRepository('DGAbgSistemaBundle:CtlCategoriaBlog')->findAll();
        
        $prom2 = $this->busquedaPublicidad(2);
        $prom3 = $this->busquedaPublicidad(3);
        //$reg['data'] = $em->createQuery($dql);        
//        var_dump($parametros);
//        die();
        return $this->render('DGAbgSistemaBundle:blog:blog.html.twig', array('prom2'=> $prom2, 'prom3'=> $prom3,'detalleblog'=>$parametros, 'ctlCategoriasBlog'=>$ctlCategoriasBlog));     
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