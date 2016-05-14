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
     * @Route("/", name="directorio_index")
     * @Method("GET")
     */
    public function indexAction()
    { 
       $em = $this->getDoctrine()->getManager();

        $ctlCiudads = $em->getRepository('DGAbgSistemaBundle:CtlEstado')->findAll();
        $dql = "SELECT c FROM DGAbgSistemaBundle:CtlEstado c "
                . "INNER JOIN c.ctlPais p WHERE p.estado=1";
        
        $ctlCiudads = $em->createQuery($dql)
                   //->setParameter('id',$id)
                   ->getResult();
        
        return $this->render('directorio/directorio.html.twig', array(
            'deptos' => $ctlCiudads,
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
//        $inicioRegistro = ($paginaActual*10)-10;
        $inicioRegistro = ($longitud * ($paginaActual - 1));
                          
        $response = new JsonResponse();
//        $start = $request->query->get('start');
//        $draw = $request->query->get('draw');
//        $longitud = $request->query->get('length');
//        $busqueda = $request->query->get('search');
        //var_dump($inicioRegistro);
        //var_dump($longitud);
        
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
            
        
        
        $sql = "SELECT * FROM directorio WHERE CONCAT(upper(nombres),' ',upper(apellido)) LIKE '%".strtoupper($busqueda)."%' ORDER BY nombres ASC LIMIT ".$inicioRegistro.",".$longitud;
        //echo $sql;
        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $reg['data'] = $stmt->fetchAll();
        //var_dump($reg);
        
        $sql = "SELECT COUNT(*) as total FROM directorio WHERE CONCAT(upper(nombres),' ',apellido) LIKE '%".strtoupper($busqueda)."%' ORDER BY nombres ASC LIMIT 0,10";
        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $totales = $stmt->fetchAll();
        $reg['numRegistros'] = $totales[0]['total'];
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
            
            $reg['pages']=floor(($reg['numRegistros']/10))+1;
            
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
            
            if(count($reg['data'])!=0){
                $reg['data'][$i]['especialidades']=array();
                foreach($reg['data'] as $i =>$row){
                    //var_dump($row);
                    $reg['data'][$i]['especialidades']=array();
                    //var_dump($reg['data']);
                    $dql = "SELECT esp.nombreEspecialidad FROM DGAbgSistemaBundle:AbgPersonaEspecialida subper "
                        
                        . "JOIN subper.ctlEspecialidad esp "
                        . "JOIN subper.abgPersona per WHERE per.id=:idPersona";
                    $em = $this->getDoctrine()->getManager();
                    $especialidades = $em->createQuery($dql)
                           ->setParameter('idPersona',$row['id'])
                           ->getResult();

                    foreach($especialidades as $row2){
                        array_push($esp,$row2);
                    }
                    if(count($especialidades)==0){
                        //array_push($row['especidalidades'], 'N/A');
                        $reg['data'][$i]['especialidades']['nombreEspecialidad'] = "N/A";
                    }
                    else{
                        //array_push($reg['data'][$i]['especialidades'], $esp);
                        $reg['data'][$i]['especialidades']=$esp;
                    }

//                    $dql = "SELECT foto.src FROM DGAbgSistemaBundle:AbgFoto foto "
//                            . "JOIN foto.abgPersona per "
//                            . "WHERE per.id=:idPersona AND foto.tipoFoto=1";
//                    $em = $this->getDoctrine()->getManager();
//                    $foto = $em->createQuery($dql)
//                           ->setParameter('idPersona',$row['id'])
//                           ->getResult();
                   //var_dump($foto);
                   //die();
//                    if(count($foto)!=0){
//                        $reg['data'][$i]['fotoPerfil']=$foto[0]['src'];
//                    }
                   //var_dump($reg['data']);
                   //die();

                    $i++;
                }
            }
            
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
