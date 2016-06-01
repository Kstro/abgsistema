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
class DirectorioController extends Controller
{
    /**
     * Lists all CtlCiudad entities.
     *
     * @Route("/", name="directorio_index", options={"expose"=true}))
     * @Method("GET")
     */
    public function indexAction()
    { 
        $em = $this->getDoctrine()->getManager();

        $prom = $this->busquedaPublicidad(1);
        $prom2 = $this->busquedaPublicidad(2);
        $prom3 = $this->busquedaPublicidad(3);
        $prom4 = $this->busquedaPublicidad(4);
        $busqueda = $this->getRequest()->get('busqueda');
        $ciudad= $this->getRequest()->get('ciu');
        $depto= $this->getRequest()->get('depto');
//        var_dump($busqueda);
//        var_dump(isset($ciudad));
//        var_dump(isset($depto));
        if(isset($ciudad)&& $ciudad!='' && $ciudad!='undefined'){
            $ctlCiudads = $em->getRepository('DGAbgSistemaBundle:CtlCiudad')->findBy(array('nombreCiudad'=>$ciudad));
            //var_dump($ctlCiudads);
            if(isset($depto) && $depto !='' && $depto !='undefined'){
                $ctlEstado = $em->getRepository('DGAbgSistemaBundle:CtlEstado')->findBy(array('nombreEstado'=>$depto));
            }
            else{
                $ctlEstado = $em->getRepository('DGAbgSistemaBundle:CtlEstado')->findAll();
            }
        }
        else{
            //$ctlEstado = $em->getRepository('DGAbgSistemaBundle:CtlEstado')->findAll();
            $dql = "SELECT c FROM DGAbgSistemaBundle:CtlEstado c "
                    . "INNER JOIN c.ctlPais p WHERE p.estado=1";

            $ctlEstado = $em->createQuery($dql)
                       //->setParameter('id',$id)
                       ->getResult();
        }
//        var_dump($ctlEstado);
//        var_dump($ctlCiudads);
        if(count($ctlEstado)==0){
            $ctlEstado = $em->getRepository('DGAbgSistemaBundle:CtlEstado')->findAll();
        }
//        if(count($ctlCiudads)==0){
//            $ctlCiudads= $em->getRepository('DGAbgSistemaBundle:CtlCiudad')->findAll();
//        }
//        var_dump($ctlCiudads);
        
        
        return $this->render('directorio/directorio.html.twig', array(
            'deptos' => $ctlEstado,
            'ciudades' => $ctlCiudads,
            'prom1'   => $prom,
            'prom2'   => $prom2,
            'prom3'   => $prom3,
            'prom4'   => $prom4,
            'busqueda' => $busqueda,
            
        ));
    }
    
    /**
     * 
     *
     * @Route("/busqueda/data/registros", name="busqueda_data")
     */
    public function databusquedaAction(Request $request)
    {
        
        $inicio = $request->get('inicio');
        $longitud = $request->get('longitud');
        $paginaActual = $request->get('paginaActual');
        $busqueda = $request->get('busqueda');
        $deptoId = $request->get('deptoId');
        $munId = $request->get('munId');
        
//        $inicioRegistro = ($paginaActual*10)-10;
        $inicioRegistro = ($longitud * ($paginaActual - 1));
                          
        $response = new JsonResponse();
   
        $em = $this->getDoctrine()->getEntityManager();
        $abogadosTotal = $em->getRepository('DGAbgSistemaBundle:AbgPersona')->findBy(array('estado'=>1));
        //$empresaTotal = $em->getRepository('DGAbgSistemaBundle:CtlEmpresa')->findBy(array('estado'=>1));
        
        //var_dump($reg);
        //die();
        //var_dump($data);
        $reg['inicio']=$inicio++;  
        $reg['longitud'] = $longitud;
        $reg['paginaActual']= $paginaActual;
        $reg['inicioRegistro']= $inicioRegistro;
        $reg['data']= array();
        
        if($busqueda!=''){        
            $reg['numRegistros']= 0;
            
        
            if($deptoId==0 && $munId==0){
    //            echo "sin filtro";
                $sql = "SELECT * FROM directorio WHERE CONCAT(upper(nombres),' ',upper(apellido),' ',upper(sub),' ',upper(especialidad)) LIKE '%".strtoupper($busqueda)."%' ORDER BY nombres ASC LIMIT ".$inicioRegistro.",".$longitud;
    //            $sql = "SELECT estado,ciudad,url,nombres,apellido,correoelectronico,telefono_fijo,telefono_movil,tipo,id,foto,group_concat(sub) FROM directorio WHERE CONCAT(upper(nombres),' ',upper(apellido),' ', upper(sub)) LIKE '%".strtoupper($busqueda)."%' ORDER BY nombres ASC LIMIT ".$inicioRegistro.",".$longitud;
                //echo $sql;
                $em = $this->getDoctrine()->getManager();
                $stmt = $em->getConnection()->prepare($sql);
                $stmt->execute();
                $reg['data'] = $stmt->fetchAll();
                //var_dump($reg);
    //            $sql = "SELECT COUNT(*) as total FROM directorio WHERE CONCAT(upper(nombres),' ',upper(apellido)) LIKE '%".strtoupper($busqueda)."%' ORDER BY nombres ASC LIMIT 0,10";
                $sql = "SELECT COUNT(*) as total FROM directorio WHERE CONCAT(upper(nombres),' ',upper(apellido),' ',upper(sub),' ',upper(especialidad) ) LIKE '%".strtoupper($busqueda)."%' ORDER BY nombres ASC LIMIT 0,10";

                $em = $this->getDoctrine()->getManager();
                $stmt = $em->getConnection()->prepare($sql);
                $stmt->execute();
                $totales = $stmt->fetchAll();
                $reg['numRegistros'] = $totales[0]['total'];
    //            $reg['numRegistros'] = intval($reg['data']);
            }
            else{
                if($deptoId!=0){
    //                echo "busqueda depto";
                    $sql = "SELECT * FROM directorio WHERE estado=".$deptoId." AND CONCAT(upper(nombres),' ',upper(apellido),' ',upper(sub),' ',upper(especialidad)) LIKE '%".strtoupper($busqueda)."%' ORDER BY nombres ASC LIMIT ".$inicioRegistro.",".$longitud;
    //                $sql = "SELECT estado,ciudad,url,nombres,apellido,correoelectronico,telefono_fijo,telefono_movil,tipo,id,foto,GROUP_concat(sub) FROM directorio WHERE estado=".$deptoId." AND CONCAT(upper(nombres),' ',upper(apellido),' ',upper(sub)) LIKE '%".strtoupper($busqueda)."%' ORDER BY nombres ASC LIMIT ".$inicioRegistro.",".$longitud;
                    //echo $sql;
                    $em = $this->getDoctrine()->getManager();
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    $reg['data'] = $stmt->fetchAll();
                    //var_dump($reg);
    //                $sql = "SELECT COUNT(*) as total FROM directorio WHERE estado=".$deptoId." AND CONCAT(upper(nombres),' ',upper(apellido)) LIKE '%".strtoupper($busqueda)."%' ORDER BY nombres ASC LIMIT 0,10";
                    $sql = "SELECT COUNT(*) as total FROM directorio WHERE estado=".$deptoId." AND CONCAT(upper(nombres),' ',upper(apellido),' ',upper(sub),' ',upper(especialidad)) LIKE '%".strtoupper($busqueda)."%' ORDER BY nombres ASC LIMIT 0,10";

                    $em = $this->getDoctrine()->getManager();
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    $totales = $stmt->fetchAll();
                    $reg['numRegistros'] = $totales[0]['total'];
                }
                if($munId!=0){
    //                echo "busqueda mun";
                    $sql = "SELECT * FROM directorio WHERE estado=".$deptoId." AND ciudad=".$munId." AND CONCAT(upper(nombres),' ',upper(apellido),' ',upper(sub),' ',upper(especialidad)) LIKE '%".strtoupper($busqueda)."%' ORDER BY nombres ASC LIMIT ".$inicioRegistro.",".$longitud;
    //                $sql = "SELECT estado,ciudad,url,nombres,apellido,correoelectronico,telefono_fijo,telefono_movil,tipo,id,foto,GROUP_concat(sub) FROM directorio WHERE estado=".$deptoId." AND ciudad=".$munId." AND CONCAT(upper(nombres),' ',upper(apellido),' ', upper(sub)) LIKE '%".strtoupper($busqueda)."%' ORDER BY nombres ASC LIMIT ".$inicioRegistro.",".$longitud;
                    //echo $sql;
                    $em = $this->getDoctrine()->getManager();
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    $reg['data'] = $stmt->fetchAll();
                    //var_dump($reg);
    //                $sql = "SELECT COUNT(*) as total FROM directorio WHERE estado=".$deptoId." AND ciudad=".$munId." AND CONCAT(upper(nombres),' ',upper(apellido)) LIKE '%".strtoupper($busqueda)."%' ORDER BY nombres ASC LIMIT 0,10";
                    $sql = "SELECT COUNT(*) as total FROM directorio WHERE estado=".$deptoId." AND ciudad=".$munId." AND CONCAT(upper(nombres),' ',upper(apellido),' ',upper(sub),' ',upper(especialidad)) LIKE '%".strtoupper($busqueda)."%' ORDER BY nombres ASC LIMIT 0,10";

                    $em = $this->getDoctrine()->getManager();
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    $totales = $stmt->fetchAll();
                    $reg['numRegistros'] = $totales[0]['total'];
                }
            }
//        echo "\n número de registros: ".$reg['numRegistros']."\n";
//        if(count($reg['data']==0)){
//            $sql="SELECT estado.id AS estado,per.ctl_ciudad_id AS ciudad,urlp.url AS url,per.nombres AS nombres,per.apellido AS apellido,per.correoelectronico AS correoelectronico,per.telefono_fijo AS telefono_fijo,per.telefono_movil AS telefono_movil,'1' AS tipo,per.id AS id,fo.src AS foto FROM abg_persona per left outer join abg_persona_especialidad peresp on peresp.abg_persona_id = per.id join ctl_especialidad esp on esp.id=peresp.ctl_especialidad_id join ctl_subespecialidad subesp on subesp.abg_especialidad_id = esp.id join marvinvi_abg.abg_foto fo on((per.id = fo.abg_persona_id)) join marvinvi_abg.abg_url_personalizada urlp on((urlp.abg_persona_id = per.id)) left join marvinvi_abg.ctl_ciudad ciudad on((ciudad.id = per.ctl_ciudad_id)) left join marvinvi_abg.ctl_estado estado on((estado.id = ciudad.ctl_estado_id)) where ((fo.tipo_foto = 1) and (urlp.estado = 1) and (per.estado = 1)) order by per.id ASC";
//            
//        }
        //var_dump($reg);
    
            //El tipo perfil es para saber si es empresa o abogado
//            $dql = "SELECT per.nombres as nombres, per.apellido as apellido, per.correoelectronico as correoelectronico, per.telefonoFijo as telefonoFijo, per.telefonoMovil as telefonoMovil, "
//                    . " '' as sitioWeb, per.id, '1' as tipoPerfil "
//                    . "FROM DGAbgSistemaBundle:AbgPersona per "
//                    . "WHERE CONCAT(upper(per.nombres),' ',upper(per.apellido)) LIKE upper(:busqueda) ORDER BY per.nombres ASC, per.apellido ASC, per.correoelectronico ASC";
//            $em = $this->getDoctrine()->getManager();
//            $reg['data'] = $em->createQuery($dql)
//                        ->setParameter('busqueda','%'.$busqueda.'%')
//                        ->setFirstResult($inicioRegistro)
//                        ->setMaxResults($longitud)
//                        ->getResult();
            
            
//            $dql = "SELECT emp.nombreEmpresa as nombres, '' as apellido, emp.correoelectronico as correoelectronico, emp.telefono as telefonoFijo, emp.movil as telefonoMovil, "
//                    . "emp.sitioWeb as sitioWeb, emp.id, '2' as tipoPerfil "
//                    . "FROM DGAbgSistemaBundle:Ctlempresa emp "
//                    . "WHERE upper(emp.nombreEmpresa) LIKE upper(:busqueda) ORDER BY emp.nombreEmpresa ASC,emp.correoelectronico ASC";
//            $em = $this->getDoctrine()->getManager();
//            $reg2['data'] = $em->createQuery($dql)
//                        ->setParameter('busqueda','%'.$busqueda.'%')
//                        ->setFirstResult($inicioRegistro)
//                        ->setMaxResults($longitud)
//                        ->getResult();
            
            /*******************************/
            /* Esto es para encontrar todos los registros */
             
             
//            $dql = "SELECT per.nombres as nombres, per.apellido as apellido, per.correoelectronico as correoelectronico, per.telefonoFijo as telefonoFijo, per.telefonoMovil as telefonoMovil, "
//                    . " '' as sitioWeb, per.id, '1' as tipoPerfil "
//                    . "FROM DGAbgSistemaBundle:AbgPersona per "
//                    . "WHERE CONCAT(upper(per.nombres),' ',upper(per.apellido)) LIKE upper(:busqueda) ORDER BY per.nombres, per.apellido";
//            $em = $this->getDoctrine()->getManager();
//            $reg3['data'] = $em->createQuery($dql)
//                        ->setParameter('busqueda','%'.$busqueda.'%')
//                        ->getResult();
//            
//            
//            $dql = "SELECT emp.nombreEmpresa as nombres, '' as apellido, emp.correoelectronico as correoelectronico, emp.telefono as telefonoFijo, emp.movil as telefonoMovil, "
//                    . "emp.sitioWeb as sitioWeb, emp.id, '2' as tipoPerfil "
//                    . "FROM DGAbgSistemaBundle:Ctlempresa emp "
//                    . "WHERE upper(emp.nombreEmpresa) LIKE upper(:busqueda) ORDER BY emp.nombreEmpresa";
//            $em = $this->getDoctrine()->getManager();
//            $reg4['data'] = $em->createQuery($dql)
//                        ->setParameter('busqueda','%'.$busqueda.'%')
//                        ->getResult(); 
//             
             
             /*******************************/
            
//            foreach($reg2['data'] as $i =>$row){
//                //if(count($reg['data'])<10)
//                    array_push($reg['data'],$reg2['data'][$i]);
//                
//            }
//            
//            
//            
//            
//            foreach($reg4['data'] as $i =>$row){
//            
//                array_push($reg3['data'],$reg4['data'][$i]);
//                
//            }
            
            
            
//            $reg['numRegistros']= count($reg3['data']);
            if($reg['numRegistros']>10){
                $reg['pages']=floor(($reg['numRegistros']/10))+1;
            }
            else{
                $reg['pages']=1;
            }
            
//            echo "\n número de páginas: ".$reg['pages']."\n";
//            $k = 0;
//            var_dump($reg['data']);
//            foreach($reg['data'] as $i =>$row){
//                
////                if($i < $inicioRegistro )
////                    unset($reg['data'][$i]);
//                if( $i>($longitud-1) )
//                    unset($reg['data'][$i]);
//                 
//            }
//            var_dump($reg['data']);
//            die();
            //sort($reg['data']);
            //$reg['numRegistros']= count($reg['data']);
//            var_dump($reg2['data']);
            //$reg['numRegistros']= count($reg['data']);
            //var_dump(count($reg['data']));
            //die();
            
            $reg['filtroRegistros'] = count($reg['data']);
            $esp = array();
            
            $i=0;
            
//            if(count($reg['data'])!=0){
//                $reg['data'][$i]['especialidades']=array();
////                echo "totales: ".count($reg['data']);
//                foreach($reg['data'] as $i =>$row){
//                    //var_dump($reg['data'][intval($i)+1]);
////                    echo "-".$i;
////                    
////                    if($reg['data'][$i]['id']==$reg['data'][intval($i)+1]['id'] && $i<(count($reg['data'])-1)){
////                        echo "sdcd";
////                        unset ($reg['data'][$i]);
////                    }
////                    else{
////                        echo $row['id'];
//                        $esp = array();
//                        $reg['data'][$i]['especialidades']=array();
//                        //var_dump($reg['data']);
//                        $dql = "SELECT esp.nombreEspecialidad FROM DGAbgSistemaBundle:AbgPersonaEspecialida subper "
//
//                            . "JOIN subper.ctlEspecialidad esp "
//                            . "JOIN subper.abgPersona per WHERE subper.abgPersona=:idPersona";
//                        $em = $this->getDoctrine()->getManager();
//                        $especialidades = $em->createQuery($dql)
//                               ->setParameter('idPersona',$row['id'])
//                               ->getResult();
//                        
//                        foreach($especialidades as $row2){
//                            array_push($esp,$row2);
//                        }
//                        //var_dump($esp);
//                        if(count($especialidades)==0){
//                            //array_push($row['especidalidades'], 'N/A');
//                            $reg['data'][$i]['especialidades']['nombreEspecialidad'] = "N/A";
//                        }
//                        else{
//                            //array_push($reg['data'][$i]['especialidades'], $esp);
//                            $reg['data'][$i]['especialidades']=$esp;
//                        }
//
//    //                    $dql = "SELECT foto.src FROM DGAbgSistemaBundle:AbgFoto foto "
//    //                            . "JOIN foto.abgPersona per "
//    //                            . "WHERE per.id=:idPersona AND foto.tipoFoto=1";
//    //                    $em = $this->getDoctrine()->getManager();
//    //                    $foto = $em->createQuery($dql)
//    //                           ->setParameter('idPersona',$row['id'])
//    //                           ->getResult();
//                       //var_dump($foto);
//                       //die();
//    //                    if(count($foto)!=0){
//    //                        $reg['data'][$i]['fotoPerfil']=$foto[0]['src'];
//    //                    }
//                       //var_dump($reg['data']);
//                       //die();
//
//                        $i++;
////                    }
//                }
//            }
            
            //var_dump($reg);
            //die();  
            //var_dump($reg['data'][0]['especialidades'][0]['nombreEspecialidad']);
            //var_dump($reg['data'][0]['especialidades'][1]['nombreEspecialidad']);
            //die();

        }
        else{
            $reg['numRegistros']= 0;
            $reg['pages']=0;
            $reg['filtroRegistros']= 0;
            $reg['data']=array();
            $reg['pages']=0;
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
    public function municipiosBusquedaAction(Request $request)
    {
        
        
        $deptoId = $request->get('deptoId');
        $munId = $request->get('munId');
        
        
                          
        $response = new JsonResponse();

        $em = $this->getDoctrine()->getEntityManager();
        //$municipios['regs'] = $em->getRepository('DGAbgSistemaBundle:CtlCiudad')->findBy(array('ctlEstado'=>intval($deptoId)));
//        $dql
        $dql = "SELECT c.id as id,c.nombreCiudad as nombre FROM DGAbgSistemaBundle:CtlCiudad c "
                        . "JOIN c.ctlEstado est WHERE est.id=:deptoId";
        $municipios = $em->createQuery($dql)
                           ->setParameter('deptoId',$deptoId)
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

        $fecha = new \DateTime ('now');
        
        $promotions = $em->createQuery($dql)
                          ->setParameter('posicion',$posicion)
                          ->setParameter('fecha', $fecha)
                          ->getResult();  
        
        if(!empty($promotions)){
            $max = count($promotions);

            if($max > 20){
                while ($i < 20){
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
     * Creates a new CtlCiudad entity.
     *
     * @Route("/new", name="ctlciudad_new")
     * @Method({"GET", "POST"})
     */
//    public function newAction(Request $request)
//    {
//        $ctlCiudad = new CtlCiudad();
//        $form = $this->createForm('DGAbgSistemaBundle\Form\CtlCiudadType', $ctlCiudad);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($ctlCiudad);
//            $em->flush();
//
//            return $this->redirectToRoute('ctlciudad_show', array('id' => $ctlCiudad->getId()));
//        }
//
//        return $this->render('ctlciudad/new.html.twig', array(
//            'ctlCiudad' => $ctlCiudad,
//            'form' => $form->createView(),
//        ));
//    }

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
