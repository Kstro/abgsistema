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

include_once '../src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify.php';
include_once '../src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify/Exception.php';
include_once '../src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify/ResultMeta.php';
include_once '../src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify/Result.php';
include_once '../src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify/Source.php';
include_once '../src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify/Client.php';

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
     * @Route("/", name="admin_promociones_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        //$admPromociones = $em->getRepository('DGAbgSistemaBundle:AdmPromociones')->findAll();
        
        $dql = "Select prom.id, prom.posicion, prom.monto, abo.codigo, fac.plazo, tip.tipoPago, fac.fechaPago"
                            . " From DGAbgSistemaBundle:AbgFacturacion fac"
                            . " Join fac.ctlPromociones prom"
                            . " Join fac.abgPersona abo"
                            . " Join fac.abgTipoPago tip";
        
        $admPromociones = $em->createQuery($dql)->getArrayResult();
                    
        $tipoPago = $em->getRepository('DGAbgSistemaBundle:CtlTipoPago')->findAll();

        $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();

        $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                        . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil, p.estado As estado, p.tituloProfesional AS tprofesional, p.verificado As verificado "
                        . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
        $result_persona = $em->createQuery($dql_persona)->getArrayResult();
        
        $dql_tipoPago = "SELECT p.id as id, p.tipoPago As nombre FROM DGAbgSistemaBundle:CtlTipoPago p ORDER BY p.tipoPago ASC";
        $TipoPago = $em->createQuery($dql_tipoPago)->getArrayResult();

        $dqlfoto = "SELECT fot.src as src FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
        $result_foto = $em->createQuery($dqlfoto)->getArrayResult();
        
        return $this->render('admpromociones/index.html.twig', array(
            'abgPersona' => $result_persona,
            'usuario'    => $idPersona,
            'TipoPago'   => $TipoPago,
            'abgFoto'    => $result_foto,
            'tipoPago'   => $tipoPago,  
            'admPromociones' => $admPromociones,
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
     * @Route("/{id}", name="admin_promociones_show")
     * @Method("GET")
     */
    public function showAction(AdmPromociones $admPromocione)
    {
        $deleteForm = $this->createDeleteForm($admPromocione);

        return $this->render('admpromociones/show.html.twig', array(
            'admPromocione' => $admPromocione,
            'delete_form' => $deleteForm->createView(),
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
            $em = $this->getDoctrine()->getManager();
            
            $id = $this->get('request')->request->get('abogado');
            $monto = $this->get('request')->request->get('monto');
            $tipopagoId = $this->get('request')->request->get('tipopago');
            $posicion = $this->get('request')->request->get('posicion');
            $plazo = $this->get('request')->request->get('plazo');
            $descuento = $this->get('request')->request->get('descuento');
            $descripcion = $this->get('request')->request->get('descripcion');
            
            $abogado = $em->getRepository('DGAbgSistemaBundle:AbgPersona')->find($id);
            $tipopago = $em->getRepository('DGAbgSistemaBundle:CtlTipoPago')->find($tipopagoId);
            $fecha = new \DateTime('now');
            
            $promocion = new AdmPromociones();
            $promocion->setMonto($monto);
            $promocion->setPosicion($posicion);
            $promocion->setDescuento($descuento);
            $promocion->setCtlProdServicioAdmin(null);
            $promocion->setEstado(1);
            $promocion->setFechaInicio($fecha);
            
            $fechafin = $fecha->add(new \DateInterval('P'.$plazo.'D'));
            $promocion->setFechaFin($fechafin);
            
            $em->persist($promocion);
            $em->flush();
            
            $facturacion = new \DGAbgSistemaBundle\Entity\AbgFacturacion();
            $usuario= $this->get('security.token_storage')->getToken()->getUser();
            
            $facturacion->setCtlPromociones($promocion);
            $facturacion->setAbgTipoPago($tipopago);
            $facturacion->setAbgPersona($abogado);
            $facturacion->setFechaPago(new \DateTime ('now'));
            $facturacion->setMonto($monto);
            $facturacion->setPlazo($plazo);
            $facturacion->setDescripcion($descripcion);
            $facturacion->setIdUser($usuario->getId());
            $facturacion->setServicio('Espacio publicitario');
            $facturacion->setCtlEmpresa(null);
            
            $em->persist($facturacion);
            $em->flush();
            
            $pathTemporal = $this->container->getParameter('photo.perfil.temporal');
            $horaFecha = date('Y-m-d His');
            $nombreTemporal = $horaFecha;
            $nombreTemporal = str_replace(" ", "", $nombreTemporal);
            
            define('UPLOAD_DIR', $pathTemporal);
            $anuncioImg = $request->get('imagen');
            $anuncioImg = str_replace('data:image/png;base64,', '', $anuncioImg);
            $anuncioImg = str_replace(' ', '_', $anuncioImg);
            $data = base64_decode($anuncioImg);
            $file = UPLOAD_DIR . $nombreTemporal . '.png';
            $success = file_put_contents($file, $data);
            $nombreTemporal = $nombreTemporal . '.png';

            if ($success) {
                if ($nombreTemporal != null) {
                    //Direccion fisica del la imagen  
                    $path = $this->container->getParameter('photo.publicidad');
                    var_dump($path);
                    //$path = "Photos/Perfil/E";
                    $nombreArchivo = $nombreTemporal;
                    $nombreBASE = $path . $nombreArchivo;
                    $nombreSERVER = $nombreArchivo;
                    //var_dump($pathTemporal . $nombreSERVER);
                    //die();
                    //Codigo para poder redimensionar la  imagenes que se suben
//                    \Tinify\setKey("H9jR26ywRdh6J3Es7TXAPjIRAz5xuQHZ");
//
//                    $source = \Tinify\fromFile($pathTemporal . $nombreSERVER);
//                    
//                    $resized = $source->resize(array(
//                        "method" => "cover",
//                        "width" => 300,
//                        "height" => 300
//                    ));
                    
//                    $resultado = $resized->toFile($path . $nombreSERVER);
                    $numero = unlink($pathTemporal . $nombreSERVER);
                    $resultado=1;
                    if ($resultado) {
                        $imagen = new \DGAbgSistemaBundle\Entity\AbgFoto();
                        
                        $imagen->setPromocion($promocion);
                        $imagen->setSrc($nombreBASE);
                        $imagen->setAbgPersona(null);
                        $imagen->setCtlEmpresa(null);
                        $imagen->setTipoFoto(2);
                        $imagen->setFechaRegistro(new \DateTime ('now'));
                        $imagen->setFechaExpiracion($fechafin);
                        $imagen->setEstado(1);
                        
                    }
                }
            }
            
            $response = new JsonResponse();
            $response->setData(array(
                                  'exito'       => '1',
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
        
        $em = $this->getDoctrine()->getEntityManager();
        $dql = "SELECT abo.id abogadoid, abo.nombres, abo.apellido, abo.codigo "
                        . "FROM DGAbgSistemaBundle:AbgPersona abo "
                        . "WHERE upper(abo.codigo) LIKE upper(:busqueda) "
                        . "ORDER BY abo.codigo ASC ";
        
        $abogado['data'] = $em->createQuery($dql)
                ->setParameters(array('busqueda'=>"%".$busqueda."%"))
                ->setMaxResults( 10 )
                ->getResult();
        
        return new Response(json_encode($abogado));
    }
}
