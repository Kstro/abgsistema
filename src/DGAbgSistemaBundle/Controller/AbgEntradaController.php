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

/**
 * AbgEntrada controller.
 *
 * @Route("/abgentrada")
 */
class AbgEntradaController extends Controller {

    /**
     * Lists all AbgPersona entities.
     *
     * @Route("/", name="entrada_index")
     * @Method("GET")
     */
    public function indexAction() {
        //$em = $this->getDoctrine()->getManager();
        //  $abgPersonas = $em->getRepository('DGAbgSistemaBundle:AbgPersona')->findAll();
        
        $em = $this->getDoctrine()->getManager();

        $ctlCategoriasBlog = $em->getRepository('DGAbgSistemaBundle:CtlCategoriaBlog')->findAll();
        
        return $this->render('blog/index.html.twig', array(
                           'ctlCategoriasBlog' => $ctlCategoriasBlog,
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
            $abgEntrada->setAbgCategoriaEntradaId;
            
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
  

}