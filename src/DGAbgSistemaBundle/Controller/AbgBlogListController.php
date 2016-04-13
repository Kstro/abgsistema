<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * BlogList controller.
 *
 * @Route("/admin/bloglist")
 */
class AbgBlogListController extends Controller{
    
    /**
     * Muestra un mensaje de entra ingresada correctamente.
     *
     * @Route("/", name="admin_bloglist", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return $this->render('DGAbgSistemaBundle:blog:bloglist.html.twig');     
    }
}