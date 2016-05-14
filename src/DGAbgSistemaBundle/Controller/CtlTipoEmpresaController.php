<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DGAbgSistemaBundle\Entity\CtlTipoEmpresa;
use DGAbgSistemaBundle\Form\CtlTipoEmpresaType;
use Symfony\Component\HttpFoundation\Response;

/**
 * CtlTipoEmpresa controller.
 *
 * @Route("/admin/tipoempresa")
 */
class CtlTipoEmpresaController extends Controller
{
    /**
     * Lists all CtlTipoEmpresa entities.
     *
     * @Route("/", name="admin_tipoempresa_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ctlTipoEmpresas = $em->getRepository('DGAbgSistemaBundle:CtlTipoEmpresa')->findAll();

        return $this->render('ctltipoempresa/index.html.twig', array(
            'ctlTipoEmpresas' => $ctlTipoEmpresas,
        ));
    }

    /**
     * Creates a new CtlTipoEmpresa entity.
     *
     * @Route("/new", name="admin_tipoempresa_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $ctlTipoEmpresa = new CtlTipoEmpresa();
        $form = $this->createForm('DGAbgSistemaBundle\Form\CtlTipoEmpresaType', $ctlTipoEmpresa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlTipoEmpresa);
            $em->flush();

            return $this->redirectToRoute('admin_tipoempresa_show', array('id' => $ctlTipoEmpresa->getId()));
        }

        return $this->render('DGAbgsistemaBundle:tipoempresa/new.html.twig', array(
            'ctlTipoEmpresa' => $ctlTipoEmpresa,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CtlTipoEmpresa entity.
     *
     * @Route("/{id}", name="admin_tipoempresa_show")
     * @Method("GET")
     */
    public function showAction(CtlTipoEmpresa $ctlTipoEmpresa)
    {
        $deleteForm = $this->createDeleteForm($ctlTipoEmpresa);

        return $this->render('ctltipoempresa/show.html.twig', array(
            'ctlTipoEmpresa' => $ctlTipoEmpresa,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CtlTipoEmpresa entity.
     *
     * @Route("/{id}/edit", name="admin_tipoempresa_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CtlTipoEmpresa $ctlTipoEmpresa)
    {
        $deleteForm = $this->createDeleteForm($ctlTipoEmpresa);
        $editForm = $this->createForm('DGAbgSistemaBundle\Form\CtlTipoEmpresaType', $ctlTipoEmpresa);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlTipoEmpresa);
            $em->flush();

            return $this->redirectToRoute('admin_tipoempresa_edit', array('id' => $ctlTipoEmpresa->getId()));
        }

        return $this->render('ctltipoempresa/edit.html.twig', array(
            'ctlTipoEmpresa' => $ctlTipoEmpresa,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CtlTipoEmpresa entity.
     *
     * @Route("/{id}", name="admin_tipoempresa_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CtlTipoEmpresa $ctlTipoEmpresa)
    {
        $form = $this->createDeleteForm($ctlTipoEmpresa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ctlTipoEmpresa);
            $em->flush();
        }

        return $this->redirectToRoute('admin_tipoempresa_index');
    }

    /**
     * Creates a form to delete a CtlTipoEmpresa entity.
     *
     * @param CtlTipoEmpresa $ctlTipoEmpresa The CtlTipoEmpresa entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CtlTipoEmpresa $ctlTipoEmpresa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_tipoempresa_delete', array('id' => $ctlTipoEmpresa->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * @Route("/registar_persona/get", name="registrar_tipoempresa", options={"expose"=true})
     * @Method("GET")
     */
    public function RegistrarTipoEmpresaAction() {
        $em = $this->getDoctrine()->getManager();
        try {
            //   $em->getConnection()->beginTransaction();
            $request = $this->getRequest();

            parse_str($request->get('dato'), $datos);
          
            
            $tipoEmpresa = new CtlTipoEmpresa();
            $tipoEmpresa->setTipoEmpresa($datos['txtnombreempresa']);

            $em->persist($tipoEmpresa);
            $em->flush();


                $data['msj'] = "Registrado";
           
           
            return new Response(json_encode($data));
        } catch (\Exception $e) {
           
            $data['msj'] = $e->getMessage();
          

            return new Response(json_encode($data));
        }
    }
    
    
  /**
     * 
     *
     * @Route("/tipoempresa/data", name="tipo_empresa_data")
     */
    public function dataterritorioAction(Request $request)
    {
        
        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
        $entity = new CtlTipoEmpresa();
     
     
        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');
        
        $em = $this->getDoctrine()->getEntityManager();
        $territoriosTotal = $em->getRepository('DGAbgSistemaBundle:CtlTipoEmpresa')->findAll();
        
        $territorio['draw']=$draw++;  
        $territorio['recordsTotal'] = count($territoriosTotal);
        $territorio['recordsFiltered']= count($territoriosTotal);
        
        $territorio['data']= array();
        //var_dump($busqueda);
        //die();
        $arrayFiltro = explode(' ',$busqueda['value']);
        
        //echo count($arrayFiltro);
        $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
        if($busqueda['value']!=''){
            //foreach ($arrayFiltro as $row){
                //var_dump($row);
              //  if($row!=''){
                    
                    $dql = "SELECT tie.id, tie.tipoEmpresa ,concat(concat('<input type=\"checkbox\" class=\"checkbox idtipoempresa\" id=\"',tie.id), '\">' as link FROM DGAbgSistemaBundle:CtlTipoEmpresa tie "
                        . "WHERE upper(tie.tipoEmpresa) LIKE upper(:busqueda) "
                        . "ORDER BY tie.tipoEmpresa DESC ";
                   $territorio['data'] = $em->createQuery($dql)
                            ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                            ->getResult();
                    
                   $territorio['recordsFiltered']= count($territorio['data']);
                    
                   $dql = "SELECT tie.id, tie.tipoEmpresa ,concat(concat('<input type=\"checkbox\" class=\"checkbox idtipoempresa\" id=\"',tie.id), '\">' as link FROM DGAbgSistemaBundle:CtlTipoEmpresa tie "
                        . "WHERE upper(tie.tipoEmpresa) LIKE upper(:busqueda) "
                        . "ORDER BY tie.tipoEmpresa DESC ";
                   
                   $territorio['data'] = $em->createQuery($dql)
                            ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                            ->setFirstResult($start)
                            ->setMaxResults($longitud)
                            ->getResult();
       
        }
        else{
            $dql = "SELECT tie.id , tie.tipoEmpresa  ,concat(concat('<input type=\"checkbox\" class=\"checkbox idtipoempresa\" id=\"',tie.id), '\">' as link FROM DGAbgSistemaBundle:CtlTipoEmpresa tie  "
                . " ORDER BY tie.tipoEmpresa DESC ";
            $territorio['data'] = $em->createQuery($dql)
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();
        }
     
        
        return new Response(json_encode($territorio));
    }
      
    
     /**
     * Displays a form to edit an existing Orden entity.
     *
     * @Route("/admin/eliminar/tipoempresa", name="delete_tipoempresa")
     */
    public function deleteTipoEmpresaAction()
    {
         
         $isAjax = $this->get('Request')->isXMLhttpRequest();
         if($isAjax){

            $response = new JsonResponse();
     
            $idtipoempresa = $this->get('request')->request->get('idtipoempresa');       
            foreach($idtipoempresa as $row){
                $em = $this->getDoctrine()->getManager();
                $valor = $em->getRepository('DGAbgSistemaBundle:CtlTipoEmpresa')->find($row);
                $em->remove($valor);
                $em->flush();
                
            }
            
            
            
            $response->setData(array(
                            'flag' => 0,
                            
                    ));    
            return $response; 
         }else {    
            
            $response->setData(array(
                           'flag'  =>   0
                    ));  
            return $response; 
        }  
        
        
        
    }
     
    

    
}
