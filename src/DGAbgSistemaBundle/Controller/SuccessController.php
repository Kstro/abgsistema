<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Success controller.
 *
 * @Route("/admin/succcess")
 */
class SuccessController extends Controller{
    
    /**
     * Muestra un mensaje de entra ingresada correctamente.
     *
     * @Route("/", name="admin_success", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return $this->render('DGAbgSistemaBundle:success:success.html.twig');     
    }
}