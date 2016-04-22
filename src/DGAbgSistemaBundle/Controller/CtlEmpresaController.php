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
use DGAbgSistemaBundle\Entity\AbgPersonaSubespecialidad;
use DGAbgSistemaBundle\Resources\Tinypng\lib\lib\Tinify;

include_once '../src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify.php';
include_once '../src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify/Exception.php';
include_once '../src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify/ResultMeta.php';
include_once '../src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify/Result.php';
include_once '../src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify/Source.php';
include_once '../src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify/Client.php';




/**
 * CtlEmpresa controller.
 *
 * @Route("/")
 */
class CtlEmpresaController extends Controller
{
    /**
     * Lists all CtlEmpresa entities.
     *
     * @Route("/registro", name="registro")
     * @Method({"GET", "POST"})
     */
    public function IndexAction()
    {
      
        return $this->render('ctlempresa/index.html.twig', array(
            
        ));
    }
    
    
     /**
     * Lists all CtlEmpresa entities.
     *
     * @Route("/admin/ajusteFoto", name="ajusteFoto")
     * @Method({"GET", "POST"})
     */
    public function FotoAction()
    {
      
        return $this->render('ctlempresa/ajustesFotos.html.twig', array(
            
        ));
    }
    
    
    
    
    /**
     * Lists all CtlEmpresa entities.
     *
     * @Route("admin/empresa", name="empresa")
     * @Method({"GET", "POST"})
     */
    

    public function EmpresaAction()
    {
        $username = $this->container->get('security.context')->getToken()->getUser();
        //$establecimiento = $user->getIdEmpleado()->getIdEstablecimiento()->getId();
         
        //var_dump($username);
        
      
         $em = $this->getDoctrine()->getManager();
         $RepositorioPersona = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlUsuario')->findByUsername($username->getUsername()); //->getRhPersona();
         //var_dump($RepositorioPersona);
         $ctlEmpresaId = $username->getCtlEmpresa()->getId();
        
         //Coleccion de datos de la empresa
         
        $dqlempresa = "SELECT  e.nombreEmpresa AS nombreEmpresa, e.correoelectronico as correoEmpresa, e.direccion, e.sitioWeb,e.movil, e.telefono, e.color,e.cantidadEmpleados ,e.latitude, e.longitude ,"
                . "date_format(e.fechaFundacion, '%Y') As fechaFundacion"
                . " FROM DGAbgSistemaBundle:CtlEmpresa e WHERE e.id=" . $ctlEmpresaId;
        
        $result_empresa = $em->createQuery($dqlempresa)->getArrayResult();
         
         //Valor de la foto de la empresa
        
        $dqlfoto = "SELECT fot.src as src "
                . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.ctlEmpresa=" . $ctlEmpresaId . " and fot.estado=1 and fot.tipoFoto=1";
        $result_foto = $em->createQuery($dqlfoto)->getArrayResult();
       
        
        //Array de si se lista o no dentro del perfil de la empresa
        $RepositorioListaEmpresa = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlEmpresa')->find($ctlEmpresaId); //->getRhPersona();
        $lista =$RepositorioListaEmpresa->getListaEmpleado();
        
       if($lista){
            //Listar los empleados de la empresa
        $dql = "SELECT per.nombres as nombres, per.apellido as apellido, per.correoelectronico as correoelectronico, per.telefonoFijo as telefonoFijo, per.telefonoMovil as telefonoMovil, "
                    . " '' as sitioWeb, per.id, fot.src "
                    . "FROM DGAbgSistemaBundle:AbgFoto fot "
                    . "JOIN fot.abgPersona per "
                    . "JOIN per.ctlEmpresa emp "
                    . "WHERE emp.id =".$ctlEmpresaId
                    . " ORDER BY per.nombres ASC";
        
        $registro_empleados = $em->createQuery($dql)->getArrayResult();
           
       }else{
           
           $registro_empleados = null;
           
       }
       
        //valor de los tipos de empresa  
        $dqlTipoEmpresa = "SELECT tipo.tipoEmpresa as tipoEmpresa  "
                            . "FROM DGAbgSistemaBundle:CtlEmpresa emp "
                            . "JOIN emp.ctlTipoEmpresa tipo "
                            . "WHERE emp.id =".$ctlEmpresaId;
        
        $registro_tipoempresa = $em->createQuery($dqlTipoEmpresa)->getResult();   
        
        
       
           
        
        //Valor de la persona
        
        
        // $RepositorioPersonas = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlUsuario')->findByUsername($username); //->getRhPersona();
        $idPersona = $username->getRhPersona()->getId();
        $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo "
                    . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
        $result_persona = $em->createQuery($dql_persona)->getArrayResult();
            
        
        
        //Foto de la persona de perfil cuando este dentro del modulo de empresa
        $dqlfoto = "SELECT fot.src as src "
                . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and fot.tipoFoto=1";
        $result_fotoAbogado = $em->createQuery($dqlfoto)->getArrayResult();        
        
            
        return $this->render('ctlempresa/paneladministrativoempresa.html.twig', array(
             'ctlEmpresa' => $result_empresa,
            'abgFoto' =>$result_foto,
            'ctlEmpresaId'=>$ctlEmpresaId,
            'empleados' =>$registro_empleados,
            'tipoEmpresa' =>$registro_tipoempresa,
            'usuario'=>$username,
             'abgPersona' => $result_persona,
             'abgFotoP'=>$result_fotoAbogado,
            
        ));
    }
    
    
    
    /**
     * Lists all CtlEmpresa entities.
     *
     * @Route("/empresapublico/{username}", name="empresapublico")
     * @Method({"GET", "POST"})
     */
    
    
    public function PerfilpublicoempresaAction($username)
    {
        
      
         $em = $this->getDoctrine()->getManager();
         $RepositorioPersona = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlUsuario')->findByUsername($username); //->getRhPersona();
         $ctlEmpresaId = $RepositorioPersona[0]->getCtlEmpresa()->getId();
        
         //Coleccion de datos de la empresa
         
        $dqlempresa = "SELECT  e.nombreEmpresa AS nombreEmpresa, e.correoelectronico as correoEmpresa, e.direccion, e.sitioWeb,e.movil, e.telefono, e.color,e.cantidadEmpleados ,e.latitude, e.longitude ,"
                . "date_format(e.fechaFundacion, '%Y') As fechaFundacion"
                . " FROM DGAbgSistemaBundle:CtlEmpresa e WHERE e.id=" . $ctlEmpresaId;
        
        $result_empresa = $em->createQuery($dqlempresa)->getArrayResult();
         
         //Valor de la foto de la empresa
        
        $dqlfoto = "SELECT fot.src as src "
                . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.ctlEmpresa=" . $ctlEmpresaId . " and fot.estado=1 and fot.tipoFoto=1";
        $result_foto = $em->createQuery($dqlfoto)->getArrayResult();
       
        
        //Array de si se lista o no dentro del perfil de la empresa
        $RepositorioListaEmpresa = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlEmpresa')->find($ctlEmpresaId); //->getRhPersona();
        $lista =$RepositorioListaEmpresa->getListaEmpleado();
        
       if($lista){
            //Listar los empleados de la empresa
        $dql = "SELECT per.nombres as nombres, per.apellido as apellido, per.correoelectronico as correoelectronico, per.telefonoFijo as telefonoFijo, per.telefonoMovil as telefonoMovil, "
                    . " '' as sitioWeb, per.id, fot.src "
                    . "FROM DGAbgSistemaBundle:AbgFoto fot "
                    . "JOIN fot.abgPersona per "
                    . "JOIN per.ctlEmpresa emp "
                    . "WHERE emp.id =".$ctlEmpresaId
                    . " ORDER BY per.nombres ASC";
        
        $registro_empleados = $em->createQuery($dql)->getArrayResult();
           
       }else{
           
           $registro_empleados = null;
           
       }
       
        //valor de los tipos de empresa  
        $dqlTipoEmpresa = "SELECT tipo.tipoEmpresa as tipoEmpresa  "
                            . "FROM DGAbgSistemaBundle:CtlEmpresa emp "
                            . "JOIN emp.ctlTipoEmpresa tipo "
                            . "WHERE emp.id =".$ctlEmpresaId;
        
        $registro_tipoempresa = $em->createQuery($dqlTipoEmpresa)->getResult();  
        
        //Llenando las especialidades
        
                $dql_especialida = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre"
                        . " FROM  DGAbgSistemaBundle:CtlEspecialidad e "
                        . "JOIN  DGAbgSistemaBundle:CtlSubespecialidad sub WHERE e.id=sub.abgEspecialidad "
                        . "JOIN DGAbgSistemaBundle:AbgPersonaSubespecialidad pe WHERE sub.id=pe.abgSubespecialidad AND pe.ctlEmpresa=" .$ctlEmpresaId 
                        . " GROUP by e.id";
                $result_especialida = $em->createQuery($dql_especialida)->getArrayResult();
               
                
                if ($result_especialida) {
                    
                    $dsql_sub = "SELECT pe.id AS idSub,sub.abgSubespecialidadcol AS nombre, e.id AS idEsp  "
                            . "FROM  DGAbgSistemaBundle:AbgPersonaSubespecialidad pe "
                            . "JOIN  DGAbgSistemaBundle:CtlSubespecialidad sub WHERE  sub.id=pe.abgSubespecialidad AND  pe.ctlEmpresa=" .$ctlEmpresaId 
                            . "JOIN  DGAbgSistemaBundle:CtlEspecialidad e WHERE e.id=sub.abgEspecialidad ";
                    $result_sub = $em->createQuery($dsql_sub)->getArrayResult();
                    
                   

                }else{
                     $result_especialida=null;
                     $result_sub=null;
                    
                }
                
                
                
                

        //Valores de las persona
                
        $RepositorioPersonas = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlUsuario')->findByUsername($username); //->getRhPersona();
        $idPersona = $RepositorioPersonas[0]->getRhPersona()->getId();
        $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo "
                    . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
        $result_persona = $em->createQuery($dql_persona)->getArrayResult();
     
        
        
         
        
        return $this->render('ctlempresa/perfilEmpresa.html.twig', array(
            'ctlEmpresa' => $result_empresa,
            'abgFoto' =>$result_foto,
            'ctlEmpresaId'=>$ctlEmpresaId,
            'empleados' =>$registro_empleados,
            'tipoEmpresa' =>$registro_tipoempresa,
            'usuario'=>$username,
            'abgPersona' => $result_persona,
            'abgEspecialida'=>$result_especialida,
            'subEsp'=>$result_sub,
            
           
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
            
            $ctlEmpresa->setNombreEmpresa('Nombre de la empresa');
            $ctlEmpresa->setCtlCiudad(null);
            $ctlEmpresa->setCtlTipoEmpresa(null);
            $ctlEmpresa->setMovil('00000000');
            $ctlEmpresa->setTelefono('00000000');
            $ctlEmpresa->setSitioWeb('Sitio Web');
            $ctlEmpresa->setServicios(null);
            $ctlEmpresa->setNit(null);
            $ctlEmpresa->setFotoPerfil(null);
            $ctlEmpresa->setFechaFundacion(null);
            $ctlEmpresa->setFax(null);
            $ctlEmpresa->setDireccion('Direccion empresa');
            $ctlEmpresa->setDescripcion(null);
            $ctlEmpresa->setCorreoelectronico('ejemplo@ejemplo.com');
            $ctlEmpresa->setColor("#000035");
            
            $ctlEmpresa->setCantidadEmpleados(null);
            $ctlEmpresa->setLatitude(13.70036411285400400000);
            $ctlEmpresa->setLongitude(-89.22023010253906000000);
            $ctlEmpresa->setListaEmpleado(1);
            $em->persist($ctlEmpresa);
            $em->flush();
            
            $idEmpresa = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlEmpresa')->find($ctlEmpresa->getId());

            
            //Ingreso de un usuario
            $rol = $em->getRepository('DGAbgSistemaBundle:CtlRol')->find(2);
            
            $ctlUsuario->setUsername($correoUsuario);
            $ctlUsuario->setPassword($contrasenha);
            $ctlUsuario->setEstado('1');
            $ctlUsuario->setRhPersona($idPersona);
            $ctlUsuario->setCtlEmpresa($idEmpresa);
            $ctlUsuario->addCtlRol($rol);
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
                    $abgFotoE->setSrc('Photos/defecto/defecto.png');
                    $abgFotoE->setFechaRegistro(null);
                    $abgFotoE->setFechaExpiracion(null);
                    $abgFotoE->setEstado(1);
                     $em->persist($abgFotoE);
                     $em->flush();
            
             
                    $abgFoto->setAbgPersona($idPersona);
                    $abgFoto->setCtlEmpresa(null);
                    $abgFoto->setTipoFoto(1);
                    $abgFoto->setSrc('Photos/defecto/defecto.png');
                    $abgFoto->setFechaRegistro(null);
                    $abgFoto->setFechaExpiracion(null);
                    $abgFoto->setEstado(1);
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
     * @Route("/ingresar_foto/get", name="ingresar_foto", options={"expose"=true})
     * @Method("POST")
     */
    
    
    public function IngresarFotoAction() {
            
            $request = $this->getRequest();
            //data es el valor de retorno de ajax donde puedo ver los valores que trae dependiendo de las instrucciones que hace dentro del controlador
            
            $path2 = $this->container->getParameter('photo.perfil.temporal');
            $horaFecha = date('Y-m-d His');
            $nombreTemporal = $horaFecha;
            $nombreTemporal=str_replace(" ", "", $nombreTemporal);
            
            define('UPLOAD_DIR', $path2);
            $img = $request->get('imageDatas');
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);

            $data = base64_decode($img);
            $file = UPLOAD_DIR .$nombreTemporal. '.png';
            $success = file_put_contents($file, $data);
            
            $nombreTemporal=$nombreTemporal.'.png';
            
            
   if ($success){

            $EmpresaId = $request->get("empresaId");
            
           
 
         
         if ($nombreTemporal != null){
             
            //Direccion fisica del la imagen  
                $path1 = $this->container->getParameter('photo.perfil');
               
                $path = "Photos/perfil/E";
                $nombreArchivo = $nombreTemporal;
                
                $nombreBASE=$path.$nombreArchivo;
                
                $nombreSERVER = $nombreArchivo;

                //Codigo para poder redimensionar la  imagenes que se suben
                    \Tinify\setKey("eY78XR8uy0tp-SCRHjp4R8fX4Ib9mKgg");
                    
                     $source = \Tinify\fromFile($path2.$nombreSERVER);
                     $resized = $source->resize(array(
                         "method" => "cover",
                         "width" => 300,
                         "height" => 300
                     ));
                     
              
                $resultado=$resized->toFile($path1."E".$nombreSERVER);
                $numero =unlink($path2.$nombreSERVER);
                
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

                                if($direccion!='' && $direccion!='Photos/defecto/defecto.png'){
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
                                                $direcciones = $src[0]->getSrc();
                                                $direccionParaAjax = str_replace("\\","" , $direcciones);
                                                $datas['direccion']=$direccionParaAjax;
                                                $datas['estado']=true;

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
                                                
                                                $direcciones = $enti[0]->getSrc();
                                                $datas['direccion']=$direcciones;
                                                
                                }


                    
                }else{
                         $datas['servidor'] = "No se pudo mover la imagen al servidor";
                    
                    
                }
               
                
            }
            else{
                $datas['imagen'] = "Imagen invalida";
                
                
            }
            
             
    }
            
           return new Response(json_encode($datas));
           
      
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
                  
                 
                    $sql = "select YEAR(NOW()) as num";

                    $stm = $this->container->get('database_connection')->prepare($sql);
                    $stm->execute();
                    $anio = $stm->fetchAll();
                    $numero = $anio[0]['num'];
         
                    if ($anhoFundacion<=$numero){
                        
                       
                         $anhoFundacion= $anhoFundacion."-12-31";
                         $empresa->setFechaFundacion(new \DateTime($anhoFundacion));
                         
                         $data['valor']=true;
                         
                    }
                    else{
                        $data['valor']=false;
                        
                    }
                    
                    break;
                
                 case 8:                    
                     $cantidadEmpleados = $request->get('cantidadEmpleados');
                     $empresa->setCantidadEmpleados($cantidadEmpleados);
                    break;
                
                  case 9:                    
                     $latitude = $request->get('latitudes');
                     $longitud = $request->get('longitudes');

                     $empresa->setLatitude($latitude);
                     $empresa->setLongitude($longitud);

                    break;
                case 10:                    
                     $valor = $request->get('valor');
                     $empresa->setListaEmpleado($valor);
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
    
    
    public function RegistrarFotoPersonaAction() {
            //data es el valor de retorno de ajax donde puedo ver los valores que trae dependiendo de las instrucciones que hace dentro del controlador
         
        
            $request = $this->getRequest();
            //data es el valor de retorno de ajax donde puedo ver los valores que trae dependiendo de las instrucciones que hace dentro del controlador
            
            $path2 = $this->container->getParameter('photo.perfil.temporal');
            $horaFecha = date('Y-m-d His');
            $nombreTemporal = $horaFecha;
            $nombreTemporal=str_replace(" ", "", $nombreTemporal);
            
            define('UPLOAD_DIR', $path2);
            $img = $request->get('imageDatas');
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = UPLOAD_DIR .$nombreTemporal. '.png';
            $success = file_put_contents($file, $data);
            
            $nombreTemporal=$nombreTemporal.'.png';
           
            
    if ($success){
        $personaId = $request->get("personaId");
  
         if ($nombreTemporal != null){
             
 
          //Direccion fisica del la imagen  
                $path1 = $this->container->getParameter('photo.perfil');
               
                $path = "Photos/perfil/E";
                $nombreArchivo = $nombreTemporal;
                
                $nombreBASE=$path.$nombreArchivo;
                
                $nombreSERVER = $nombreArchivo;

                //Codigo para poder redimensionar la  imagenes que se suben
                    \Tinify\setKey("eY78XR8uy0tp-SCRHjp4R8fX4Ib9mKgg");
                    
                     $source = \Tinify\fromFile($path2.$nombreSERVER);
                     $resized = $source->resize(array(
                         "method" => "cover",
                         "width" => 300,
                         "height" => 300
                     ));
                     
              
                $resultado=$resized->toFile($path1."E".$nombreSERVER);
                $numero =unlink($path2.$nombreSERVER);
                
                
                if ($resultado){
                                $abgPersona = new AbgPersona();
                                $foto = new AbgFoto();
                                $em = $this->getDoctrine()->getManager();
                                $idPersona = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:AbgPersona')->find($personaId);
                                $src = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:AbgFoto')->findBy(array("abgPersona" =>$idPersona,"tipoFoto"=>1));
                                $direccion = $src[0]->getSrc();
                                
                                $direccion = str_replace("\\","" , $direccion);
                                $direccion = str_replace("Photos/perfil/","", $direccion);
                                
                                if($direccion!='' && $direccion!='Photos/defecto/defecto.png'){
                                    
                                    
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
                                                $datas['direccion']=$direccionParaAjax;
                                                $datas['estado']=true;
                                                

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
                                                $direcciones = $enti[0]->getSrc();
                                                
                                        
                                                $direccionPara = str_replace("\\","" , $direcciones);
                                                $datas['direccion']=$direccionPara;
                                                $datas['estado']=true;
                                               
                                                
                            }


                    
                }else{
                         $datas['servidor'] = "No se pudo mover la imagen al servidor";
                    
                    
                }
               
                
            }
            else{
                $datas['imagen'] = "Imagen invalida";
                
                
            }
    }    
         
            
           return new Response(json_encode($datas));
           
      
    }
     
    
    /**
     * @Route("/ctlempresa/mostarsubcategorias", name="mostarsubcategorias", options={"expose"=true})
     * @Method("GET")
     */
    public function Mostarsubcategorias() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
      
        
        try {
            $n = 0;
            $subEspS = "";
            $em = $this->getDoctrine()->getManager();
            $request = $this->getRequest();
            $subEspecialidadesSeleccionadas = "";
            if (($request->get('hPersona') != null)) {
                $dql_especialida = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre"
                        . " FROM  DGAbgSistemaBundle:CtlEspecialidad e "
                        . "JOIN  DGAbgSistemaBundle:CtlSubespecialidad sub WHERE e.id=sub.abgEspecialidad "
                        . "JOIN DGAbgSistemaBundle:AbgPersonaSubespecialidad pe WHERE sub.id=pe.abgSubespecialidad AND pe.ctlEmpresa=" . $request->get('hPersona')
                        . " GROUP by e.id";
                $subEspecialidadesSeleccionadas = $em->createQuery($dql_especialida)->getArrayResult();



                if (count($subEspecialidadesSeleccionadas) > 0) {
                    $sql = "select pe.abg_subespecialidad_id AS idEspSelect, sub.abg_subespecialidadcol AS nombre,sub.id AS idSub, e.id AS idEsp
                        from  marvinvi_abg.abg_persona_subespecialidad pe "
                            . " right join marvinvi_abg.ctl_subespecialidad sub on  sub.id=pe.abg_subespecialidad_id AND pe.ctl_empresa_id=" . $request->get('hPersona')
                            . " right join marvinvi_abg.ctl_especialidad e on e.id=sub.abg_especialidad_id 
                        group by  pe.id,pe.abg_subespecialidad_id, sub.abg_subespecialidadcol,sub.abg_especialidad_id";

                    $stm = $this->container->get('database_connection')->prepare($sql);
                    $stm->execute();
                    $subEspS = $stm->fetchAll();
                }
            }
            $dql_departamento = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre"
                    . " FROM  DGAbgSistemaBundle:CtlEspecialidad e "
                    . "JOIN  DGAbgSistemaBundle:CtlSubespecialidad sub WHERE e.id=sub.abgEspecialidad group by e.id";
            $result_especialida = $em->createQuery($dql_departamento)->getArrayResult();

            $dsql_sub = "SELECT e.id AS idEsp, e.nombreEspecialidad AS nombreEsp, sub.id AS idSub, sub.abgSubespecialidadcol AS nombreSub "
                    . " FROM  DGAbgSistemaBundle:CtlEspecialidad e "
                    . "JOIN  DGAbgSistemaBundle:CtlSubespecialidad sub WHERE e.id=sub.abgEspecialidad";
            $result_sub = $em->createQuery($dsql_sub)->getArrayResult();
            if ($n == 1) {
                $data['esp'] = $em->createQuery($dql_departamento)->getArrayResult();
                return new Response(json_encode($data));
            } else {
                return $this->render('abgpersona/especialidades.html.twig', array(
                            'abgEspecialida' => $result_especialida,
                            'subEsp' => $result_sub,
                            'subEspS' => $subEspS,
                            'especialidadesS' => $subEspecialidadesSeleccionadas,
                ));
            }
            
            
 
            
        } catch (\Exception $e) {
            
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        } 
    
    
     }
       
     
    /**
     * @Route("/ctlempresa/insertarsubespecialidad", name="insertarsubespecialidad", options={"expose"=true})
     * @Method("GET")
     */
    public function Insertarsubespecialidad() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        try {
          $request = $this->getRequest();
            $em = $this->getDoctrine()->getManager();
            $Persona = $em->getRepository("DGAbgSistemaBundle:CtlEmpresa")->find($request->get('hPersona'));
            $array = $request->get('SubEspecialida');

            $RepositorioSubEsp = $em->getRepository("DGAbgSistemaBundle:AbgPersonaSubespecialidad");
            if (is_null($RepositorioSubEsp->findBy(array('ctlEmpresa' => $request->get('hPersona'))))) {
                
            } else {
                $PersonaSub = $RepositorioSubEsp->findBy(array('ctlEmpresa' => $request->get('hPersona')));
                foreach ($PersonaSub as $obj) {
                    $em->remove($obj);
                    $em->flush();
                }
            }
            if (is_null($array)) {
                
            } else {
                foreach ($array as $obj) {
                    $PersonaSubespecialidad = new AbgPersonaSubespecialidad();
                    $idSub = $em->getRepository("DGAbgSistemaBundle:CtlSubespecialidad")->find(intval($obj));
                    $PersonaSubespecialidad->setCtlEmpresa($Persona);
                    $PersonaSubespecialidad->setAbgSubespecialidad($idSub);
                    $em->persist($PersonaSubespecialidad);
                    $em->flush();
                }
            }

            $dql_especialida = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre"
                    . " FROM  DGAbgSistemaBundle:CtlEspecialidad e "
                    . "JOIN  DGAbgSistemaBundle:CtlSubespecialidad sub WHERE e.id=sub.abgEspecialidad "
                    . "JOIN DGAbgSistemaBundle:AbgPersonaSubespecialidad pe WHERE sub.id=pe.abgSubespecialidad AND pe.ctlEmpresa=" . $request->get('hPersona')
                    . " GROUP by e.id";

            $data['Esp'] = $em->createQuery($dql_especialida)->getArrayResult();

            $dsql_sub = "SELECT pe.id AS idSub,sub.abgSubespecialidadcol AS nombre, e.id AS idEsp  "
                    . "FROM  DGAbgSistemaBundle:AbgPersonaSubespecialidad pe "
                    . "JOIN  DGAbgSistemaBundle:CtlSubespecialidad sub WHERE  sub.id=pe.abgSubespecialidad AND  pe.ctlEmpresa=" . $request->get('hPersona')
                    . "JOIN  DGAbgSistemaBundle:CtlEspecialidad e WHERE e.id=sub.abgEspecialidad ";
            $data['subEsp'] = $em->createQuery($dsql_sub)->getArrayResult();

            return new Response(json_encode($data));

        } catch (\Exception $e) {
            
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        } 
    
    
     }    
     
 
     
     

    

}
