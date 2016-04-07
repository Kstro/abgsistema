<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DGAbgSistemaBundle\Entity\CtlEmpresa;
use DGAbgSistemaBundle\Entity\AbgFoto;
use DGAbgSistemaBundle\Entity\AbgPersona;
use DGAbgSistemaBundle\Entity\CtlUsuario;
use DGAbgSistemaBundle\Entity\AbgSitioWeb;
use DGAbgSistemaBundle\Form\CtlEmpresaType;
use Symfony\Component\HttpFoundation\Response;
use DGAbgSistemaBundle\Resources\Tinypng\lib\lib\Tinify;

include_once '../src/DGAbgSistemaBundle/Resources/Tinypng/lib/lib/Tinify.php';
include_once '../src/DGAbgSistemaBundle/Resources/Tinypng/lib/lib/Tinify/Exception.php';
include_once '../src/DGAbgSistemaBundle/Resources/Tinypng/lib/lib/Tinify/ResultMeta.php';
include_once '../src/DGAbgSistemaBundle/Resources/Tinypng/lib/lib/Tinify/Result.php';
include_once '../src/DGAbgSistemaBundle/Resources/Tinypng/lib/lib/Tinify/Source.php';
include_once '../src/DGAbgSistemaBundle/Resources/Tinypng/lib/lib/Tinify/Client.php';




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
    
    
    
    /**
     * Lists all CtlEmpresa entities.
     *
     * @Route("/empresa/dash", name="ctlempresa_dashbord")
     * @Method({"GET", "POST"})
     */
    
    public function dashbordAction()
    {
        $ctlEmpresaId = 6;
      
        $em = $this->getDoctrine()->getManager();
        $dqlempresa = "SELECT  e.nombreEmpresa AS nombreEmpresa, e.correoelectronico as correoEmpresa, e.direccion, e.sitioWeb,e.movil, e.telefono"
                . " FROM DGAbgSistemaBundle:CtlEmpresa e WHERE e.id=" . $ctlEmpresaId;
        
        $dqlfoto = "SELECT fot.src as src "
                . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.ctlEmpresa=" . $ctlEmpresaId . " and fot.estado=1 and fot.tipoFoto=1";
        
        $result_empresa = $em->createQuery($dqlempresa)->getArrayResult();
        $result_foto = $em->createQuery($dqlfoto)->getArrayResult();
        
        //var_dump($result_foto);
        
        return $this->render('ctlempresa/perfilEmpresa.html.twig', array(
             'ctlEmpresa' => $result_empresa,
            'abgFoto' =>$result_foto,
            
        ));
    }
    
    
    
    
    
    
    
    

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
    
    
  
    public function RegistrarUsuarioAction(Request $request) {
        
    
        
        $isAjax = $this->get('Request')->isXMLhttpRequest();
         if($isAjax){
             
           $em = $this->getDoctrine()->getManager();  
            $datos = $this->get('request')->request->get('frm');       
            $frm = json_decode($datos);
             
            $ctlEmpresa = new CtlEmpresa();
            $abgPersona = new AbgPersona();
            $ctlUsuario = new CtlUsuario();
            $abgSitioWeb = new AbgSitioWeb();
            
            
            
            $nombreAbogado = $frm->txtnombre;
            $apellidoAbogado = $frm->txtapellido;
            $correoUsuario = $frm->correoEmpresa;
            $contrasenha = $frm->contrasenha;
            
            //Ingreso de una persona
            
            $abgPersona->setNombres($nombreAbogado);
            $abgPersona->setApellido($apellidoAbogado);
            $abgPersona->setCorreoelectronico($correoUsuario);
            $abgPersona->setFechaIngreso(new \DateTime("now"));
            $abgPersona->setEstado('1');
            $em->persist($abgPersona);
            $em->flush();
            $idPersona = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:AbgPersona')->find($abgPersona->getId());
             
            //Ingreso null de una empresa
            
            $ctlEmpresa->setNombreEmpresa(null);
            $ctlEmpresa->setCtlCiudad(null);
            $ctlEmpresa->setCtlTipoEmpresa(null);
            $ctlEmpresa->setMovil(null);
            $ctlEmpresa->setTelefono(null);
            $ctlEmpresa->setSitioWeb(null);
            $ctlEmpresa->setServicios(null);
            $ctlEmpresa->setNit(null);
            $ctlEmpresa->setFotoPerfil(null);
            $ctlEmpresa->setFechaFundacion(null);
            $ctlEmpresa->setFax(null);
            $ctlEmpresa->setDireccion(null);
            $ctlEmpresa->setDescripcion(null);
            $ctlEmpresa->setCorreoelectronico(null);
            
            
            
            
            
            
            $em->persist($ctlEmpresa);
            $em->flush();
            $idEmpresa = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlEmpresa')->find($ctlEmpresa->getId());
            
            
            
            
            //Ingreso de un usuario
            $ctlUsuario->setUsername($correoUsuario);
            $ctlUsuario->setPassword($contrasenha);
            $ctlUsuario->setEstado('1');
            $ctlUsuario->setRhPersona($idPersona);
            $ctlUsuario->setCtlEmpresa($idEmpresa);
            
           
            
            
            
            $this->setSecurePassword($ctlUsuario, $contrasenha);
            $em->persist($ctlUsuario);
            $em->flush();
//            $em->getConnection()->commit();
//            $em->close();
            
         
        
            
            
            
            $data = new \stdClass();
            $data->estado = true;
            $data->valor = "Las tres entidades fueron insertadas con exito";
             
        
            return new Response(json_encode($data)); 
         }else{
             
            $data = new \stdClass();
            $data->estado = false;
            $data->valor = "Error al insertar los datos";
             return new Response(json_encode($data)); 
         }
        
        
        
    }
    

    /**
     * @Route("/validar_correo/", name="validar_correo", options={"expose"=true})
     * @Method("POST")
     */
    
    
      public function ValidarCorreoAction(Request $request) {
        
        $isAjax = $this->get('Request')->isXMLhttpRequest();
         if($isAjax){
             
            $em = $this->getDoctrine()->getManager();
            $response = new JsonResponse();
            $datos = $this->get('request')->request->get('frm');       
            $frm = json_decode($datos);
            $correo = $frm->correoEmpresa;
     
            $dqlEmp = "SELECT COUNT(emp.id) AS res FROM DGAbgSistemaBundle:CtlEmpresa emp WHERE"
                   . " emp.correoelectronico = :correo ";
            
            $dqlPer = "SELECT COUNT(per.id) AS resp FROM DGAbgSistemaBundle:AbgPersona per WHERE"
                   . " per.correoelectronico = :correo ";
            
            
            $resultadoEmpresa = $em->createQuery($dqlEmp)
                        ->setParameters(array('correo'=>$correo))
                        ->getResult();
            
            
            $resultadoPersona = $em->createQuery($dqlPer)
                        ->setParameters(array('correo'=>$correo))
                        ->getResult();
            
            
            
            $rp=$resultadoPersona[0]['resp'];
            $re=$resultadoEmpresa[0]['res'];
                
            $num = $rp+$re;
            
            if ($num==0){
                
                $data = true;
            }else{
                
                $data =false;
                
                
            }
                
             return new Response(json_encode($data)); 
            
            
         }
        
        
        
    }
    

    
    
    /**
     * @Route("/ingresar_empresa/get", name="ingresar_foto", options={"expose"=true})
     * @Method("POST")
     */
    
    
    public function RegistrarEmpresaAction(Request $request) {
            //data es el valor de retorno de ajax donde puedo ver los valores que trae dependiendo de las instrucciones que hace dentro del controlador
          
            $data = new \stdClass();
            $nombreimagen2=" ";
            $dataForm = $request->get('frm');
            $nombreimagen=$_FILES['file']['name'];
            
           
            $tipo = $_FILES['file']['type'];
            $extension= explode('/',$tipo);
            $nombreimagen2.=".".$extension[1];
         
         if ($nombreimagen != null){
             
            //Direccion fisica del la imagen  
                 $path1 = $this->container->getParameter('photo.perfil');
                $data->imagenError=1;
            
                $em = $this->getDoctrine()->getManager();
           
                $path1 = $this->container->getParameter('photo.perfil');
                
                $path = "Photos/perfil/E";
                $fecha = date('Y-m-d His');
                
                $nombreArchivo = $nombreimagen."-".$fecha.$nombreimagen2;
                
                $nombreBASE=$path.$nombreArchivo;
                $nombreBASE=str_replace(" ","", $nombreBASE);
                $nombreSERVER =str_replace(" ","", $nombreArchivo);
             
                $resultado = move_uploaded_file($_FILES["file"]["tmp_name"], $path1.$nombreSERVER);
                
                
                //Codigo para poder redimensionar la  imagenes que se suben
                    \Tinify\setKey("TGdnhEaY1ZrJB1J_NSAYYLeqno6FdIYF");

                     $source = \Tinify\fromFile($path1.$nombreSERVER);
                     $resized = $source->resize(array(
                         "method" => "fit",
                         "width" => 100,
                         "height" => 100
                     ));
                     
                
                     
                $resized->toFile($path1."E".$nombreSERVER);
             
                $numero =unlink($path1.$nombreSERVER);
                
                if ($numero){
                echo "Valores cambiados con exito";
                    
                }else{
                    echo "Error al eliminar el archivo";
                }
                
                
                if ($resultado){
                    $ctlEmpresa = new CtlEmpresa();
                    $abgFoto = new AbgFoto();
                    //Ojo que posteriormente tengo que sacar los valores con el id de la variable de sesion que este presente
                    
                    $idEmpresa = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlEmpresa')->find(6);
                    //Aqui termina lo del id
                    $abgFoto->setAbgPersona(null);
                    $abgFoto->setCtlEmpresa($idEmpresa);
                    $abgFoto->setTipoFoto(1);
                    $abgFoto->setSrc($nombreBASE);
                    $abgFoto->setFechaRegistro(new \DateTime("now"));
                    $abgFoto->setFechaExpiracion(null);
                    $abgFoto->setEstado(1);
                    
                     $em->persist($abgFoto);
                     $em->flush();

                    $data->sevidor = 1;
                    echo "Datos ingresados con exito";
                    
                    
                }else{
                    $data->servidor = 2;
                    
                    
                }
               
                
            }
            else{
                
                $data->imagenError = 2;
                
            }
            
         
  
            
           $request = $this->getRequest();
           return new Response(json_encode($data));
           
           
      
    }
     
    
    
   private function setSecurePassword(&$entity, $contrasenia) {
        $entity->setSalt(md5(time()));
        $encoder = new \Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword($contrasenia, $entity->getSalt());
        $entity->setPassword($password);
    }  
    

    
 /**
     * @Route("/edit/empresa", name="edit_empresa", options={"expose"=true})
     * @Method("POST")
     */
    public function EditPersonaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $data = new \stdClass();
        try {
          
           
            
            $empresa = $em->getRepository("DGAbgSistemaBundle:CtlEmpresa")->find($request->get('empresa'));
             
            
            switch ($request->get('n')) {
                case 0:
                    $nombreEmpresa = ($request->get('nombreEmpresa'));
                       $empresa->setNombreEmpresa($nombreEmpresa);
                       
                    break;
                case 1:
                    $numeroMovil = $request->get('movil');
                    $empresa->setMovil($numeroMovil);
                  
                    break;
                 case 2:
                    $numeroFijo = $request->get('fijo');
                    $empresa->setTelefono($numeroFijo);
                    break;
                case 3:
                    $correoEmpresa = $request->get('correoEmpresa');
                    $empresa->setCorreoelectronico($correoEmpresa);
                    break;
                case 4:
                    $direccionEmpresa = $request->get('direccionEmpresa');
                    $empresa->setDireccion($direccionEmpresa);
                    break;
                case 5:
                    $sitioWebEmpresa = $request->get('sitiowebEmpresa');
                    $empresa->setSitioWeb($sitioWebEmpresa);
                    break;
            }

            
             $em->merge($empresa);
             $em->flush();
          
           
          $data->estado = true;
            
            return new Response(json_encode($data));
        } catch (\Exception $e) {
            
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }   
    
    
    
    
    
    
    
    
    

}
