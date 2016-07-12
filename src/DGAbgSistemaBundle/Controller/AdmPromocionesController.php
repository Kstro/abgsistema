<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DGAbgSistemaBundle\Entity\AdmPromociones;
use DGAbgSistemaBundle\Form\AdmPromocionesType;
use Doctrine\ORM\Query\ResultSetMapping;

include_once 'src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify.php';
include_once 'src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify/Exception.php';
include_once 'src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify/ResultMeta.php';
include_once 'src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify/Result.php';
include_once 'src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify/Source.php';
include_once 'src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify/Client.php';

/**
 * AdmPromociones controller.
 *
 * @Route("/admin/espacio-publicitario")
 */
class AdmPromocionesController extends Controller
{
    /**
     * Lists all AdmPromociones entities.
     *
     * @Route("/", name="admin_promociones_index", options={"expose"=true})
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        //$admPromociones = $em->getRepository('DGAbgSistemaBundle:AdmPromociones')->findAll();
        
        $dql = "Select fac.id, fac.monto, abo.codigo, fac.plazo, tip.tipoPago, fac.fechaPago, fac.servicio"
                            . " From DGAbgSistemaBundle:AbgFacturacion fac"
                            . " Join fac.abgPersona abo"
                            . " Join fac.abgTipoPago tip";
                            
        
        //$admPromociones = $em->createQuery($dql)->getArrayResult();
                    
        $tipoPago = $em->getRepository('DGAbgSistemaBundle:CtlTipoPago')->findAll();

        $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();

        $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                        . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil, p.estado As estado, p.tituloProfesional AS tprofesional, p.verificado As verificado "
                        . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
        $result_persona = $em->createQuery($dql_persona)->getArrayResult();
        
        $nombreCorto=split(" ",$result_persona[0]['nombre'])[0]." ".split(" ",$result_persona[0]['apellido'])[0];
        
        $dql_tipoPago = "SELECT p.id as id, p.tipoPago As nombre FROM DGAbgSistemaBundle:CtlTipoPago p ORDER BY p.tipoPago ASC";
        $TipoPago = $em->createQuery($dql_tipoPago)->getArrayResult();

        $dqlfoto = "SELECT fot.src as src FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
        $result_foto = $em->createQuery($dqlfoto)->getArrayResult();
        
        return $this->render('admpromociones/index.html.twig', array(
            'nombreCorto'=>$nombreCorto,
            'abgPersona' => $result_persona,
            'usuario'    => $idPersona,
            'TipoPago'   => $TipoPago,
            'abgFoto'    => $result_foto,
            'tipoPago'   => $tipoPago,  
            'nombreCorto'=>$nombreCorto,
            //'admPromociones' => $admPromociones,
        ));
    }

    /**
     * Creates a new AdmPromociones entity.
     *
     * @Route("/new", name="admin_promociones_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $admPromocione = new AdmPromociones();
        $form = $this->createForm('DGAbgSistemaBundle\Form\AdmPromocionesType', $admPromocione);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($admPromocione);
            $em->flush();

            return $this->redirectToRoute('admin_promociones_show', array('id' => $admPromocione->getId()));
        }

        return $this->render('admpromociones/new.html.twig', array(
            'admPromocione' => $admPromocione,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdmPromociones entity.
     *
     * @Route("/{id}", name="admin_promociones_show", options={"expose"=true})
     * @Method("GET")
     */
    public function showAction(\DGAbgSistemaBundle\Entity\AbgFacturacion $admPromocione)
    {
        $em = $this->getDoctrine()->getManager();
        
        $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();

        $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                        . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil, p.estado As estado, p.tituloProfesional AS tprofesional, p.verificado As verificado "
                        . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
        
        $result_persona = $em->createQuery($dql_persona)->getArrayResult();
        
        $nombreCorto=split(" ",$result_persona[0]['nombre'])[0]." ".split(" ",$result_persona[0]['apellido'])[0];
        
        if($admPromocione->getCtlPromociones() != NULL){
            $abgFoto = $em->getRepository("DGAbgSistemaBundle:AbgFoto")->findBy(array('promocion' => $admPromocione->getCtlPromociones()->getId()));
        } else {
            $abgFoto = NULL;
        }
        //var_dump($admPromocione->getCtlPromociones());
        
        $dql_tipoPago = "SELECT p.id as id, p.tipoPago As nombre FROM DGAbgSistemaBundle:CtlTipoPago p ORDER BY p.tipoPago ASC";
        $TipoPago = $em->createQuery($dql_tipoPago)->getArrayResult();

        $dqlfoto = "SELECT fot.src as src FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
        $result_foto = $em->createQuery($dqlfoto)->getArrayResult();
        
        $tipoPago = $em->getRepository('DGAbgSistemaBundle:CtlTipoPago')->findAll();
        
        return $this->render('admpromociones/show.html.twig', array(
            'abgPersona'    => $result_persona,
            'usuario'       => $idPersona,
            'TipoPago'      => $TipoPago,
            'tipoPago'      => $tipoPago, 
            'abgFoto'       => $result_foto,
            'admPromocione' => $admPromocione,
            'nombreCorto'=>$nombreCorto,
            'publicidad'    => $abgFoto
        ));
    }

    /**
     * Displays a form to edit an existing AdmPromociones entity.
     *
     * @Route("/{id}/edit", name="admin_promociones_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AdmPromociones $admPromocione)
    {
        $deleteForm = $this->createDeleteForm($admPromocione);
        $editForm = $this->createForm('DGAbgSistemaBundle\Form\AdmPromocionesType', $admPromocione);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($admPromocione);
            $em->flush();

            return $this->redirectToRoute('admin_promociones_edit', array('id' => $admPromocione->getId()));
        }

        return $this->render('admpromociones/edit.html.twig', array(
            'admPromocione' => $admPromocione,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdmPromociones entity.
     *
     * @Route("/{id}", name="admin_promociones_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AdmPromociones $admPromocione)
    {
        $form = $this->createDeleteForm($admPromocione);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($admPromocione);
            $em->flush();
        }

        return $this->redirectToRoute('admin_promociones_index');
    }

    /**
     * Creates a form to delete a AdmPromociones entity.
     *
     * @param AdmPromociones $admPromocione The AdmPromociones entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdmPromociones $admPromocione)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_promociones_delete', array('id' => $admPromocione->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
    * Ajax utilizado para registrar un nuevo anuncio publicitario
    *  
    * @Route("/registro/nueva-publicidad/set", name="admin_registro_anuncio_publicitario", options={"expose"=true})
    */
    public function registrarNuevaPromocionAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            $request = $this->getRequest();
            $parameters = $request->request->all();
            $nombreArchivo = '';
            $em = $this->getDoctrine()->getManager();
            
//            $id = $this->get('request')->request->get('abogado');
//            $monto = $this->get('request')->request->get('monto');
//            $tipopagoId = $this->get('request')->request->get('tipopago');
//            $servicio = $this->get('request')->request->get('tiposervicio');
//            $posicion = $this->get('request')->request->get('posicion');
//            $plazo = $this->get('request')->request->get('plazo');
//            $descuento = $this->get('request')->request->get('descuento');
//            $descripcion = $this->get('request')->request->get('descripcion');
//            $referencia = $this->get('request')->request->get('referencia');
            
            $id = $parameters['espaciop']['abogado'];
            $monto = $parameters['espaciop']['costo'];
            $tipopagoId = $parameters['espaciop']['tipopago'];
            $servicio = $parameters['espaciop']['tiposervicio'];
            $plazo = $parameters['espaciop']['plazo'];
            $descripcion = $parameters['espaciop']['descripcion'];
            $descuento = $parameters['espaciop']['descuento'];
            $nombreSERVER = "";
                    
            if(isset($parameters['espaciop']['posicion'])){
                $posicion = $parameters['espaciop']['posicion'];
            }
            
            if(isset($parameters['espaciop']['posicion'])){
                $referencia = $parameters['espaciop']['referencia'];
            } else {
                $referencia = null;
            }
            
            $abogado = $em->getRepository('DGAbgSistemaBundle:AbgPersona')->find($id);
            $tipopago = $em->getRepository('DGAbgSistemaBundle:CtlTipoPago')->find($tipopagoId);
            $fecha = new \DateTime('now');
            $fechafin = $fecha->add(new \DateInterval('P'.$plazo.'D'));
            
            if($servicio == 'Espacio publicitario'){
                $nombreimagen2=" ";
                
                $promocion = new AdmPromociones();
                $promocion->setMonto($monto);
                $promocion->setPosicion($posicion);
                $promocion->setDescuento($descuento);
                $promocion->setCtlProdServicioAdmin(null);
                $promocion->setEstado(1);
                $promocion->setFechaInicio(new \DateTime ('now'));

                $promocion->setFechaFin($fechafin);

                $em->persist($promocion);
                $em->flush();
                
                //$path1 = $this->container->getParameter('photo.publicidad');
                $path1 = $this->container->getParameter('photo.perfil');
                $nombreimagen=$_FILES['file']['name'];    

                $tipo = $_FILES['file']['type'];
                $extension= explode('/',$tipo);
                $nombreimagen2.=".".$extension[1];
            
                if ($nombreimagen != null){
                    $path = "Photos/Perfil/";
                    $fecha = date('Y-m-d His');
                    $fec = explode(' ',$fecha);
                    $nombreArchivo = "publicidad-".trim($fec[0]).trim($fec[1]).$nombreimagen2;

                    $nombreBASE=$path.$nombreArchivo;
                    $nombreBASE=str_replace(" ","", $nombreBASE);
                    $nombreSERVER =str_replace(" ","", $nombreArchivo);
                    //$imagen->setFoto($nombreSERVER);
                    $resultado = move_uploaded_file($_FILES["file"]["tmp_name"], $path1.$nombreSERVER);    
                    //n$umero = unlink($path2 . $nombreSERVER);
                    
                    $imagen = new \DGAbgSistemaBundle\Entity\AbgFoto();
                    $imagen->setPromocion($promocion);
                    $imagen->setSrc($nombreBASE);
                    $imagen->setAbgPersona(null);
                    $imagen->setCtlEmpresa(null);
                    $imagen->setTipoFoto(2);
                    $imagen->setFechaRegistro(new \DateTime ('now'));
                    $imagen->setFechaExpiracion($fechafin);
                    $imagen->setEstado(1);

                    $em->persist($imagen);
                    $em->flush();
                }
            }
                
            
                
            $facturacion = new \DGAbgSistemaBundle\Entity\AbgFacturacion();
            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            
            $suscripciones = $em->getRepository('DGAbgSistemaBundle:AbgFacturacion')->findBy(array('abgPersona' => $abogado), array('fechaPago' => 'DESC'));            
            
            if($servicio == 'Espacio publicitario'){
                $facturacion->setCtlPromociones($promocion);
            } else {
                $facturacion->setCtlPromociones(null);
            }
            
            if($suscripciones != NULL) {
                $facturacion->setFechaRegistro(new \DateTime ('now'));
                $facturacion->setFechaPago($fechafin);
                $facturacion->setPlazo($plazo);                                
            } else {
                $facturacion->setFechaRegistro(new \DateTime ('now'));
                $facturacion->setFechaPago($fechafin);
                $facturacion->setPlazo($plazo);    
            }            
            
            $facturacion->setAbgTipoPago($tipopago);
            $facturacion->setAbgPersona($abogado);
            $facturacion->setMonto($monto);                
            $facturacion->setDescripcion($descripcion);
            $facturacion->setIdUser($usuario->getId());
            $facturacion->setServicio($servicio);
            $facturacion->setCtlEmpresa(null);
            $facturacion->setReferencia($referencia);
            $facturacion->setDescuento($descuento);
            $em->persist($facturacion);
            $em->flush();
                
            $response = new JsonResponse();
            $response->setData(array(
                                  'exito'   => '1',
                                  'imagen'  => $nombreSERVER,
                               ));  
            
            return $response; 
        } else {    
            return new Response('0');              
        } 
   }
    
    /**
    * Ajax utilizado para registrar un nuevo anuncio publicitario
    *  
    * @Route("/editar/transacion/set", name="admin_edicion_anuncio_publicitario", options={"expose"=true})
    */
    public function editarTransacionAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            $request = $this->getRequest();
            $parameters = $request->request->all();
            $nombreArchivo = '';
            
            $em = $this->getDoctrine()->getManager();
            
//            $id = $this->get('request')->request->get('abogado');
//            $monto = $this->get('request')->request->get('monto');
//            $tipopagoId = $this->get('request')->request->get('tipopago');
//            $servicio = $this->get('request')->request->get('tiposervicio');
//            $posicion = $this->get('request')->request->get('posicion');
//            $plazo = $this->get('request')->request->get('plazo');
//            $descuento = $this->get('request')->request->get('descuento');
//            $descripcion = $this->get('request')->request->get('descripcion');
//            $referencia = $this->get('request')->request->get('referencia');
            $facturacionId = $parameters['facturacionId'];
            $monto = $parameters['espaciop']['costo'];
            $tipopagoId = $parameters['espaciop']['tipopago'];
            $servicio = $parameters['espaciop']['tiposervicio'];
            $plazo = $parameters['espaciop']['plazo'];
            $descripcion = $parameters['espaciop']['descripcion'];
            $descuento = $parameters['espaciop']['descuento'];
            $nombreSERVER = "";
            
            $facturacion = $em->getRepository('DGAbgSistemaBundle:AbgFacturacion')->find($facturacionId);
            
            if(isset($parameters['espaciop']['abogado'])){
                $id = $parameters['espaciop']['abogado'];
            } else {
                $id = $facturacion->getAbgPersona()->getId();
            }
            
            if(isset($parameters['espaciop']['posicion'])){
                $posicion = $parameters['espaciop']['posicion'];
            }
            
            if(isset($parameters['espaciop']['posicion'])){
                $referencia = $parameters['espaciop']['referencia'];
            } else {
                $referencia = null;
            }
            
            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            $abogado = $em->getRepository('DGAbgSistemaBundle:AbgPersona')->find($id);
            $tipopago = $em->getRepository('DGAbgSistemaBundle:CtlTipoPago')->find($tipopagoId);
            $fecha = new \DateTime('now');
            
            if($facturacion->getCtlPromociones() == NULL){
                if($servicio == 'Espacio publicitario'){
                    $nombreimagen2=" ";

                    $promocion = new AdmPromociones();
                    $promocion->setMonto($monto);
                    $promocion->setPosicion($posicion);
                    $promocion->setDescuento($descuento);
                    $promocion->setCtlProdServicioAdmin(null);
                    $promocion->setEstado(1);
                    $promocion->setFechaInicio(new \DateTime ('now'));

                    $fechafin = $fecha->add(new \DateInterval('P'.$plazo.'D'));
                    $promocion->setFechaFin($fechafin);

                    $em->persist($promocion);
                    $em->flush();

                    //$path1 = $this->container->getParameter('photo.publicidad');
                    $path1 = $this->container->getParameter('photo.perfil');
                    $nombreimagen=$_FILES['file']['name'];    

                    $tipo = $_FILES['file']['type'];
                    $extension= explode('/',$tipo);
                    $nombreimagen2.=".".$extension[1];

                    if ($nombreimagen != null){
                        $path = "Photos/Perfil/";
                        $fecha = date('Y-m-d His');
                        $fec = explode(' ',$fecha);
                        $nombreArchivo = "publicidad-".trim($fec[0]).trim($fec[1]).$nombreimagen2;

                        $nombreBASE=$path.$nombreArchivo;
                        $nombreBASE=str_replace(" ","", $nombreBASE);
                        $nombreSERVER =str_replace(" ","", $nombreArchivo);
                        //$imagen->setFoto($nombreSERVER);
                        $resultado = move_uploaded_file($_FILES["file"]["tmp_name"], $path1.$nombreSERVER);    
                        //n$umero = unlink($path2 . $nombreSERVER);

                        $imagen = new \DGAbgSistemaBundle\Entity\AbgFoto();
                        $imagen->setPromocion($promocion);
                        $imagen->setSrc($nombreBASE);
                        $imagen->setAbgPersona(null);
                        $imagen->setCtlEmpresa(null);
                        $imagen->setTipoFoto(2);
                        $imagen->setFechaRegistro(new \DateTime ('now'));
                        $imagen->setFechaExpiracion($fechafin);
                        $imagen->setEstado(1);

                        $em->persist($imagen);
                        $em->flush();
                    }
                }

//                if($servicio == 'Espacio publicitario'){
//                    $facturacion->setCtlPromociones($promocion);
//                } else {
//                    $facturacion->setCtlPromociones(null);
//                }
            } else {
                $nombreimagen2=" ";
                $promocion = $em->getRepository('DGAbgSistemaBundle:AdmPromociones')->find($facturacion->getCtlPromociones()->getId());
                
                $promocion->setMonto($monto);
                $promocion->setPosicion($posicion);
                $promocion->setDescuento($descuento);
                $promocion->setCtlProdServicioAdmin(null);
                $promocion->setEstado(1);
                //$promocion->setFechaInicio(new \DateTime ('now'));

                $fechafin = $fecha->add(new \DateInterval('P'.$plazo.'D'));
                $promocion->setFechaFin($fechafin);

                $em->merge($promocion);
                $em->flush();
                
                $imagenProm = $em->getRepository('DGAbgSistemaBundle:AbgFoto')->findOneBy(array('promocion' => $promocion));
                $em->remove($imagenProm);
                $em->flush();
                
                $path1 = $this->container->getParameter('photo.perfil');
                $nombreimagen=$_FILES['file']['name'];    

                $tipo = $_FILES['file']['type'];
                $extension= explode('/',$tipo);
                $nombreimagen2.=".".$extension[1];

                if ($nombreimagen != null){
                    $path = "Photos/Perfil/";
                    $fecha = date('Y-m-d His');
                    $fec = explode(' ',$fecha);
                    $nombreArchivo = "publicidad-".trim($fec[0]).trim($fec[1]).$nombreimagen2;

                    $nombreBASE=$path.$nombreArchivo;
                    $nombreBASE=str_replace(" ","", $nombreBASE);
                    $nombreSERVER =str_replace(" ","", $nombreArchivo);
                    //$imagen->setFoto($nombreSERVER);
                    $resultado = move_uploaded_file($_FILES["file"]["tmp_name"], $path1.$nombreSERVER);    
                    //n$umero = unlink($path2 . $nombreSERVER);

                    $imagen = new \DGAbgSistemaBundle\Entity\AbgFoto();
                    $imagen->setPromocion($promocion);
                    $imagen->setSrc($nombreBASE);
                    $imagen->setAbgPersona(null);
                    $imagen->setCtlEmpresa(null);
                    $imagen->setTipoFoto(2);
                    $imagen->setFechaRegistro(new \DateTime ('now'));
                    $imagen->setFechaExpiracion($fechafin);
                    $imagen->setEstado(1);

                    $em->persist($imagen);
                    $em->flush();
                }                                
            }
            
            if($servicio == 'Espacio publicitario'){
                $facturacion->setCtlPromociones($promocion);
            } else {
                $facturacion->setCtlPromociones(null);
            }
            
            $facturacion->setAbgTipoPago($tipopago);
            $facturacion->setAbgPersona($abogado);
            //  $facturacion->setFechaPago(new \DateTime ('now'));
            $facturacion->setMonto($monto);
            $facturacion->setPlazo($plazo);
            $facturacion->setDescripcion($descripcion);
            $facturacion->setIdUser($usuario->getId());
            $facturacion->setServicio($servicio);
            $facturacion->setCtlEmpresa(null);
            $facturacion->setReferencia($referencia);
            $facturacion->setDescuento($descuento);
            
            $em->merge($facturacion);
            $em->flush();
            
            
            
            $response = new JsonResponse();
            $response->setData(array(
                                  'exito'   => '1',
                                  'imagen'  => $nombreSERVER,
                               ));  
            
            return $response; 
        } else {    
            return new Response('0');              
        }
    }    
    
    /**
    * Ajax utilizado para registrar un nuevo anuncio publicitario
    *  
    * @Route("/cancelar/suscripcion/set", name="admin_cancelar_anuncio_publicitario", options={"expose"=true})
    */
    public function cancelarSuscripcionAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            $request = $this->getRequest();
            $parameters = $request->request->all();
            
            $em = $this->getDoctrine()->getManager();
            
            $id = $this->get('request')->request->get('id');
            $facturacion = $em->getRepository('DGAbgSistemaBundle:AbgFacturacion')->find($id);
            $promocion = $em->getRepository('DGAbgSistemaBundle:AdmPromociones')->find($facturacion->getCtlPromociones()->getId());
            $promocion->setEstado(0);
            
            $em->merge($promocion);
            $em->flush();
            
            $response = new JsonResponse();
            $response->setData(array(
                                  'exito'   => '1',
                               ));  
            
            return $response; 
        } else {    
            return new Response('0');              
        }
    }    
   
    /**
    * Ajax utilizado para buscar informacion de abogados
    *  
    * @Route("/busqueda-abogado-select/data", name="busqueda_abogado_select")
    */
    public function busquedaAbogadoAction(Request $request)
    {
        $busqueda = $request->query->get('q');
        $page = $request->query->get('page');
        
        $em = $this->getDoctrine()->getManager();
//        $dql = "SELECT abo.id abogadoid, abo.nombres, abo.apellido, abo.codigo "
//                        . "FROM DGAbgSistemaBundle:AbgPersona abo "
//                        . "WHERE upper(abo.codigo) LIKE upper(:busqueda) "
//                        . "ORDER BY abo.codigo ASC ";
//        
//        $abogado['data'] = $em->createQuery($dql)
//                ->setParameters(array('busqueda'=>"%".$busqueda."%"))
//                ->setMaxResults( 10 )
//                ->getResult();
        
        $rsm = new ResultSetMapping();
        $sql = "select abo.id abogadoid, abo.nombres as nombres, abo.apellido as apellido, ru.ctl_rol_id as rol, abo.codigo from ctl_rol_usuario ru 
                inner join ctl_usuario usu on ru.ctl_usuario_id = usu.id
                inner join abg_persona abo on usu.rh_persona_id = abo.id
                where upper(abo.codigo) LIKE upper('%".$busqueda."%') and ru.ctl_rol_id = 2
                order by abo.codigo asc
                limit 0, 10";
                
        $rsm->addScalarResult('abogadoid','abogadoid');
        $rsm->addScalarResult('nombres','nombres');
        $rsm->addScalarResult('apellido','apellido');
        $rsm->addScalarResult('rol','rol');
        $rsm->addScalarResult('codigo','codigo');

        $abogado['data'] = $em->createNativeQuery($sql, $rsm)
                                  ->getResult();
        
        return new Response(json_encode($abogado));
    }
    
    /**
    * Ajax utilizado para buscar anuncios publicitarios
    *  
    * @Route("/busqueda/publicidad/abogados", name="admin_busqueda_anuncio_publicitario", options={"expose"=true})
    */
    public function busquedaromocionAction()
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            $em = $this->getDoctrine()->getEntityManager();
            
            $i = 0;
            $recuperados = array();
            $prom = array();
            
            $dql = "Select fot.idargFoto, fot.src"
                    . " From DGAbgSistemaBundle:AbgFoto fot"
                    . " Join fot.promocion pro"
                    . " WHERE pro.posicion = 1"
                    . " ORDER BY fot.idargFoto DESC ";
            
            $promotions = $em->createQuery($dql)
                              ->getResult();  
            
            if(!empty($promotions)){
                $max = count($promotions);
                
                if($max > 10){
                    while ($i < 10){
                        $random = rand(1, ($max - 1));
                        
                        if (!in_array($random, $recuperados)) {
                            $recuperados[$i] = $random;
                            $prom[$i]['idargFoto'] = $promotions[$random]['idargFoto'];
                            $prom[$i]['src'] = $promotions[$random]['src'];
                            $i++;
                        }    
                    }
                } else {
                    foreach ($promotions as $key => $value) {
                        $prom[$key]['idargFoto'] = $value['idargFoto'];
                        $prom[$key]['src'] = $value['src'];
                    }
                }
            } else {
                $prom = NULL;
            }
            
            $response = new JsonResponse();
            $response->setData(array(
                                  'exito'   => '1',
                                  'data'    => $prom
                               ));  
            
            return $response; 
        } else {    
            return new Response('0');              
        } 
   }
}
