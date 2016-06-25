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
use DGAbgSistemaBundle\Entity\AbgPregunta;
use DGAbgSistemaBundle\Form\CtlCiudadType;

/**
 * CtlCiudad controller.
 *
 * @Route("/directorio")
 */
class DirectorioController extends Controller {

    /**
     * Lists all CtlCiudad entities.
     *
     * @Route("/", name="directorio_index", options={"expose"=true}))
     * @Method("GET")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $prom = $this->busquedaPublicidad(1);
        $prom2 = $this->busquedaPublicidad(2);
        $prom3 = $this->busquedaPublicidad(3);
        $prom4 = $this->busquedaPublicidad(4);
        $busqueda = $this->getRequest()->get('busqueda');
        $ciudad = $this->getRequest()->get('ciu');

        if (isset($ciudad) && $ciudad != '' && $ciudad != 'undefined') {
            
        } else {
            $ciudad = "";
        }


        return $this->render('directorio/directorio.html.twig', array(
//            'ciudades' => $ctlCiudads,
                    'prom1' => $prom,
                    'prom2' => $prom2,
                    'prom3' => $prom3,
                    'prom4' => $prom4,
                    'busqueda' => $busqueda,
                    'ciudad' => $ciudad
        ));
    }

    /**
     * 
     *
     * @Route("/busqueda/data/registros", name="busqueda_data")
     */
    public function databusquedaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $inicio = $request->get('inicio');
        $longitud = $request->get('longitud');
        $paginaActual = $request->get('paginaActual');
        $busqueda = $request->get('busqueda');
        $orderBy = $request->get('orderBy');


        $deptoId = '';


        if ($request->get('munId') !== '') {
            $deptoId = $request->get('munId');
        }
        $deptoId = strtoupper($deptoId);
        ////////////////////////////////////////////////
        /*
        $sql = "SELECT * FROM directorio ORDER BY nombres ASC LIMIT " . $inicioRegistro . "," . $longitud;

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $reg['data'] = $stmt->fetchAll();
        $sql = "SELECT COUNT(*) as total FROM directorio ORDER BY nombres ASC LIMIT 0,10";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $totales = $stmt->fetchAll();
        $reg['numRegistros'] = $totales[0]['total'];
       */
        ////////////////////////////////////////////////
//        $inicioRegistro = ($paginaActual*10)-10;
        $inicioRegistro = ($longitud * ($paginaActual - 1));

        $response = new JsonResponse();

        $em = $this->getDoctrine()->getManager();
        $abogadosTotal = $em->getRepository('DGAbgSistemaBundle:AbgPersona')->findBy(array('estado' => 1));
        
        
//        set_time_limit(0.5);
//        sleep(5);
        //echo '¿';
        $reg['inicio'] = $inicio++;
        $reg['longitud'] = $longitud;
        $reg['paginaActual'] = $paginaActual;
        $reg['inicioRegistro'] = $inicioRegistro;
        $reg['data'] = array();
        //var_dump($orderBy);
        //die();
        if($orderBy==""){
            $reg['order'] = "";
            $sql = "SELECT GROUP_CONCAT(orden ORDER BY RAND()) as 'order' FROM directorio WHERE CONCAT(upper(nombres),' ',upper(apellido),' ',upper(sub),' ',upper(especialidad)) LIKE '%" . strtoupper($busqueda) . "%' ORDER BY RAND() LIMIT " . $inicioRegistro . "," . $longitud;
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $reg['order'] = $stmt->fetchAll();
            $reg['order'] =$reg['order'][0]['order'];
            $orderBy = $reg['order'];
        }
        else{
            $reg['order'] = $orderBy;
        }
        $newOrderBy="";
        $list = explode(",",$orderBy);
        foreach ($list as $key=>$row){
            if($key==(count($list)-1)){
                $newOrderBy.="'".$row."'";
            }
            else{
                $newOrderBy.="'".$row."',";
            }
        }
        
        //echo $newOrderBy;
        //var_dump($data);
        //die();

        //var_dump($orderBy);
        //var_dump($data);
        //die();
        if ($busqueda != '' && $deptoId == '') {
            $reg['numRegistros'] = 0;
            if ($deptoId == "") {
                //            echo "sin filtro";
                $sql = "SELECT * FROM directorio WHERE orden in ($newOrderBy) AND CONCAT(upper(nombres),' ',upper(apellido),' ',upper(sub),' ',upper(especialidad)) LIKE '%" . strtoupper($busqueda) . "%' ORDER BY FIELD(orden,$newOrderBy) LIMIT " . $inicioRegistro . "," . $longitud;
                //            $sql = "SELECT estado,ciudad,url,nombres,apellido,correoelectronico,telefono_fijo,telefono_movil,tipo,id,foto,group_concat(sub) FROM directorio WHERE CONCAT(upper(nombres),' ',upper(apellido),' ', upper(sub)) LIKE '%".strtoupper($busqueda)."%' ORDER BY nombres ASC LIMIT ".$inicioRegistro.",".$longitud;
                //echo $sql;
                $stmt = $em->getConnection()->prepare($sql);
                $stmt->execute();
                $reg['data'] = $stmt->fetchAll();
                //var_dump($reg);
                //            $sql = "SELECT COUNT(*) as total FROM directorio WHERE CONCAT(upper(nombres),' ',upper(apellido)) LIKE '%".strtoupper($busqueda)."%' ORDER BY nombres ASC LIMIT 0,10";
                $sql = "SELECT COUNT(*) as total FROM directorio WHERE orden in($newOrderBy) AND CONCAT(upper(nombres),' ',upper(apellido),' ',upper(sub),' ',upper(especialidad) ) LIKE '%" . strtoupper($busqueda) . "%' ORDER BY FIELD(orden,$newOrderBy) LIMIT 0,".$longitud;
                $stmt = $em->getConnection()->prepare($sql);
                $stmt->execute();
                $totales = $stmt->fetchAll();
                $reg['numRegistros'] = $totales[0]['total'];
                //            $reg['numRegistros'] = intval($reg['data']);
            } else {
                if ($deptoId != "") {
                    //                echo "busqueda depto";
                    $sql = "SELECT * FROM directorio "
                            . " WHERE  UCASE(departamento) LIKE '%" . strtoupper(trim($deptoId)) . "' AND CONCAT(upper(nombres),' ',upper(apellido),' ',upper(sub),' ',upper(especialidad)) LIKE '%" . strtoupper($busqueda) . "%' ORDER BY nombres ASC LIMIT " . $inicioRegistro . "," . $longitud;
                    //                $sql = "SELECT estado,ciudad,url,nombres,apellido,correoelectronico,telefono_fijo,telefono_movil,tipo,id,foto,GROUP_concat(sub) FROM directorio WHERE estado=".$deptoId." AND CONCAT(upper(nombres),' ',upper(apellido),' ',upper(sub)) LIKE '%".strtoupper($busqueda)."%' ORDER BY nombres ASC LIMIT ".$inicioRegistro.",".$longitud;
                    //echo $sql;
                    
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    $reg['data'] = $stmt->fetchAll();
                    //var_dump($reg);
                    //                $sql = "SELECT COUNT(*) as total FROM directorio WHERE estado=".$deptoId." AND CONCAT(upper(nombres),' ',upper(apellido)) LIKE '%".strtoupper($busqueda)."%' ORDER BY nombres ASC LIMIT 0,10";
                    $sql = "SELECT COUNT(*) as total FROM directorio WHERE UCASE(departamento) LIKE '%" . trim($deptoId) . "'  AND CONCAT(upper(nombres),' ',upper(apellido),' ',upper(sub),' ',upper(especialidad)) LIKE '%" . strtoupper($busqueda) . "%' ORDER BY nombres ASC LIMIT 0,".$longitud;

                    $em = $this->getDoctrine()->getManager();
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    $totales = $stmt->fetchAll();
                    $reg['numRegistros'] = $totales[0]['total'];
                }
            }


            //$reg['numRegistros']= count($reg3['data']);
            if ($reg['numRegistros'] > $longitud) {
                if(intval($reg['numRegistros']) % intval($longitud)!=0){
                    $reg['pages'] = floor(($reg['numRegistros'] / $longitud)) + 1;
                }
                else{
                    $reg['pages'] = floor(($reg['numRegistros'] / $longitud));
                }
                
            } else {
                $reg['pages'] = 1;
            }


            $reg['filtroRegistros'] = count($reg['data']);
            $esp = array();

            $i = 0;
        } else if ($busqueda != "" && $deptoId != "") {

            $sql = "SELECT * FROM directorio "
                    . " WHERE  UCASE(departamento) LIKE '%" . strtoupper(trim($deptoId)) . "%' AND CONCAT(upper(nombres),' ',upper(apellido),' ',upper(sub),' ',upper(especialidad)) LIKE '%" . strtoupper(trim($busqueda)) . "%' ORDER BY nombres ASC LIMIT " . $inicioRegistro . "," . $longitud;


            
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $reg['data'] = $stmt->fetchAll();

            $sql = "SELECT COUNT(*) as total FROM directorio WHERE UCASE(departamento) LIKE '%" . strtoupper(trim($deptoId)) . "%'  AND CONCAT(upper(nombres),' ',upper(apellido),' ',upper(sub),' ',upper(especialidad)) LIKE '%" . strtoupper(trim($busqueda)) . "%' ORDER BY nombres ASC LIMIT 0,".$longitud;

            
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $totales = $stmt->fetchAll();

            $reg['numRegistros'] = $totales[0]['total'];
            
            if(intval($reg['numRegistros']) % intval($longitud)!=0){
                $reg['pages'] = floor(($reg['numRegistros'] / $longitud)) + 1;
            }
            else{
                $reg['pages'] = floor(($reg['numRegistros'] / $longitud));
            }
            


            $reg['filtroRegistros'] = count($reg['data']);
            $esp = array();

            $i = 0;
        } else if ($busqueda == '' && $deptoId != "") {
            $reg['numRegistros'] = 0;
            $sql = "SELECT * FROM directorio "
                    . " WHERE  UCASE(departamento) LIKE '%" . trim($deptoId) . "%' ORDER BY nombres ASC LIMIT " . $inicioRegistro . "," . $longitud;

            
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $reg['data'] = $stmt->fetchAll();

            if ($reg['numRegistros'] > $longitud) {
                $reg['pages'] = floor(($reg['numRegistros'] / $longitud)) + 1;
            } else {
                $reg['pages'] = 1;
            }
            $reg['filtroRegistros'] = count($reg['data']);
            $esp = array();

            $i = 0;

            $sql = "SELECT COUNT(*) as total FROM directorio WHERE UCASE(departamento) LIKE '%" . trim($deptoId) . "%' ORDER BY nombres ASC LIMIT 0,".$longitud;

            
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $totales = $stmt->fetchAll();

            $reg['numRegistros'] = $totales[0]['total'];
        } else {

            $reg['numRegistros'] = 0;
            $reg['pages'] = 0;
            $reg['filtroRegistros'] = 0;
            $reg['data'] = array();
            $reg['pages'] = 0;
        }



        $response->setData($reg);
        return $response;
        //return new Response(json_encode($reg));
    }

    /**
     * 
     *
     * @Route("/busqueda/municipios/registros", name="busqueda_mun")
     */
    public function municipiosBusquedaAction(Request $request) {


        $deptoId = $request->get('deptoId');
        $munId = $request->get('munId');



        $response = new JsonResponse();

        $em = $this->getDoctrine()->getManager();
        //$municipios['regs'] = $em->getRepository('DGAbgSistemaBundle:CtlCiudad')->findBy(array('ctlEstado'=>intval($deptoId)));
//        $dql
        $dql = "SELECT c.id as id,c.nombreCiudad as nombre FROM DGAbgSistemaBundle:CtlCiudad c "
                . "JOIN c.ctlEstado est WHERE est.id=:deptoId";
        $municipios = $em->createQuery($dql)
                ->setParameter('deptoId', $deptoId)
                ->getResult();
        $response->setData($municipios);
        return $response;
    }

    private function busquedaPublicidad($posicion) {
        $em = $this->getDoctrine()->getManager();

        $i = 0;
        $recuperados = array();
        $prom = array();

        $dql = "Select fot.idargFoto, fot.src From DGAbgSistemaBundle:AbgFoto fot Join fot.promocion pro"
                . " WHERE pro.posicion = :posicion  and pro.estado = 1 and fot.fechaExpiracion > :fecha"
                . " ORDER BY fot.idargFoto DESC ";

        $fecha = new \DateTime('now');

        $promotions = $em->createQuery($dql)
                ->setParameter('posicion', $posicion)
                ->setParameter('fecha', $fecha)
                ->getResult();

        if (!empty($promotions)) {
            $max = count($promotions);

            if ($max > 20) {
                while ($i < 20) {
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

        return $prom;
    }

    /**
     * Abogado landing.
     *
     * @Route("/Abogado", name="soy_abogado")
     * @Method({"GET", "POST"})
     */
    public function SoyAbogadoAction() {
        return $this->render('Layout/abogado_landing.html.twig');
    }

//    /**
//     * Creates a new CtlCiudad entity.
//     *
//     * @Route("/new", name="ctlciudad_new")
//     * @Method({"GET", "POST"})
//     */
////    public function newAction(Request $request)
////    {
////        $ctlCiudad = new CtlCiudad();
////        $form = $this->createForm('DGAbgSistemaBundle\Form\CtlCiudadType', $ctlCiudad);
////        $form->handleRequest($request);
////
////        if ($form->isSubmitted() && $form->isValid()) {
////            $em = $this->getDoctrine()->getManager();
////            $em->persist($ctlCiudad);
////            $em->flush();
////
////            return $this->redirectToRoute('ctlciudad_show', array('id' => $ctlCiudad->getId()));
////        }
////
////        return $this->render('ctlciudad/new.html.twig', array(
////            'ctlCiudad' => $ctlCiudad,
////            'form' => $form->createView(),
////        ));
////    }

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
