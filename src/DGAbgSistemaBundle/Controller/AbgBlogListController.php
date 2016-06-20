<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGAbgSistemaBundle\Entity\AbgEntrada;
use DGAbgSistemaBundle\Entity\AbgImagenBlog;
use DGAbgSistemaBundle\Entity\CtlCategoriaBlog;

/**
 * BlogList controller.
 *
 * @Route("/bloglist")
 */
class AbgBlogListController extends Controller {

    /**
     * Muestra un mensaje de entra ingresada correctamente.
     *
     * @Route("/", name="bloglist", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $ctlCategoriasBlog = $em->getRepository('DGAbgSistemaBundle:CtlCategoriaBlog')->findAll();
        $prom = $this->busquedaPublicidad(1);
        $prom2 = $this->busquedaPublicidad(2);
        $prom3 = $this->busquedaPublicidad(3);

        return $this->render('DGAbgSistemaBundle:blog:bloglist.html.twig', array('ctlCategoriasBlog' => $ctlCategoriasBlog,
                    'prom1' => $prom,
                    'prom2' => $prom2,
                    'prom3' => $prom3));
    }

    /**
     * 
     *
     * @Route("/admin/lista_entrada", name="lista_entrada")
     */
    public function listaentradaAction(Request $request) {

        $inicio = $request->get('inicio');
        $longitud = $request->get('longitud');
        $paginaActual = $request->get('paginaActual');
        $inicioRegistro = ($longitud * ($paginaActual - 1));

        $response = new JsonResponse();

        $em = $this->getDoctrine()->getEntityManager();
        //$entradas = $em->getRepository('DGAbgSistemaBundle:AbgEntrada')->findAll();
        //$entradasTotal = count($entradas);
        //$empresaTotal = $em->getRepository('DGAbgSistemaBundle:CtlEmpresa')->findBy(array('estado'=>1));


        $reg['inicio'] = $inicio++;
        $reg['longitud'] = $longitud;
        $reg['paginaActual'] = $paginaActual;
        $reg['inicioRegistro'] = $inicioRegistro;

        $reg['data'] = array();
        $reg['numRegistros'] = 0;
        //if($busqueda!=''){        

        $sql = "SELECT COUNT(*) as total FROM abg_entrada";
        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $totales = $stmt->fetchAll();
        $reg['numRegistros'] = $totales[0]['total'];

        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT en.id as identrada, en.tituloEntrada as titulo, en.fecha as fecha, en.contenido as contenido, "
                . " im.src as src, ctlblog.nombreCategoria as catblognombre, per.nombres as nombres, per.apellido as apellidos "
                . "FROM DGAbgSistemaBundle:AbgImagenBlog im "
                . "JOIN im.abgEntrada en "
                . "JOIN en.abgCategoriaEntradaId ctlblog "
                . "JOIN en.ctlUsuario us "
                . "JOIN us.rhPersona per "
                . "ORDER BY en.id DESC";

        $reg['data'] = $em->createQuery($dql)
                ->setFirstResult($inicioRegistro)
                ->setMaxResults($longitud)
                ->getResult();
        $entradasTotal = count($reg['data']);

        //$reg['numRegistros'] = $entradasTotal;


        $reg['pages'] = floor(($reg['numRegistros'] / 10)) + 1;
        //$reg['pages'] = floor(($reg['numRegistros']/10))+1;

        $reg['filtroRegistros'] = count($reg['data']);

        $i = 0;



        $response->setData($reg);

        //die();
        return $response;
        //return new Response(json_encode($reg));
    }

    //Esta funcion del controlador es para cuando se elige una categoria
    /*     * ****************************************************************************************************************** */

    /**
     * 
     *
     * @Route("/admin/lista_entrada_xcat/get", name="lista_entrada_xcat")
     */
    public function listaentradaxcatAction(Request $request) {

        $inicio = $request->get('inicio');
        $longitud = $request->get('longitud');
        $paginaActual = $request->get('paginaActual');
        $inicioRegistro = ($longitud * ($paginaActual - 1));
        $idcatselect = $request->get('idcat');
        //var_dump($idcatselect);
        //die();


        $response = new JsonResponse();

        $em = $this->getDoctrine()->getEntityManager();
        //$entradas = $em->getRepository('DGAbgSistemaBundle:AbgEntrada')->findAll();
        //$entradasTotal = count($entradas);
        //$empresaTotal = $em->getRepository('DGAbgSistemaBundle:CtlEmpresa')->findBy(array('estado'=>1));


        $reg['inicio'] = $inicio++;
        $reg['longitud'] = $longitud;
        $reg['paginaActual'] = $paginaActual;
        $reg['inicioRegistro'] = $inicioRegistro;

        $reg['data'] = array();
        $reg['numRegistros'] = 0;
        //if($busqueda!=''){        

        if ($idcatselect) {//Esta pate solo se ejecuta al seleccionar la categoria
            $sql = "SELECT COUNT(*) as total FROM abg_entrada en "
                    . "inner join ctl_categoria_blog cb on en.abg_categoria_entrada_id=cb.id "
                    . "where cb.id = $idcatselect";
            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $totales = $stmt->fetchAll();
            $reg['numRegistros'] = $totales[0]['total'];
        }


//        $dql = "SELECT en.id as identrada, en.tituloEntrada as titulo, en.fecha as fecha, en.contenido as contenido, im.src as src FROM DGAbgSistemaBundle:AbgEntrada en, DGAbgSistemaBundle:AbgImagenBlog im " 
//                        . "ORDER BY en.id ASC ";
//                    $reg['data'] = $em->createQuery($dql)
//                            
//                            ->setFirstResult($inicioRegistro)
//                            ->setMaxResults($longitud)
//                            ->getResult();
//                                         
//            $entradasTotal = count($reg['data']);
        //if($idcatselect){
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT en.id as identrada, en.tituloEntrada as titulo, en.fecha as fecha, en.contenido as contenido, im.src as src, ctlblog.id as idcat, ctlblog.nombreCategoria as catblognombre, per.nombres as nombres, per.apellido as apellidos "
                . "FROM DGAbgSistemaBundle:AbgImagenBlog im "
                . "JOIN im.abgEntrada en "
                . "JOIN en.abgCategoriaEntradaId ctlblog "
                . "JOIN en.ctlUsuario us "
                . "JOIN us.rhPersona per "
                . "WHERE ctlblog.id =:idcat "
                . "ORDER BY en.id ASC";

        $reg['data'] = $em->createQuery($dql)
                ->setFirstResult($inicioRegistro)
                ->setMaxResults($longitud)
                ->setParameter('idcat', $idcatselect)
                ->getResult();
        //var_dump($reg['data']);
        //die();
        $entradasTotal = count($reg['data']);
        //}
        //$reg['numRegistros'] = $entradasTotal;



        $reg['pages'] = floor(($reg['numRegistros'] / 10)) + 1;
        //$reg['pages'] = floor(($reg['numRegistros']/10))+1;

        $reg['filtroRegistros'] = count($reg['data']);

        $i = 0;



        $response->setData($reg);

        //die();
        return $response;
        //return new Response(json_encode($reg));
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

}
