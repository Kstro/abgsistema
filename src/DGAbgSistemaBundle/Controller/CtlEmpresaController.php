<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DGAbgSistemaBundle\Entity\CtlEmpresa;
use DGAbgSistemaBundle\Entity\AbgFoto;
use DGAbgSistemaBundle\Form\CtlEmpresaType;
use Symfony\Component\HttpFoundation\Response;


/**
 * CtlEmpresa controller.
 *
 * @Route("/admin/ctlempresa")
 */
class CtlEmpresaController extends Controller
{
    /**
     * Lists all CtlEmpresa entities.
     *
     * @Route("/", name="admin_ctlempresa_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ctlEmpresas    = $em->getRepository('DGAbgSistemaBundle:CtlEmpresa')->findAll();
        $ctlTipoEmpresa = $em->getRepository('DGAbgSistemaBundle:CtlTipoEmpresa')->findAll();
        $ctlCuidad = $em->getRepository('DGAbgSistemaBundle:CtlCiudad')->findAll();
        

        return $this->render('ctlempresa/index.html.twig', array(
            'ctlEmpresas' => $ctlEmpresas,
            'ctlTipoEmpresas' => $ctlTipoEmpresa ,
            'ctlCuidades' => $ctlCuidad,
        ));
    }
    
    
    
//    /**
//     * Lists all CtlEmpresa entities.
//     *
//     * @Route("/empresa/dash", name="ctlempresa_dashbord")
//     * @Method({"GET", "POST"})
//     */
//    
//    public function dashbordAction()
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        $ctlEmpresas    = $em->getRepository('DGAbgSistemaBundle:CtlEmpresa')->findAll();
//        $ctlTipoEmpresa = $em->getRepository('DGAbgSistemaBundle:CtlTipoEmpresa')->findAll();
//        $ctlCuidad = $em->getRepository('DGAbgSistemaBundle:CtlCiudad')->findAll();
//        
//
//        return $this->render('ctlempresa/formularioEdicion.html.twig', array(
//            'ctlEmpresas' => $ctlEmpresas,
//            'ctlTipoEmpresas' => $ctlTipoEmpresa ,
//            'ctlCuidades' => $ctlCuidad,
//        ));
//    }
    
    
    
    
    
    
    
    

    /**
     * Creates a new CtlEmpresa entity.
     *
     * @Route("/new", name="admin_ctlempresa_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $ctlEmpresa = new CtlEmpresa();
        $form = $this->createForm('DGAbgSistemaBundle\Form\CtlEmpresaType', $ctlEmpresa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlEmpresa);
            $em->flush();

            return $this->redirectToRoute('admin_ctlempresa_show', array('id' => $ctlEmpresa->getId()));
        }

        return $this->render('ctlempresa/new.html.twig', array(
            'ctlEmpresa' => $ctlEmpresa,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CtlEmpresa entity.
     *
     * @Route("/{id}", name="admin_ctlempresa_show")
     * @Method("GET")
     */
    public function showAction(CtlEmpresa $ctlEmpresa)
    {
        $deleteForm = $this->createDeleteForm($ctlEmpresa);

        return $this->render('ctlempresa/show.html.twig', array(
            'ctlEmpresa' => $ctlEmpresa,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CtlEmpresa entity.
     *
     * @Route("/{id}/edit", name="admin_ctlempresa_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CtlEmpresa $ctlEmpresa)
    {
        $deleteForm = $this->createDeleteForm($ctlEmpresa);
        $editForm = $this->createForm('DGAbgSistemaBundle\Form\CtlEmpresaType', $ctlEmpresa);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlEmpresa);
            $em->flush();

            return $this->redirectToRoute('admin_ctlempresa_edit', array('id' => $ctlEmpresa->getId()));
        }

        return $this->render('ctlempresa/edit.html.twig', array(
            'ctlEmpresa' => $ctlEmpresa,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CtlEmpresa entity.
     *
     * @Route("/{id}", name="admin_ctlempresa_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CtlEmpresa $ctlEmpresa)
    {
        $form = $this->createDeleteForm($ctlEmpresa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ctlEmpresa);
            $em->flush();
        }

        return $this->redirectToRoute('admin_ctlempresa_index');
    }

    /**
     * Creates a form to delete a CtlEmpresa entity.
     *
     * @param CtlEmpresa $ctlEmpresa The CtlEmpresa entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CtlEmpresa $ctlEmpresa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_ctlempresa_delete', array('id' => $ctlEmpresa->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    
    
    
    /**
     * @Route("/ingresar_usuarioEmpresa/", name="ingresar_usuarioEmpresa", options={"expose"=true})
     * @Method("POST")
     */
    
    
    public function RegistrarEmpresaUsuarioAction(Request $request) {
        
        $isAjax = $this->get('Request')->isXMLhttpRequest();
         if($isAjax){

            $response = new JsonResponse();
     
            $datos = $this->get('request')->request->get('frm');       
            $numero = json_decode($datos);
            
            
            var_dump($numero->txtnombreEmpresa);
            die();
            
            
            $response->setData(array(
                            'flag' => 0,
                            
                    ));    
            return $response; 
         }
        
        
        
    }
    
    
    
    /**
     * @Route("/ingresar_empresa/get", name="ingresar_empresa", options={"expose"=true})
     * @Method("POST")
     */
    
    
    public function RegistrarEmpresaAction(Request $request) {
        
            $nombreimagen2=" ";
            $dataForm = $request->get('frm');
            $nombreimagen=$_FILES['file']['name'];
            $tipo = $_FILES['file']['type'];
            $extension= explode('/',$tipo);
            $nombreimagen2.=".".$extension[1];
            

            
            
            $nombreEmpresa = $_POST["txtnombreEmpresa"];
            $fechaFundacion = $_POST["fechafundacion"];
            $tipoEmpresa = $_POST["tipoempresa"];
            $descripcionEmpresa = $_POST["descripcionEmpresa"];
            $direccionEmpresa= $_POST["direccionEmpresa"];
            $sitioWebEmpresa = $_POST["sitioWebEmpresa"];
            $correoEmpresa = $_POST["correoEmpresa"];
            $telefono=$_POST["telefono"];
            $movil = $_POST["movil"];
            
 
         if ($nombreimagen != null){
              
                $path = $this->container->getParameter('photo.perfil');
                $fecha = date('Y-m-d His');
                $nombreArchivo = $nombreimagen. "-" . $fecha.$nombreimagen2;
                $resultado = move_uploaded_file($_FILES["file"]["tmp_name"], $path.$nombreArchivo);

            }
           
        $em = $this->getDoctrine()->getManager();
        try {
           
            
            $request = $this->getRequest();

           
           return new Response(json_encode($data));
        } catch (\Exception $e) {
           
            $data['msj'] = $e->getMessage();
          

            return new Response(json_encode($data));
        }
    }
     
    
    
    
    
    
    
    
    
    
    

}
