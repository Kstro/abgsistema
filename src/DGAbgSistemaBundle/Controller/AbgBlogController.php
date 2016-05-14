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
        $dql = "SELECT en.id as identrada, en.tituloEntrada as titulo, en.fecha as fecha, en.contenido as contenido, im.src as src, ctlblog.nombreCategoria as catblognombre "
                . "FROM DGAbgSistemaBundle:AbgImagenBlog im "
                . "JOIN im.abgEntrada en "
                . "JOIN en.abgCategoriaEntradaId ctlblog "
                . "WHERE en.id =  :identrada";
        $parametros = $em->createQuery($dql)
                        ->setParameter('identrada', $identrada)
                        ->getSingleResult();
        
                //var_dump($parametros);
                //die();
        
        
        $em = $this->getDoctrine()->getManager();
        $ctlCategoriasBlog = $em->getRepository('DGAbgSistemaBundle:CtlCategoriaBlog')->findAll();
        //$reg['data'] = $em->createQuery($dql);        
//        var_dump($parametros);
//        die();
        return $this->render('DGAbgSistemaBundle:blog:blog.html.twig', array('detalleblog'=>$parametros, 'ctlCategoriasBlog'=>$ctlCategoriasBlog));     
    }
}