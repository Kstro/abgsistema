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
 * @Route("/blog")
 */
class AbgBlogController extends Controller{
    
    /**
     * Presenta el detalle de un blog especifico.
     *
     * @Route("/", name="blog", options={"expose"=true})
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
        
                //var_dump($parametros['fecha']);
                //var_dump($parametros);
                //die();
        
        
        $em = $this->getDoctrine()->getManager();
        $ctlCategoriasBlog = $em->getRepository('DGAbgSistemaBundle:CtlCategoriaBlog')->findAll();
                $prom = $this->busquedaPublicidad(1);
        $prom2 = $this->busquedaPublicidad(2);
        $prom3 = $this->busquedaPublicidad(3);
        //$reg['data'] = $em->createQuery($dql);        
//        var_dump($parametros);
//        die();
        return $this->render('DGAbgSistemaBundle:blog:blog.html.twig', 
                array('prom1'=> $prom,'prom2'=> $prom2, 'prom3'=> $prom3,'detalleblog'=>$parametros, 'ctlCategoriasBlog'=>$ctlCategoriasBlog));     
    }
    
    /**
     * Presenta el detalle de un blog especifico.
     *
     * @Route("/mostrar/", name="admin_showBlog", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function showBlogAction()
    {
        $request = $this->getRequest();
        $identrada = $request->get('id');
        
        $em = $this->getDoctrine()->getManager();            
        
        $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();
        
        $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil, p.estado As estado, p.tituloProfesional AS tprofesional, p.verificado As verificado "
                . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
        $result_persona = $em->createQuery($dql_persona)->getArrayResult();

        $dqlfoto = "SELECT fot.src as src "
                    . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
        $result_foto = $em->createQuery($dqlfoto)->getArrayResult();
        
        $username = $this->container->get('security.context')->getToken()->getUser();
        
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
        
        $ctlCategoriasBlog = $em->getRepository('DGAbgSistemaBundle:CtlCategoriaBlog')->findAll();

         return $this->render('blog/show.html.twig', array(
                    'abgPersona' => $result_persona,
                    'abgFoto' => $result_foto,
                    'usuario' => $username,
                    'detalleblog'=>$parametros, 
                    'ctlCategoriasBlog'=>$ctlCategoriasBlog
                 ));
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