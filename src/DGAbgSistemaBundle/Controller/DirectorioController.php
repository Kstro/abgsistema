<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DGAbgSistemaBundle\Entity\CtlCiudad;
use DGAbgSistemaBundle\Entity\Persona;
use DGAbgSistemaBundle\Form\CtlCiudadType;

/**
 * CtlCiudad controller.
 *
 * @Route("/directorio")
 */
class DirectorioController extends Controller
{
    /**
     * Lists all CtlCiudad entities.
     *
     * @Route("/", name="directorio_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ctlCiudads = $em->getRepository('DGAbgSistemaBundle:CtlCiudad')->findAll();

        return $this->render('directorio/directorio.html.twig', array(
            'ctlCiudads' => $ctlCiudads,
        ));
    }

    
    
    
    /**
     * 
     *
     * @Route("/busqueda/data/registros", name="busqueda_data")
     */
    public function databusquedaAction(Request $request)
    {
        
        $inicio = $request->get('inicio');
        $longitud = $request->get('longitud');
        $paginaActual = $request->get('paginaActual');
        $busqueda = $request->get('busqueda');
        
        $response = new JsonResponse();
//        $start = $request->query->get('start');
//        $draw = $request->query->get('draw');
//        $longitud = $request->query->get('length');
//        $busqueda = $request->query->get('search');
        
        
        $em = $this->getDoctrine()->getEntityManager();
        $expedientesTotal = $em->getRepository('DGAbgSistemaBundle:AbgPersona')->findAll();
        
        $reg['inicio']=$inicio++;  
        $reg['longitud'] = $longitud;
        $reg['paginaActual']= $paginaActual;
        $reg['data']= array();
        
        //var_dump($data);
        
        
//        '<div class=\"item-directorio-nexo\" style=\"width: 100%\">
//                                                    <div class=\"clearfix\"></div>
//                                                    <div class=\"row\">
//                                                        <div class=\"col-xs-2 col-sm-3 col-md-3 col-lg-2\">
//                                                            <img src=\"{{asset(\"Resources/src/img/profile.png\")}}\" style=\"max-width: 100%; width: 100%; margin-left: 10px; margin-top: 15px; margin-bottom: 15px; margin-right: 10px;\">
//                                                        </div>
//                                                        <div class=\" col-xs-10 col-sm-9 col-md-9 col-lg-10\">
//                                                            <p style=\"margin-top: 10px; font-size: 1.3em; margin-bottom: 0px;\" class=\"sans\"><strong>Marvin Jose Vigil</strong></p>
//                                                            <p style=\"color: #777777; margin-top: -3px; margin-bottom: 0px; font-size: 12px;\" class=\"sans\">Director Ejecutivo en Digitality Garage</p>
//                                                            <div class=\"row\" style=\"margin-top: 2px; margin-bottom: 15px;\">
//                                                                <div class=\"col-sm-4 col-xs-12\">
//                                                                    <p style=\"color: #4444444; font-size: 11px; margin-bottom: 0px;\"><strong>Telefono</strong></p>
//                                                                    <p style=\"font-size: 11px; margin-bottom: 0px;\">+503 77971933</p>
//                                                                </div>
//                                                                <div class=\"col-sm-4 col-xs-12\">
//                                                                    <p style=\"color: #4444444; font-size: 11px; margin-bottom: 0px;\"><strong>Email</strong></p>
//                                                                    <p style=\"font-size: 11px; margin-bottom: 0px;\">marvinvigil@gmail.com</p>
//                                                                </div>
//                                                                <div class=\"col-sm-4 col-xs-12\">
//                                                                    <p style=\"color: #4444444; font-size: 11px; margin-bottom: 0px;\"><strong>Sitio Web</strong></p>
//                                                                    <p style=\"font-size: 11px; margin-bottom: 0px;\"><a href=\"http://www.ariaslaw.com\" target=\"_blank\">www.ariaslaw.com</a></p>
//                                                                </div>
//                                                                <div class=\"col-sm-12\">
//                                                                    <span style=\"color: #4444444; font-size: 11px; margin-bottom: 0px; padding-right: 10px;\"><strong>Especialidades:</strong> </span>
//                                                                    <span style=\"font-size: 11px;\">Derecho Civil, Derecho Comercial, Derecho Constitucional, Derecho Penal</span>
//                                                                </div>
//                                                                <div class=\"col-sm-12\" style=\"margin-top: -0px;\">
//                                                                    <span style=\"font-size: 11px;\"><a href=\"#\">Ver perfil</a></span>
//                                                                    <span style=\"color: #777777; margin-left: 5px; margin-right: 5px;\">|</span>
//                                                                    <span style=\"font-size: 11px;\"><a href=\"#\">Contactar</a></span>
//                                                                    <span style=\"color: #777777; margin-left: 5px; margin-right: 5px;\">|</span>
//                                                                    <span style=\"font-size: 11px;\"><a href=\"#\">Recomendar</a></span>							
//                                                                </div>
//                                                            </div>
//                                                        </div>
//                                                    </div>
//                                                </div>'
                
//        $dql = "SELECT "
//                . "CONCAT('<div class=\"item-directorio-nexo\" style=\"width: 100%\">
//                                <div class=\"clearfix\"></div>
//                                <div class=\"row\">
//                                    <div class=\"col-xs-2 col-sm-3 col-md-3 col-lg-2\">'
//                                    ,'<img src=\"\" style=\"max-width: 100%; width: 100%; margin-left: 10px; margin-top: 15px; margin-bottom: 15px; margin-right: 10px;\"></div>',
//                                    '<div class=\" col-xs-10 col-sm-9 col-md-9 col-lg-10\">',
//                                        '<p style=\"margin-top: 10px; font-size: 1.3em; margin-bottom: 0px;\" class=\"sans\"><strong>',per.nombres,'</strong></p>',
//                                        '<p style=\"color: #777777; margin-top: -3px; margin-bottom: 0px; font-size: 12px;\" class=\"sans\">Director Ejecutivo en Digitality Garage</p>',
//                                        '<div class=\"row\" style=\"margin-top: 2px; margin-bottom: 15px;\">',
//                                        '<div class=\"col-sm-4 col-xs-12\">',
//                                        '<p style=\"color: #4444444; font-size: 11px; margin-bottom: 0px;\"><strong>Telefono</strong></p>',
//                                        '<p style=\"font-size: 11px; margin-bottom: 0px;\">',IFNULL(per.telefonoFijo,' '),IFNULL(per.telefonoMovil,''),'</p>',
//                                        '</div>',
//                                        '<div class=\"col-sm-4 col-xs-12\">',
//                                        '<p style=\"color: #4444444; font-size: 11px; margin-bottom: 0px;\"><strong>Email</strong></p>',
//                                        '<p style=\"font-size: 11px; margin-bottom: 0px;\">',IFNULL(per.correoelectronico,''),'</p>',
//                                        '</div>',
//                                        '<div class=\"col-sm-4 col-xs-12\">',
//                                        '<p style=\"color: #4444444; font-size: 11px; margin-bottom: 0px;\"><strong>Sitio Web</strong></p>',
//                                        '<p style=\"font-size: 11px; margin-bottom: 0px;\"><a href=\"http://www.ariaslaw.com\" target=\"_blank\">www.ariaslaw.com</a></p></div>',
//                                        '<div class=\"col-sm-12\">',
//                                        '<span style=\"color: #4444444; font-size: 11px; margin-bottom: 0px; padding-right: 10px;\"><strong>Especialidades:</strong> </span>',
//                                        '<span style=\"font-size: 11px;\">Derecho Civil, Derecho Comercial, Derecho Constitucional, Derecho Penal</span></div>',
//                                        '<div class=\"col-sm-12\" style=\"margin-top: -0px;\">',
//                                        '<span style=\"font-size: 11px;\"><a href=\"#\">Ver perfil</a></span>',
//                                        '<span style=\"color: #777777; margin-left: 5px; margin-right: 5px;\">|</span><span style=\"font-size: 11px;\"><a href=\"#\">Contactar</a></span>',
//                                        '<span style=\"color: #777777; margin-left: 5px; margin-right: 5px;\">|</span><span style=\"font-size: 11px;\"><a href=\"#\">Recomendar</a></span>',
//                                        '</div></div></div></div></div>') "
//                . "as datos FROM DGAbgSistemaBundle:AbgPersona per";
        
        $dql = "SELECT per.id,per.nombres, per.apellido, per.correoelectronico, per.telefonoFijo, per.telefonoMovil FROM DGAbgSistemaBundle:AbgPersona per";
        $em = $this->getDoctrine()->getManager();
        $reg['data'] = $em->createQuery($dql)
//                   ->setParameter('numero','%'.$numeroExp.'%')
                   ->getResult();
        
        $esp = array();
        
        $i=0;
        foreach($reg['data'] as $i =>$row){
            $reg['data'][$i]['especialidades']=array();
            //var_dump($reg['data']);
            $dql = "SELECT esp.nombreEspecialidad FROM DGAbgSistemaBundle:AbgPersonaSubespecialidad subper "
                . "JOIN subper.abgSubespecialidad sub "
                . "JOIN sub.abgEspecialidad esp "
                . "JOIN subper.abgPersona per WHERE per.id=:idPersona";
            $em = $this->getDoctrine()->getManager();
            $especialidades = $em->createQuery($dql)
                   ->setParameter('idPersona',$row['id'])
                   ->getResult();
            //var_dump($especialidades);
            
            
            
            
            foreach($especialidades as $row2){
                array_push($esp,$row2);
            }
            
            //var_dump($esp);
            //die();

            
            if(count($especialidades)==0){
                array_push($row['especidalidades'], 'N/A');
            }
            else{
                //array_push($reg['data'][$i]['especialidades'], $esp);
                $reg['data'][$i]['especialidades']=$esp;
            }
            
            
            
            $dql = "SELECT foto.src FROM DGAbgSistemaBundle:AbgFoto foto "
                    . "JOIN foto.abgPersona per "
                    . "WHERE per.id=:idPersona AND foto.tipoFoto=1";
            $em = $this->getDoctrine()->getManager();
            $especialidades = $em->createQuery($dql)
                   ->setParameter('idPersona',$row['id'])
                   ->getResult();
            
            
            
            $i++;
        }
        //var_dump($reg['data'][0]['especialidades'][0]['nombreEspecialidad']);
        //var_dump($reg['data'][0]['especialidades'][1]['nombreEspecialidad']);
        //die();
        
        
        
        
        //var_dump($reg['data']);
        //die();
        $response->setData($reg);    
        return $response; 
        //return new Response(json_encode($reg));
    }
    
    
    /**
     * Creates a new CtlCiudad entity.
     *
     * @Route("/new", name="ctlciudad_new")
     * @Method({"GET", "POST"})
     */
//    public function newAction(Request $request)
//    {
//        $ctlCiudad = new CtlCiudad();
//        $form = $this->createForm('DGAbgSistemaBundle\Form\CtlCiudadType', $ctlCiudad);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($ctlCiudad);
//            $em->flush();
//
//            return $this->redirectToRoute('ctlciudad_show', array('id' => $ctlCiudad->getId()));
//        }
//
//        return $this->render('ctlciudad/new.html.twig', array(
//            'ctlCiudad' => $ctlCiudad,
//            'form' => $form->createView(),
//        ));
//    }

    /**
     * Finds and displays a CtlCiudad entity.
     *
     * @Route("/{id}", name="ctlciudad_show")
     * @Method("GET")
     */
//    public function showAction(CtlCiudad $ctlCiudad)
//    {
//        $deleteForm = $this->createDeleteForm($ctlCiudad);
//
//        return $this->render('ctlciudad/show.html.twig', array(
//            'ctlCiudad' => $ctlCiudad,
//            'delete_form' => $deleteForm->createView(),
//        ));
//    }

    /**
     * Displays a form to edit an existing CtlCiudad entity.
     *
     * @Route("/{id}/edit", name="ctlciudad_edit")
     * @Method({"GET", "POST"})
     */
//    public function editAction(Request $request, CtlCiudad $ctlCiudad)
//    {
//        $deleteForm = $this->createDeleteForm($ctlCiudad);
//        $editForm = $this->createForm('DGAbgSistemaBundle\Form\CtlCiudadType', $ctlCiudad);
//        $editForm->handleRequest($request);
//
//        if ($editForm->isSubmitted() && $editForm->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($ctlCiudad);
//            $em->flush();
//
//            return $this->redirectToRoute('ctlciudad_edit', array('id' => $ctlCiudad->getId()));
//        }
//
//        return $this->render('ctlciudad/edit.html.twig', array(
//            'ctlCiudad' => $ctlCiudad,
//            'edit_form' => $editForm->createView(),
//            'delete_form' => $deleteForm->createView(),
//        ));
//    }

    /**
     * Deletes a CtlCiudad entity.
     *
     * @Route("/{id}", name="ctlciudad_delete")
     * @Method("DELETE")
     */
//    public function deleteAction(Request $request, CtlCiudad $ctlCiudad)
//    {
//        $form = $this->createDeleteForm($ctlCiudad);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->remove($ctlCiudad);
//            $em->flush();
//        }
//
//        return $this->redirectToRoute('ctlciudad_index');
//    }

    /**
     * Creates a form to delete a CtlCiudad entity.
     *
     * @param CtlCiudad $ctlCiudad The CtlCiudad entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
//    private function createDeleteForm(CtlCiudad $ctlCiudad)
//    {
//        return $this->createFormBuilder()
//            ->setAction($this->generateUrl('ctlciudad_delete', array('id' => $ctlCiudad->getId())))
//            ->setMethod('DELETE')
//            ->getForm()
//        ;
//    }
}
