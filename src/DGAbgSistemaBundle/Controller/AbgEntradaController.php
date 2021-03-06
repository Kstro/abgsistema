<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DGAbgSistemaBundle\Entity\AbgEntrada;
use DGAbgSistemaBundle\Entity\CtlUsuario;
use DGAbgSistemaBundle\Entity\AbgImagenBlog;
use Symfony\Component\HttpFoundation\Response;
use DGAbgSistemaBundle\Form\AbgPersonaType;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * AbgEntrada controller.
 *
 * @Route("admin/abgentrada")
 */
class AbgEntradaController extends Controller {

    /**
     * Lists all AbgPersona entities.
     *
     * @Route("/", name="entrada_index", options={"expose"=true})
     * @Method("GET")
     */
    public function indexAction() {
        //$em = $this->getDoctrine()->getManager();
        //  $abgPersonas = $em->getRepository('DGAbgSistemaBundle:AbgPersona')->findAll();
        
        $em = $this->getDoctrine()->getManager();
        
        $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();
          $username = $this->container->get('security.context')->getToken()->getUser();
        $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil, p.estado As estado, p.tituloProfesional AS tprofesional, p.verificado As verificado "
                . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
        $result_persona = $em->createQuery($dql_persona)->getArrayResult();
        $nombreCorto = split(" ", $result_persona[0]['nombre'])[0] . " " . split(" ", $result_persona[0]['apellido'])[0];
        $dqlfoto = "SELECT fot.src as src "
                    . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
            $result_foto = $em->createQuery($dqlfoto)->getArrayResult();

        $ctlCategoriasBlog = $em->getRepository('DGAbgSistemaBundle:CtlCategoriaBlog')->findAll();
        
        $rsm = new ResultSetMapping();
        $sql = "select en.id as id, en.titulo_entrada as blog, en.fecha as fecha, cat.nombre as nombre, usu.id as usuario
                from abg_entrada en inner join ctl_categoria_blog cat on en.abg_categoria_entrada_id = cat.id
                inner join ctl_usuario usu on en.ctl_usuario_id = usu.id
                where usu.id =".$username->getId()." and en.estado=1 ";
        
        $rsm->addScalarResult('id','id');
        $rsm->addScalarResult('blog','blog');
        $rsm->addScalarResult('fecha','fecha');
        $rsm->addScalarResult('nombre','nombre');
        $rsm->addScalarResult('usuario','usuario');

        $blogsUsuario = $em->createNativeQuery($sql, $rsm)
                                  ->getResult();
        
        
        return $this->render('blog/index.html.twig', array(
                            'abgPersona' => $result_persona,
                            'abgFoto' => $result_foto,
                            'usuario' => $username,
                            'ctlCategoriasBlog' => $ctlCategoriasBlog,
                            'blogsUsuario' => $blogsUsuario,
                            'nombreCorto' => $nombreCorto
            
        ));
    }
  
    private function setSecurePassword(&$entity, $contrasenia) {
        $entity->setSalt(md5(time()));
        $encoder = new \Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword($contrasenia, $entity->getSalt());
        $entity->setPassword($password);
    }
    
    //Metodo del controlador que inserta y mueve una imagen mediante ajax


    /**
    * @Route("/ingresar_entrada/get", name="ingresar_entrada", options={"expose"=true})
    * @Method("POST")
    */    
    public function RegistrarEntradaAction(Request $request) {
        
            
            $abgEntrada = new AbgEntrada();
            $abgImagenBlog = new AbgImagenBlog();
        
            $nombreimagen2=" ";
            $dataForm = $request->get('frm');
            $nombreimagen=$_FILES['file']['name'];
            
            $tipo = $_FILES['file']['type'];
            $extension= explode('/',$tipo);
            $nombreimagen2.=".".$extension[1];
            

            $user = $this->container->get('security.context')->getToken()->getUser();
            
            $tituloEntrada = $_POST["titulo"];
            $contenidoEntrada = $_POST["contenido"];
            //$email = $_POST["email"];
            $categoria = $_POST["categoria"];
            $em = $this->getDoctrine()->getManager();
            $CatBlog = $em->getRepository('DGAbgSistemaBundle:CtlCategoriaBlog')->find($categoria);
            
            //$imagen = $_POST["imagen"];
                        
            $fecha = date('Y-m-d');  
            $abgEntrada->setTituloEntrada($tituloEntrada);
            $abgEntrada->setFecha($fecha);
            $abgEntrada->setContenido($contenidoEntrada);
            $abgEntrada->setAbgCategoriaEntradaId($CatBlog);
            $abgEntrada->setCtlUsuario($user);
            $abgEntrada->setEstado(1);
//            $abgEntrada->setAbgCategoriaEntradaId;
            
            $abgImagenBlog->setSrc($nombreimagen);
            $abgImagenBlog->setAbgEntrada($abgEntrada);
            /*$direccionEmpresa= $_POST["direccionEmpresa"];
            $sitioWebEmpresa = $_POST["sitioWebEmpresa"];
            $correoEmpresa = $_POST["correoEmpresa"];
            $telefono=$_POST["telefono"];
            $movil = $_POST["movil"];*/
            
 
         if ($nombreimagen != null){
              
                $path = $this->container->getParameter('photo.entrada');
                $fecha = date('Y-m-d-His');
                $nombreArchivo = $nombreimagen. "-" . $fecha.$nombreimagen2;
                $nombreArchivo=  str_replace(" ", "", $nombreArchivo);
                
                $resultado = move_uploaded_file($_FILES["file"]["tmp_name"], $path.$nombreArchivo);

            }
        
        $abgImagenBlog->setSrc($nombreArchivo);
        $abgImagenBlog->setAbgEntrada($abgEntrada);

        $em = $this->getDoctrine()->getManager();
        $em->persist($abgEntrada);
        $em->persist($abgImagenBlog);
        $em->flush();
        
        
        try {
           
            
            $request = $this->getRequest();

           
           return new Response(json_encode($data));
        } catch (\Exception $e) {
           
            $data['msj'] = $e->getMessage();
          

            return new Response(json_encode($data));
        }
    }
    
    
      /**
     * @Route("/seleccionarDatosEdicionEntrada/data", name="seleccionarDatosEdicionEntrada", options={"expose"=true})
     * @Method("POST")
     */
    
    function SeleccionarEdicionDatosEntrada(Request $request)
    {
          $isAjax = $this->get('Request')->isXMLhttpRequest();

         if($isAjax){
             $em = $this->getDoctrine()->getManager();
              $idEntrada = $request->get('idEditar');

               
                    $sql = "SELECT en.id, en.titulo_entrada as titulo, en.fecha, en.contenido, cat.nombre as nombreCategoria,cat.id as categoriaId, img.src, img.id as idImagen  FROM abg_entrada en"
                            . " INNER JOIN ctl_categoria_blog as cat on en.abg_categoria_entrada_id = cat.id "
                            . "INNER JOIN abg_imagen_blog img ON en.id = img.abg_entrada_id"
                            . " WHERE en.id=".$idEntrada;
                    
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    $datos = $stmt->fetchAll();

                    $sqlCategoria = "SELECT cat.id, cat.nombre  from ctl_categoria_blog cat";
                    
                    $stmt2 = $em->getConnection()->prepare($sqlCategoria);
                    $stmt2->execute();
                    $categorias = $stmt2->fetchAll();
                    
                    $data['categorias']= $categorias;
                    $data['datos']= $datos;
                    $data['estado']=true;

                    return new Response(json_encode($data)); 
               
              
         }
        

        
    }
    
    
      /**
     * @Route("/eliminarRegistroBlog/data", name="eliminarRegistroBlog", options={"expose"=true})
     * @Method("POST")
     */
    
    function ElminarDatosEntradaBlog(Request $request)
    {
          $isAjax = $this->get('Request')->isXMLhttpRequest();

         if($isAjax){
            
              $idEntrada = $request->get('idEliminar');
              
               $em = $this->getDoctrine()->getManager();
               $objEntrada = $em->getRepository('DGAbgSistemaBundle:AbgEntrada')->findById($idEntrada);
               
               $objEntrada[0]->setEstado(0);
               $em->merge($objEntrada[0]);
               $em->flush();
               $data['estado']=true;

            return new Response(json_encode($data)); 

              
         }

        
    }
    
    /**
    * @Route("/edicion/entrada/blog", name="edicion_entrada_blog", options={"expose"=true})
    * @Method("POST")
    */    
    public function EdicionEntradaBlogAction(Request $request) {
     
             $em = $this->getDoctrine()->getManager();
            $idRegistro = $_POST['idRegistro'];
            $abgEntrada = $em->getRepository('DGAbgSistemaBundle:AbgEntrada')->findById($idRegistro);
           
            $nombreimagen2=" ";
            $dataForm = $request->get('frm');
         

            $user = $this->container->get('security.context')->getToken()->getUser();

            $tituloEntrada = $_POST["tituloE"];
            $contenidoEntrada = $_POST["contenidoE"];
            
            
            
            $categoria = $_POST["categoriaE"];
           
            $CatBlog = $em->getRepository('DGAbgSistemaBundle:CtlCategoriaBlog')->findById($categoria);
            
                        
            $fecha = date('Y-m-d');  
            $abgEntrada[0]->setTituloEntrada($tituloEntrada);
            $abgEntrada[0]->setFecha($fecha);
            $abgEntrada[0]->setContenido($contenidoEntrada);
            $abgEntrada[0]->setAbgCategoriaEntradaId($CatBlog[0]);
            $abgEntrada[0]->setCtlUsuario($user);
            $abgEntrada[0]->setEstado(1);
             $em->merge($abgEntrada[0]);
             $em->flush();
             
             $idRegistroImagen = $_POST["idRegistroImagen"];
            
             
             $nombreimagen=$_FILES['fileE']['name'];
             
         if ($nombreimagen != null){
               $path = $this->container->getParameter('photo.entrada');
                $abgImagenBlog = $em->getRepository('DGAbgSistemaBundle:AbgImagenBlog')->findById($idRegistroImagen);
                $nombreImagenActual = $abgImagenBlog[0]->getSrc();
                
                 $resultadoEliminacion =unlink($path.$nombreImagenActual);
                 if ($resultadoEliminacion){
                               $tipo = $_FILES['fileE']['type']; 
                                $extension= explode('/',$tipo);
                                $fecha = date('Y-m-d His');
                                $nombreArchivo =$fecha.".".$extension[1];;
                                $nombreArchivo =str_replace(" ","", $nombreArchivo);
                            
                            
                            
                            $resultado = move_uploaded_file($_FILES["fileE"]["tmp_name"], $path.$nombreArchivo);
                            
                            if ($resultado){
                                
                                $abgImagenBlog[0]->setSrc($nombreArchivo);
                                 $em->merge($abgImagenBlog[0]);
                                 $em->flush();
                                
                            }
                            
                            
                 }
                
          

            }
        try {
           return new Response(json_encode($data));
        } catch (\Exception $e) {
           
            $data['msj'] = $e->getMessage();
          

            return new Response(json_encode($data));
        }
    }
    
    
    
    
    
    
    
    
    
    
}