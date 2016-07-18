<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DGAbgSistemaBundle\Entity\AbgEntrada;
use DGAbgSistemaBundle\Entity\CtlUsuario;
use DGAbgSistemaBundle\Entity\AbgImagenBlog;
use DGAbgSistemaBundle\Entity\AbgPregunta;
use Symfony\Component\HttpFoundation\Response;
use DGAbgSistemaBundle\Form\AbgPersonaType;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * AbgEntrada controller.
 *
 * @Route("/preguntascentro")
 */
class AbgCentroPreguntasController extends Controller {

    /**
     * Lists all Preguntas entities.
     *
     * @Route("/", name="pregunta_index")
     * @Method("GET")
     */
    public function indexAction() {

        $em = $this->getDoctrine()->getManager();

        $preguntas = $em->getRepository('DGAbgSistemaBundle:AbgPregunta')->findBy(array('estado' => 1), array('id' => 'DESC'), 10, 0);
        $rsm = new ResultSetMapping();

        $sql = "select per.nombres as nombres, per.apellido as apellidos, uper.url as url, count(pre.respuesta) as totalrespuestas
                from abg_respuesta_pregunta pre inner join ctl_usuario usu on pre.ctl_usuario_id = usu.id
                inner join abg_persona per on usu.rh_persona_id = per.id
                inner join abg_url_personalizada uper on uper.abg_persona_id = per.id and uper.estado=1
                group by per.nombres, per.apellido, uper.url
                order by count(pre.respuesta) desc
                limit 0, 10";

        $rsm->addScalarResult('nombres', 'nombres');
        $rsm->addScalarResult('apellidos', 'apellidos');
        $rsm->addScalarResult('src', 'src');
        $rsm->addScalarResult('url', 'url');
        $rsm->addScalarResult('totalrespuestas', 'totalrespuestas');

        $topUsuarios = $em->createNativeQuery($sql, $rsm)
                ->getResult();

        $prom = $this->busquedaPublicidad(1);
        $prom2 = $this->busquedaPublicidad(2);
        $prom3 = $this->busquedaPublicidad(3);
        $prom4 = $this->busquedaPublicidad(4);


        $dqlNresouestas = "SELECT COUNT(pre.respuesta) AS totalrespuestas "
                . " FROM DGAbgSistemaBundle:AbgRespuestaPregunta pre";
        $Nrespuestas = $em->createQuery($dqlNresouestas)->getArrayResult();
        
        $dqlNpreguntas = "select count(pre.id) as totalpreguntas
                from DGAbgSistemaBundle:AbgPregunta pre";
        $Npreguntas = $em->createQuery($dqlNpreguntas)->getArrayResult();

        $dqlNabg = "select count(pre.id) as totalpersonas
                from DGAbgSistemaBundle:AbgPersona pre WHERE pre.id>1 ";
        $NAbg = $em->createQuery($dqlNabg)->getArrayResult();

        $sql_preguntas_resiente = "SELECT  pre.id, usu.id,CONCAT(per.nombres, '  ', per.apellido) as nombres, uper.url as url, fot.src AS src, "
                . " pre.respuesta AS respuesta, pre.fecha_respuesta, pre.abg_pregunta AS idPregunta, per.estado AS estado, preg.pregunta AS pregunta "
                . " FROM abg_pregunta preg "
                . "JOIN abg_respuesta_pregunta pre ON preg.id=pre.abg_pregunta "
                . " JOIN ctl_usuario usu ON pre.ctl_usuario_id = usu.id "
                . " JOIN abg_persona per ON usu.rh_persona_id = per.id "
                . " JOIN abg_url_personalizada uper ON uper.abg_persona_id = per.id AND uper.estado=1 "
                . "  JOIN abg_foto fot "
                . " ON fot.abg_persona_id=per.id AND fot.tipo_foto=0 AND fot.tipo_foto <> 5 "
                . " ORDER BY  pre.id desc "
                . " LIMIT 0, 4 ";
        $stm = $this->container->get('database_connection')->prepare($sql_preguntas_resiente);
        $stm->execute();
        $ultimas_prteguntas = $stm->fetchAll();

        $tiemposRespuesta = array();
        if ($ultimas_prteguntas != NULL) {
            foreach ($ultimas_prteguntas as $row) {
                array_push($tiemposRespuesta, $this->tiempo_transcurrido($row['fecha_respuesta']));
            }
        } else {
            $tiemposRespuesta = NULL;
        }

        //   return $this->render('centropreg/indexcentro.html.twig', array('prom1' => $prom,
        return $this->render('centropreg/preguntas_landing.html.twig', array('prom1' => $prom,
                    'prom2' => $prom2,
                    'prom3' => $prom3,
                    'prom4' => $prom4,
                    'preguntas' => $preguntas,
                    'top' => $topUsuarios,
                    'ultimas_prteguntas' => $ultimas_prteguntas,
                    'tiemposRespuesta' => $tiemposRespuesta,
                    'NAgb' => $NAbg[0]['totalpersonas'],
                    'Npreguntas' => $Npreguntas[0]['totalpreguntas'],
                    'Nrespuestas' => $Nrespuestas[0]['totalrespuestas']));
    }

    /**
     * Respuesta
     *
     * @Route("/respuestas", name="respuestas_publicas", options={"expose"=true})
     * @Method("GET")
     */
    public function RespuestasPublicasAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $preguntas = $em->getRepository('DGAbgSistemaBundle:AbgPregunta')->findBy(array('estado' => 1), array('id' => 'DESC'), 10, 0);
        $rsm = new ResultSetMapping();

        $sql = "select per.nombres as nombres, per.apellido as apellidos, uper.url as url, count(pre.respuesta) as totalrespuestas
                from abg_respuesta_pregunta pre inner join ctl_usuario usu on pre.ctl_usuario_id = usu.id
                inner join abg_persona per on usu.rh_persona_id = per.id
                inner join abg_url_personalizada uper on uper.abg_persona_id = per.id and uper.estado=1
                group by per.nombres, per.apellido, uper.url
                order by count(pre.respuesta) desc
                limit 0, 10";

        $rsm->addScalarResult('nombres', 'nombres');
        $rsm->addScalarResult('apellidos', 'apellidos');
        $rsm->addScalarResult('src', 'src');
        $rsm->addScalarResult('url', 'url');
        $rsm->addScalarResult('totalrespuestas', 'totalrespuestas');

        $topUsuarios = $em->createNativeQuery($sql, $rsm)
                ->getResult();

        $prom = $this->busquedaPublicidad(1);
        $prom2 = $this->busquedaPublicidad(2);
        $prom3 = $this->busquedaPublicidad(3);
        $prom4 = $this->busquedaPublicidad(4);


        $dqlNresouestas = "SELECT COUNT(pre.respuesta) AS totalrespuestas "
                . " FROM DGAbgSistemaBundle:AbgRespuestaPregunta pre";
        $Nrespuestas = $em->createQuery($dqlNresouestas)->getArrayResult();

        $dqlNpreguntas = "select count(pre.id) as totalpreguntas
                from DGAbgSistemaBundle:AbgPregunta pre";
        $Npreguntas = $em->createQuery($dqlNpreguntas)->getArrayResult();

        $dqlNabg = "select count(pre.id) as totalpersonas
                from DGAbgSistemaBundle:AbgPersona pre ";
        $NAbg = $em->createQuery($dqlNabg)->getArrayResult();



        $sql_preguntas_resiente = "SELECT  pre.id, usu.id,CONCAT(per.nombres, '  ', per.apellido) as nombres, uper.url as url, fot.src AS src, "
                . " pre.respuesta AS respuesta, pre.fecha_respuesta, per.estado AS estado, preg.pregunta AS pregunta, preg.id AS idPreg "
                . " FROM abg_pregunta preg "
                . "JOIN abg_respuesta_pregunta pre ON preg.id=pre.abg_pregunta "
                . " JOIN ctl_usuario usu ON pre.ctl_usuario_id = usu.id "
                . " JOIN abg_persona per ON usu.rh_persona_id = per.id "
                . " JOIN abg_url_personalizada uper ON uper.abg_persona_id = per.id AND uper.estado=1 "
                . "  JOIN abg_foto fot "
                . " ON fot.abg_persona_id=per.id AND fot.tipo_foto=0 AND fot.tipo_foto <> 5 "
                . " ORDER BY  pre.id desc "
                . " LIMIT 0, 8 ";
        $stm = $this->container->get('database_connection')->prepare($sql_preguntas_resiente);
        $stm->execute();
        $ultimas_preguntas = $stm->fetchAll();

        $tiemposRespuesta = array();
        if ($ultimas_preguntas != NULL) {
            foreach ($ultimas_preguntas as $row) {
                array_push($tiemposRespuesta, $this->tiempo_transcurrido($row['fecha_respuesta']));
            }
        } else {
            $tiemposRespuesta = NULL;
        }
        $sqlTop10 = "SELECT CONCAT(per.nombres, '  ', per.apellido) as nombres, uper.url as url, "
                . " count(pre.respuesta) as totalrespuestas, fot.src AS src, per.estado AS estado"
                . " FROM abg_respuesta_pregunta pre inner join ctl_usuario usu on pre.ctl_usuario_id = usu.id "
                . " INNER JOIN abg_persona per on usu.rh_persona_id = per.id "
                . " INNER JOIN abg_url_personalizada uper on uper.abg_persona_id = per.id AND uper.estado=1 "
                . " JOIN abg_foto fot  "
                . " ON fot.abg_persona_id=per.id AND fot.tipo_foto=0 AND fot.tipo_foto <> 5 "
                . " group by per.nombres, per.apellido, uper.url "
                . " order by count(pre.respuesta) desc "
                . " limit 0, 10";

        $stm = $this->container->get('database_connection')->prepare($sqlTop10);
        $stm->execute();
        $top10 = $stm->fetchAll();

        return $this->render('centropreg/respuestas.html.twig', array(
                    'prom1' => $prom,
                    'prom2' => $prom2,
                    'prom3' => $prom3,
                    'prom4' => $prom4,
                    'ultimas_preguntas' => $ultimas_preguntas,
                    'tiemposRespuesta' => $tiemposRespuesta,
                    'NAgb' => $NAbg[0]['totalpersonas'],
                    'Npreguntas' => $Npreguntas[0]['totalpreguntas'],
                    'Nrespuestas' => $Nrespuestas[0]['totalrespuestas'],
                    'busquedagenral' => $request->get('txtgeneral'),
                    'busquedaDept' => $request->get('txtlugar'),
                    'top10' => $top10
        ));
    }

    /**
     * Lists all CtlCiudad entities.
     *
     * @Route("/preguntaPublica", name="busqueda_pregunta_publica", options={"expose"=true}))
     * @Method("GET")
     */
    public function BusquedaPreguntaPublicaAction(Request $request) {

        $busqueda = $request->query->get('query');
        $page = $request->query->get('page');

        $rsm = new ResultSetMapping();
        $em = $this->getDoctrine()->getEntityManager();

        $sql = "SELECT DISTINCT "
                . " CASE  "
                . " WHEN CONCAT(upper(per.nombres),' ',upper(per.apellido)) LIKE '%" . strtoupper($busqueda) . "%' "
                . " THEN CONCAT(upper(per.nombres),' ',upper(per.apellido)) "
                . " WHEN upper(sub.abg_subespecialidadcol) LIKE '%" . strtoupper($busqueda) . "%'"
                . " THEN sub.abg_subespecialidadcol "
                . " WHEN upper(esp.nombre_especialidad) LIKE '%" . strtoupper($busqueda) . "%'"
                . " THEN esp.nombre_especialidad"
                . " END AS value "
                . " FROM ctl_usuario usu "
                . " JOIN abg_persona per ON usu.rh_persona_id = per.id "
                . " JOIN abg_persona_especialidad pesp ON pesp.abg_persona_id=per.id "
                . " JOIN ctl_especialidad  esp ON pesp.ctl_especialidad_id=esp.id "
                . " JOIN ctl_subespecialidad sub "
                . " ON sub.abg_especialidad_id=esp.id "
                . " And CONCAT(upper(per.nombres),' ',upper(per.apellido),' ',upper(sub.abg_subespecialidadcol),' ',upper(esp.nombre_especialidad)) "
                . " LIKE '%" . strtoupper($busqueda) . "%' "
                . " ORDER BY value DESC";

        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('value', 'value');
        $rsm->addScalarResult('data', 'data');
        $abogado['suggestions'] = $em->createNativeQuery($sql, $rsm)
                ->getResult();

        return new Response(json_encode($abogado));
    }

    /**
     * Lists all CtlCiudad entities.
     *
     * @Route("/depto_ciudad", name="depto_ciudad", options={"expose"=true}))
     * @Method("GET")
     */
    public function DeptoCiudadAction(Request $request) {
        $busqueda = $request->query->get('query');
        $page = $request->query->get('page');

        $rsm = new ResultSetMapping();
        $em = $this->getDoctrine()->getEntityManager();

        $sql = " SELECT ciu.id AS id, concat(ciu.nombre_ciudad,', ',est.nombre_estado)as value "
                . " FROM ctl_estado est "
                . " JOIN ctl_ciudad ciu ON ciu.ctl_estado_id= est.id "
                . "  AND CONCAT(upper(ciu.nombre_ciudad),' ',upper(est.nombre_estado)) "
                . " LIKE '%" . strtoupper($busqueda) . "%' "
                . " ORDER BY ciu.nombre_ciudad,est.nombre_estado desc ";
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('value', 'value');
        $rsm->addScalarResult('data', 'data');
        $abogado['suggestions'] = $em->createNativeQuery($sql, $rsm)
                ->getResult();

        return new Response(json_encode($abogado));
    }

    /**
     * Respuesta
     *
     * @Route("/respuestacentro", name="respuesta_centro")
     * @Method("GET")
     */
    public function respuestacentroAction(Request $request) {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $pregunta = $em->getRepository('DGAbgSistemaBundle:AbgPregunta')->find($id);

        if ($pregunta->getRespuesta() != '') {
            $foto = $em->getRepository('DGAbgSistemaBundle:AbgFoto')->findOneBy(array("abgPersona" => $pregunta->getCtlUsuario()->getRhPersona()));

            $fechaRespuesta = $this->tiempo_transcurrido($pregunta->getFechaRespuesta());
        } else {
            $foto = null;
        }

        $rsm = new ResultSetMapping();

        $sql = "select per.nombres as nombres, per.apellido as apellidos, foto.src as src, uper.url as url, count(pre.respuesta) as totalrespuestas, per.estado AS estado
                from abg_pregunta pre inner join ctl_usuario usu on pre.ctl_usuario_id = usu.id
                inner join abg_persona per on usu.rh_persona_id = per.id
                inner join abg_foto foto on foto.abg_persona_id = per.id
                inner join abg_url_personalizada uper on uper.abg_persona_id = per.id and uper.estado=1
                group by per.nombres, per.apellido, foto.src, uper.url
                order by count(pre.respuesta) desc
                limit 0, 10";

        $rsm->addScalarResult('nombres', 'nombres');
        $rsm->addScalarResult('apellidos', 'apellidos');
        $rsm->addScalarResult('src', 'src');
        $rsm->addScalarResult('url', 'url');
        $rsm->addScalarResult('totalrespuestas', 'totalrespuestas');

        $topUsuarios = $em->createNativeQuery($sql, $rsm)
                ->getResult();

        $prom = $this->busquedaPublicidad(1);
        $prom2 = $this->busquedaPublicidad(2);
        $prom3 = $this->busquedaPublicidad(3);
        $prom4 = $this->busquedaPublicidad(4);

        return $this->render('centropreg/respuestacentro.html.twig', array('foto' => $foto, 'prom1' => $prom, 'prom2' => $prom2, 'prom3' => $prom3, 'prom4' => $prom4, 'pregunta' => $pregunta, 'top' => $topUsuarios));
    }

    /**
     * Respuesta
     *
     * @Route("/respuestas_pregunta/{id}", name="respuestas_pregunta", options={"expose"=true}))
     * @Method("GET")
     */
    public function RespuestasPreguntaAction($id) {
        $id = $id;//$request->get('id');
        $em = $this->getDoctrine()->getManager();
        $pregunta = $em->getRepository('DGAbgSistemaBundle:AbgPregunta')->find($id);
        $respuestas = $em->getRepository('DGAbgSistemaBundle:AbgRespuestaPregunta')->findBy(array('abgPregunta' => $pregunta), array('id' => 'DESC'));

        
        $sql = "SELECT * FROM pregunta WHERE idPregunta=".$id;
     $stm = $this->container->get('database_connection')->prepare($sql);
        $stm->execute();
        $respuestas = $stm->fetchAll();
       

        $fotos = array();
        $tiemposRespuesta = array();
        if ($respuestas != NULL) {
            foreach ($respuestas as $row) {
                $foto = $em->getRepository('DGAbgSistemaBundle:AbgFoto')->findOneBy(array("abgPersona" =>$row['idPersona'] ));

                array_push($fotos, $foto);
                array_push($tiemposRespuesta, $this->tiempo_transcurrido($row['fecha_respuesta']));
            }
        } else {
            $tiemposRespuesta = NULL;
        }
        //var_dump($fotos);
        $rsm = new ResultSetMapping();

        
        

        $tiempo = $this->tiempo_transcurrido($pregunta->getFechaPregunta()->format('Y-m-d H:i:s'));

        $prom = $this->busquedaPublicidad(1);
        $prom2 = $this->busquedaPublicidad(2);
        $prom3 = $this->busquedaPublicidad(3);
        $prom4 = $this->busquedaPublicidad(4);

        return $this->render('centropreg/pregunta.html.twig', array('tiempo' => $tiempo,
                    'tiemposRespuesta' => $tiemposRespuesta,
                    'fotos' => $fotos, 'prom1' => $prom,
                    'prom2' => $prom2, 'prom3' => $prom3,
                    'prom4' => $prom4, 'pregunta' => $pregunta,
                    'respuestas' => $respuestas));
    }        

    /**
     * 
     *
     * @Route("/busqueda/data/pregunta", name="busqueda_pregunta")
     */
    public function databusquedaAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $inicio = $request->get('inicio');
        $longitud = $request->get('longitud');
        
        $paginaActual = $request->get('paginaActual');


        $criterio = "";
 

        if ($request->get('busquedaDept') !=='' && $request->get('busqueda') !== '') {
            $idCiudad = $request->get('busquedaDept');
            /*$municipio = $em->getRepository('DGAbgSistemaBundle:CtlCiudad')->findByNombreCiudad(trim($busquedaDept[0]));
            $idCiudad = $municipio[0]->getId();*/
            $reg['Busquedageneral'] = $idCiudad;
            $criterio = "WHERE CONCAT(upper(nombres),' ',upper(subEspecialidad),' ',upper(especialidad),'',upper(pregunta),'',upper(respuesta)) "
                            ." LIKE '%".trim(strtoupper($request->get('busqueda')))."%' AND ciudad LIKE '%" . trim(strtoupper($idCiudad))."%'";
        }

       else if ($request->get('busqueda') !== '' && $request->get('busquedaDept') =='') {
             $criterio = "WHERE CONCAT(upper(nombres),' ',upper(subEspecialidad),' ',upper(especialidad),'',upper(pregunta),'',upper(respuesta)) "
                            ." LIKE '%".trim(strtoupper($request->get('busqueda')))."%'";
            $reg['Busquedageneral'] = $request->get('busqueda');
        }
        else
        {
            $busquedaDept = $request->get('busquedaDept');
         /*   $municipio = $em->getRepository('DGAbgSistemaBundle:CtlCiudad')->findByNombreCiudad(trim($busquedaDept[0]));
            $idCiudad = $municipio[0]->getId();*/
             $criterio = "WHERE ciudad  LIKE '%" . trim(strtoupper($busquedaDept))."%'";
              $reg['Busquedageneral'] = $busquedaDept;
        }
        $inicioRegistro = ($longitud * ($paginaActual - 1));

        $response = new JsonResponse();


        $preguntasTotal = $em->getRepository('DGAbgSistemaBundle:AbgRespuestaPregunta')->findAll();

        $reg['inicio'] = $inicio++;
        $reg['longitud'] = $longitud;
        $reg['paginaActual'] = $paginaActual;
        $reg['inicioRegistro'] = $inicioRegistro;
        $reg['data'] = array();
        $reg['numRegistros'] = 0;

        $sql = "SELECT * FROM pregunta ".$criterio
                 . " LIMIT " . $inicioRegistro . "," . $longitud;

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $ultimas_preguntas = $stmt->fetchAll();
        $reg['data'] = $ultimas_preguntas;
        
    

        $tiemposRespuesta = array();
        if ($ultimas_preguntas != NULL) {
            foreach ($ultimas_preguntas as $row) {
                array_push($tiemposRespuesta, $this->tiempo_transcurrido($row['fecha_respuesta']));
            }
        } else {
            $tiemposRespuesta = NULL;
        }

        $reg['tiemposRespuesta'] = $tiemposRespuesta;

        $sql = "SELECT COUNT(*) as total FROM pregunta ".$criterio;

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $totales = $stmt->fetchAll();


        $reg['numRegistros'] = $totales[0]['total'];


        $reg['pages'] = floor(($reg['numRegistros'] / 10)) + 1;

        $reg['filtroRegistros'] = count($reg['data']);
        $esp = array();

        $i = 0;


        $response->setData($reg);
        return $response;
        //return new Response(json_encode($reg));
    }

//    ESTA PARTE ES PARA CUANDO EL SEARCH ESTA VACIO
    /**
     * 
     *
     * @Route("/busqueda/data/preguntavacia", name="busquedavacia_pregunta")
     */
    public function databusquedavaciaAction(Request $request) {

        $inicio = $request->get('inicio');
        $longitud = $request->get('longitud');
        $paginaActual = $request->get('paginaActual');
        $busqueda = $request->get('busqueda');

        $inicioRegistro = ($longitud * ($paginaActual - 1));

        $response = new JsonResponse();

        $em = $this->getDoctrine()->getEntityManager();
        //$abogadosTotal = $em->getRepository('DGAbgSistemaBundle:AbgPersona')->findBy(array('estado' => 1));
        $preguntasTotal = $em->getRepository('DGAbgSistemaBundle:AbgRespuestaPregunta')->findAll();

        $reg['inicio'] = $inicio++;
        $reg['longitud'] = $longitud;
        $reg['paginaActual'] = $paginaActual;
        $reg['inicioRegistro'] = $inicioRegistro;
        $reg['data'] = array();

        if ($busqueda == '') {
            $reg['numRegistros'] = 0;

            $sql = "SELECT  *  FROM pregunta "
                    . " ORDER BY fecha_respuesta DESC"
                    . " LIMIT " . $inicioRegistro . "," . $longitud;
            //echo $sql;            
            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $ultimas_preguntas = $stmt->fetchAll();
            $reg['data'] = $ultimas_preguntas;
            //var_dump($reg);
            //die();

            $tiemposRespuesta = array();
            if ($ultimas_preguntas != NULL) {
                foreach ($ultimas_preguntas as $row) {
                    array_push($tiemposRespuesta, $this->tiempo_transcurrido($row['fecha_respuesta']));
                }
            } else {
                $tiemposRespuesta = NULL;
            }

            $reg['tiemposRespuesta'] = $tiemposRespuesta;

            $sql = "SELECT COUNT(*) as total FROM abg_respuesta_pregunta ORDER BY id DESC LIMIT 0,8";
            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $totales = $stmt->fetchAll();
            $reg['numRegistros'] = $totales[0]['total'];


            $reg['pages'] = floor(($reg['numRegistros'] / 8)) + 1;

            $reg['filtroRegistros'] = count($reg['data']);
            $esp = array();

            $i = 0;
        } else {
            $reg['numRegistros'] = 0;
            $reg['pages'] = 0;
            $reg['filtroRegistros'] = 0;
            $reg['data'] = array();
            $reg['pages'] = 0;
        }

        //var_dump($reg);
        //die();

        $response->setData($reg);
        return $response;
        //return new Response(json_encode($reg));
    }

    function tiempo_transcurrido($fecha) {
        if (empty($fecha)) {
            return "No hay fecha";
        }

        $intervalos = array("segundo", "minuto", "hora", "día", "semana", "mes", "año");
        $duraciones = array("60", "60", "24", "7", "4.35", "12");

        $ahora = time();
        $Fecha_Unix = strtotime($fecha);

        if (empty($Fecha_Unix)) {
            return "Fecha incorrecta";
        }
        if ($ahora > $Fecha_Unix) {
            $diferencia = $ahora - $Fecha_Unix;
            $tiempo = "Hace";
        } else {
            $diferencia = $Fecha_Unix - $ahora;
            $tiempo = "Dentro de";
        }
        for ($j = 0; $diferencia >= $duraciones[$j] && $j < count($duraciones) - 1; $j++) {
            $diferencia /= $duraciones[$j];
        }

        $diferencia = round($diferencia);

        if ($diferencia != 1) {
            $intervalos[5].="e"; //MESES
            $intervalos[$j].="s";
        }

        if ($intervalos[$j] == 'meses' and $diferencia >= 12) {
            $diferencia /= $duraciones[$j];
            $j++;
            $diferencia = round($diferencia);

            if ($diferencia != 1) {
                $intervalos[$j].="s";
            }
        }

        return "$tiempo $diferencia $intervalos[$j]";
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

//Fin de la funcion databusqueda
}

//FIN DEL CONTROLADOR