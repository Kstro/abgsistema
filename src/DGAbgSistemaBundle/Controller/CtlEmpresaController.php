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
use DGAbgSistemaBundle\Entity\CtlTipoEmpresa;
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
     * @Route("/empresa/{username}", name="empresa")
     * @Method({"GET", "POST"})
     */
    
   
    
    
    
    public function EmpresaAction($username)
    {
        
      
        $em = $this->getDoctrine()->getManager();
         $RepositorioPersona = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlUsuario')->findByUsername($username); //->getRhPersona();
         $ctlEmpresaId = $RepositorioPersona[0]->getCtlEmpresa()->getId();
        
        $dqlempresa = "SELECT  e.nombreEmpresa AS nombreEmpresa, e.correoelectronico as correoEmpresa, e.direccion, e.sitioWeb,e.movil, e.telefono, e.color ,"
                . "date_format(e.fechaFundacion, '%Y') As fechaFundacion"
                . " FROM DGAbgSistemaBundle:CtlEmpresa e WHERE e.id=" . $ctlEmpresaId;
        
        $result_empresa = $em->createQuery($dqlempresa)->getArrayResult();
         
         
        $dqlfoto = "SELECT fot.src as src "
                . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.ctlEmpresa=" . $ctlEmpresaId . " and fot.estado=1 and fot.tipoFoto=1";
        $result_foto = $em->createQuery($dqlfoto)->getArrayResult();
       
        
        
       // $idEmpresas = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlEmpresa')->find($ctlEmpresaId);
        
        $dql = "SELECT per.nombres as nombres, per.apellido as apellido, per.correoelectronico as correoelectronico, per.telefonoFijo as telefonoFijo, per.telefonoMovil as telefonoMovil, "
                    . " '' as sitioWeb, per.id "
                    . "FROM DGAbgSistemaBundle:CtlEmpresa emp "
                    . "JOIN emp.abgPersona per "
                    . "WHERE emp.id =".$ctlEmpresaId
                    . " ORDER BY per.nombres ASC";
        
        $registro_empleados = $em->createQuery($dql)->getArrayResult();
        
        
        
        
        
          
         $dqlTipoEmpresa = "SELECT tipo.tipoEmpresa as tipoEmpresa  "
                            . "FROM DGAbgSistemaBundle:CtlEmpresa emp "
                            . "JOIN emp.ctlTipoEmpresa tipo "
                            . "WHERE emp.id =".$ctlEmpresaId;
        
        $registro_tipoempresa = $em->createQuery($dqlTipoEmpresa)->getResult();     

      
        
 
        
        return $this->render('ctlempresa/perfilEmpresa.html.twig', array(
             'ctlEmpresa' => $result_empresa,
            'abgFoto' =>$result_foto,
            'ctlEmpresaId'=>$ctlEmpresaId,
            'empleados' =>$registro_empleados,
            'tipoEmpresa' =>$registro_tipoempresa,
            
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
            $abgFoto = new AbgFoto();
            
            
            
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
            $ctlEmpresa->setColor("#000035");

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
            
             //Insercion del registro de la foto de la empresa
                  
                    //Ojo que posteriormente tengo que sacar los valores con el id de la variable de sesion que este presente
                    
                    $idEmpresas = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlEmpresa')->find($idEmpresa);
                    
                    $abgFotoE= new AbgFoto();
                    //Aqui termina lo del id
                    $abgFotoE->setAbgPersona(null);
                    $abgFotoE->setCtlEmpresa($idEmpresas);
                    $abgFotoE->setTipoFoto(1);
                    $abgFotoE->setSrc(null);
                    $abgFotoE->setFechaRegistro(null);
                    $abgFotoE->setFechaExpiracion(null);
                    $abgFotoE->setEstado(0);
                     $em->persist($abgFotoE);
                     $em->flush();
            
             
                    $abgFoto->setAbgPersona($idPersona);
                    $abgFoto->setCtlEmpresa(null);
                    $abgFoto->setTipoFoto(1);
                    $abgFoto->setSrc(null);
                    $abgFoto->setFechaRegistro(null);
                    $abgFoto->setFechaExpiracion(null);
                    $abgFoto->setEstado(0);
                     $em->persist($abgFoto);
                     $em->flush();
                     
                  
                     
                 $data['username'] = $ctlUsuario->getUsername();
                 $data['estado']=true;
     
            return new Response(json_encode($data)); 
         }else{
             
           
            $data['estado'] = false;
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
           
            $nombreimagen2=" ";
            $dataForm = $request->get('frm');
            
            $EmpresaId = $_POST["empresaId"];
            
           
 
            $nombreimagen=$_FILES['file']['name'];

            $tipo = $_FILES['file']['type'];
            $extension= explode('/',$tipo);
            $nombreimagen2.=".".$extension[1];
         
         if ($nombreimagen != null){
             
            //Direccion fisica del la imagen  
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
                         "method" => "cover",
                         "width" => 300,
                         "height" => 300
                     ));
                     
                
                     
                $resized->toFile($path1."E".$nombreSERVER);
                $numero =unlink($path1.$nombreSERVER);
                
                

                
                if ($numero){
               
                    
                    
                }
                
                
                if ($resultado){
                                $ctlEmpresa = new CtlEmpresa();
                                $foto = new AbgFoto();
                                $em = $this->getDoctrine()->getManager();
                                //Ojo que posteriormente tengo que sacar los valores con el id de la variable de sesion que este presente
                                 //Este numero 6 es el id de la empresa, posteriormente hay que trabajarlo con la variable de sesion
                                $idEmpresa = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlEmpresa')->find($EmpresaId);
                                $src = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:AbgFoto')->findBy(array("ctlEmpresa" =>$idEmpresa,"tipoFoto"=>1));
                                $direccion = $src[0]->getSrc();
                                
                                $direccion = str_replace("\\","" , $direccion);
                                $direccion = str_replace("Photos/perfil/","", $direccion);

                                if($direccion!=''){
                                     $eliminacionRegistroExixtente =unlink($path1.$direccion);

                                        if($eliminacionRegistroExixtente){

                                                $entity = $em->getRepository('DGAbgSistemaBundle:AbgFoto')->findBy(array("ctlEmpresa" =>$idEmpresa,"tipoFoto"=>1));
                                                $entity[0]->setSrc($nombreBASE);
                                                $entity[0]->setFechaRegistro(new \DateTime("now"));
                                                $entity[0]->setFechaExpiracion(null);
                                                $entity[0]->setEstado(1);
                                                $em->merge($entity[0]);
                                                $em->flush();
                                                $src = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:AbgFoto')->findBy(array("ctlEmpresa" =>$idEmpresa,"tipoFoto"=>1));
                                                $direccion = $src[0]->getSrc();
                                                $direccionParaAjax = str_replace("\\","" , $direccion);
                                                $data['direccion']=$direccionParaAjax;

                                        }

                                }
                            else{
                                                $entity = $em->getRepository('DGAbgSistemaBundle:AbgFoto')->findBy(array("ctlEmpresa" =>$idEmpresa,"tipoFoto"=>1));
                                                $entity[0]->setSrc($nombreBASE);
                                                $entity[0]->setFechaRegistro(new \DateTime("now"));
                                                $entity[0]->setFechaExpiracion(null);
                                                $entity[0]->setEstado(1);
                                                $em->merge($entity[0]);
                                                $em->flush();
                                                
                                                $enti = $em->getRepository('DGAbgSistemaBundle:AbgFoto')->findBy(array("ctlEmpresa" =>$idEmpresa,"tipoFoto"=>1));
                                                
                                                $direccion = $enti[0]->getSrc();
                                                $direccionParaAjax = str_replace("\\","" , $direccion);
                                                $data['direccion']=$direccionParaAjax;
                                                
                            }


                    
                }else{
                         $data['servidor'] = "No se pudo mover la imagen al servidor";
                    
                    
                }
               
                
            }
            else{
                $data['imagen'] = "Imagen invalida";
                
                
            }
            
         
            
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
                case 6:
                    
                     $nombreTipoEmpresa = $request->get('tipoEmpresas');
                     $dato = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlTipoEmpresa')->findBy(array("tipoEmpresa" =>$nombreTipoEmpresa));
                     $idTipoEmpresa=$dato[0];
                     $empresa->setCtlTipoEmpresa($idTipoEmpresa);
                    break;
                
                case 7:
                    $anhoFundacion = $request->get('anhoFundacion');
                    $anhoFundacion= $anhoFundacion."-12-31";
                    
                   
                   
                   $empresa->setFechaFundacion(new \DateTime($anhoFundacion));
                    

                       break;
                                      
                     
               }

            
             $em->merge($empresa);
             $em->flush();
    
            $data['estado'] = true;
            
           return new Response(json_encode($data));
        } catch (\Exception $e) {
            
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }   
    
    
    
    /**
     * @Route("/edit/colorEmpresa", name="edit_color", options={"expose"=true})
     * @Method("POST")
     */
    public function EditColorAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        try {
          
           
            
            $empresa = $em->getRepository("DGAbgSistemaBundle:CtlEmpresa")->find($request->get('idEmpresa'));
            $colorEmpresa = $request->get('colorEmpresa');
            $empresa->setColor($colorEmpresa);
             $em->merge($empresa);
             $em->flush();
             
             $dato = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlEmpresa')->find($empresa);
             $colorBase=$dato->getColor();
             $data['color']=$colorBase;
          
            
            return new Response(json_encode($data));
        } catch (\Exception $e) {
            
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        } 
    
    
     }
     
     /**
     * @Route("/ctlempresa/mostrarTipoEmpresa", name="mostarTipoEmpresa", options={"expose"=true})
     * @Method("POST")
     */
    public function mostarTipoEmpresaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        try {
          
            $valor = $request->get('exa');
             $dqlTipoEmpresa = "SELECT te.tipoEmpresa as nombre, te.id as id"
                        . " FROM DGAbgSistemaBundle:CtlTipoEmpresa te"; 
        
            $dato['valores'] = $em->createQuery($dqlTipoEmpresa)->getResult();     
            

            return new Response(json_encode($dato));
        } catch (\Exception $e) {
            
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        } 
    
    
     }
     
    
     /**
     * @Route("/ingresar_empresa_persona/get", name="ingresar_foto_persona", options={"expose"=true})
     * @Method("POST")
     */
    
    
    public function RegistrarFotoPersonaAction(Request $request) {
            //data es el valor de retorno de ajax donde puedo ver los valores que trae dependiendo de las instrucciones que hace dentro del controlador
           
            $nombreimagen2=" ";
            $dataForm = $request->get('frm');
            
            $personaId = $_POST["personaId"];
            
           
 
            $nombreimagen=$_FILES['file']['name'];

            $tipo = $_FILES['file']['type'];
            $extension= explode('/',$tipo);
            $nombreimagen2.=".".$extension[1];
         
         if ($nombreimagen != null){
             
            //Direccion fisica del la imagen  
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
                         "method" => "cover",
                         "width" => 300,
                         "height" => 300
                     ));
                     
                
                     
                $resized->toFile($path1."E".$nombreSERVER);
                $numero =unlink($path1.$nombreSERVER);
                
                

                
                if ($numero){
               
                    
                    
                }
                
                
                if ($resultado){
                                $abgPersona = new AbgPersona();
                                $foto = new AbgFoto();
                                $em = $this->getDoctrine()->getManager();
                                //Ojo que posteriormente tengo que sacar los valores con el id de la variable de sesion que este presente
                                 //Este numero 6 es el id de la empresa, posteriormente hay que trabajarlo con la variable de sesion
                                $idPersona = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:AbgPersona')->find($personaId);
                                $src = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:AbgFoto')->findBy(array("abgPersona" =>$idPersona,"tipoFoto"=>1));
                                $direccion = $src[0]->getSrc();
                                
                                $direccion = str_replace("\\","" , $direccion);
                                $direccion = str_replace("Photos/perfil/","", $direccion);

                                if($direccion!=''){
                                     $eliminacionRegistroExixtente =unlink($path1.$direccion);

                                        if($eliminacionRegistroExixtente){

                                                $entity = $em->getRepository('DGAbgSistemaBundle:AbgFoto')->findBy(array("abgPersona" =>$idPersona,"tipoFoto"=>1));
                                                $entity[0]->setSrc($nombreBASE);
                                                $entity[0]->setFechaRegistro(new \DateTime("now"));
                                                $entity[0]->setFechaExpiracion(null);
                                                $entity[0]->setEstado(1);
                                                $em->merge($entity[0]);
                                                $em->flush();
                                                $src = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:AbgFoto')->findBy(array("abgPersona" =>$idPersona,"tipoFoto"=>1));
                                                $direccion = $src[0]->getSrc();
                                                $direccionParaAjax = str_replace("\\","" , $direccion);
                                                $data['direccion']=$direccionParaAjax;

                                        }

                                }
                            else{
                                                $entity = $em->getRepository('DGAbgSistemaBundle:AbgFoto')->findBy(array("abgPersona" =>$idPersona,"tipoFoto"=>1));
                                                $entity[0]->setSrc($nombreBASE);
                                                $entity[0]->setFechaRegistro(new \DateTime("now"));
                                                $entity[0]->setFechaExpiracion(null);
                                                $entity[0]->setEstado(1);
                                                $em->merge($entity[0]);
                                                $em->flush();
                                                
                                                $enti = $em->getRepository('DGAbgSistemaBundle:AbgFoto')->findBy(array("abgPersona" =>$idPersona,"tipoFoto"=>1));
                                                
                                                $direccion = $enti[0]->getSrc();
                                                $direccionParaAjax = str_replace("\\","" , $direccion);
                                                $data['direccion']=$direccionParaAjax;
                                                
                            }


                    
                }else{
                         $data['servidor'] = "No se pudo mover la imagen al servidor";
                    
                    
                }
               
                
            }
            else{
                $data['imagen'] = "Imagen invalida";
                
                
            }
            
         
            
           return new Response(json_encode($data));
           
      
    }
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
    

}
