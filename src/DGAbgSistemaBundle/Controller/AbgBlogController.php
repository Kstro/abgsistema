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
        return $this->render('DGAbgSistemaBundle:blog:blog.html.twig');     
    }
}