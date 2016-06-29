<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query\ResultSetMapping;

class DefaultController extends Controller
{
    //pagina home
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
//        $especialidades = $em->getRepository('DGAbgSistemaBundle:CtlEspecialidad')->findAll();
        $rsm4 = new ResultSetMapping();
       
        $sql4 = "select *
                from directorio
                where tipo = 1
                order by id desc 
                limit 0, 5";

        $rsm4->addScalarResult('nombres','nombres');
        $rsm4->addScalarResult('apellido','apellido');
        $rsm4->addScalarResult('foto','foto');
        $rsm4->addScalarResult('url','url');
        $rsm4->addScalarResult('especialidad','especialidad');
        $rsm4->addScalarResult('titulo','titulo');
        $rsm4->addScalarResult('cargo','cargo');
        $rsm4->addScalarResult('genero','genero');
        
        $usuarios = $em->createNativeQuery($sql4, $rsm4)
                                  ->getResult();
        //var_dump($usuarios);
        
     //   $sqldepto= "SELECT est.nombre_estado AS nombreEstado FROM ctl_estado est ORDER BY est.nombre_estado DESC LIMIT 0,6";
         $sqldepto="SELECT dep.nombre_estado As nombreEstado, count(*) AS Estado FROM  directorio d "
                 . " join ctl_estado dep ON dep.id=d.estado " 
                 . " group by estado limit 0,6";
         $stm = $this->container->get('database_connection')->prepare($sqldepto);
        $stm->execute();
        $departamentos = $stm->fetchAll();
        
        $sqlEsp = "SELECT esp.nombre_especialidad AS especialidad, count(*) AS Nabogados "
                . " FROM  directorio d "
                . " JOIN abg_persona_especialidad ep ON d.id=abg_persona_id "
                . " JOIN ctl_especialidad esp ON esp.id=ep.ctl_especialidad_id "
                . " GROUP BY ep.ctl_especialidad_id limit 0,6";
        
        $stm = $this->container->get('database_connection')->prepare($sqlEsp);
        $stm->execute();
        $especialidad= $stm->fetchAll();
        
        //$preguntas = $em->getRepository('DGAbgSistemaBundle:AbgPregunta')->findAll();
        
          $sql_preguntas_resiente = "SELECT  pre.id, usu.id,CONCAT(per.nombres, '  ', per.apellido) as nombres, uper.url as url, fot.src AS src, "
                . " pre.respuesta AS respuesta, pre.fecha_respuesta, pre.abg_pregunta AS idPregunta, per.estado AS esatdo, preg.pregunta AS pregunta "
                . " FROM abg_pregunta preg "
                . " JOIN abg_respuesta_pregunta pre ON preg.id=pre.abg_pregunta "
                . " JOIN ctl_usuario usu ON pre.ctl_usuario_id = usu.id "
                . " JOIN abg_persona per ON usu.rh_persona_id = per.id "
                . " JOIN abg_url_personalizada uper ON uper.abg_persona_id = per.id "
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
        //var_dump($especialidad);
        return $this->render('DGAbgSistemaBundle:Default:home.html.twig',array(
            'usuarios' =>$usuarios,
            'ultimas_prteguntas'=> $ultimas_prteguntas,
            'tiemposRespuesta' => $tiemposRespuesta,
            'departamentos'=>$departamentos,
            'especialidad'=>$especialidad
        ));
    }
    
    
    //Pagina landing
    /**
     * @Route("/landing/")
     */
    public function landingAction()
    {
//        $em = $this->getDoctrine()->getManager();
//        $especialidades = $em->getRepository('DGAbgSistemaBundle:CtlEspecialidad')->findAll();
        
        
        return $this->render('DGAbgSistemaBundle:Default:landing.html.twig',array(
//            'especialidades' =>$especialidades
        ));
    }
    
    
    /**
    * Ajax utilizado para buscar informacion de un abogado
    *  
    * @Route("/busqueda-ab-select/data", name="busqueda_ab_select")
    */
    public function busquedaAbAction(Request $request)
    {
        $busqueda = $request->query->get('q');
        $page = $request->query->get('page');
        
//        var_dump($busqueda);
        $rsm = new ResultSetMapping();
        $em = $this->getDoctrine()->getEntityManager();
        $sql = "SELECT * FROM directorio WHERE CONCAT(upper(nombres),' ',upper(apellido),' ',upper(sub),' ',upper(especialidad)) LIKE '%".strtoupper($busqueda)."%' ORDER BY nombres ASC";
//        var_dump($sql);
        $rsm->addScalarResult('id','id');
        $rsm->addScalarResult('nombres','nombres');
        $rsm->addScalarResult('apellido','apellido');
        $abogado['data'] = $em->createNativeQuery($sql, $rsm)
                                ->getResult();
                        
        
        
        return new Response(json_encode($abogado));
    }
    
    
    
    
    
    /**
    * Ajax utilizado para buscar informacion de un abogado
    *  
    * @Route("/busqueda-ab-input/data", name="busqueda_ab_input", options={"expose"=true}))
    */
    public function busquedaAbInputAction(Request $request)
    {
        $busqueda = $request->query->get('query');
        $page = $request->query->get('page');
        
        //var_dump($busqueda);
        $rsm = new ResultSetMapping();
        $em = $this->getDoctrine()->getEntityManager();
        $sql = "SELECT concat(nombres,' ',apellido) as value FROM directorio WHERE CONCAT(upper(nombres),' ',upper(apellido),' ',upper(sub),' ',upper(especialidad)) LIKE '%".strtoupper($busqueda)."%' ORDER BY value ASC";
        //var_dump($sql);
        
        //$rsm->addScalarResult('suggestions','suggestions');
        $rsm->addScalarResult('id','id');
        $rsm->addScalarResult('value','value');
        $rsm->addScalarResult('data','data');
        $abogado['suggestions'] = $em->createNativeQuery($sql, $rsm)
                                ->getResult();
                        
                        //var_dump($abogado['data']);
        
        return new Response(json_encode($abogado));
    }
    
    /**
    * Ajax utilizado para buscar informacion de un abogado
    *  
    * @Route("/busqueda-ciudad-input/data", name="busqueda_ciudad_input", options={"expose"=true}))
    */
    public function busquedaCiudadInputAction(Request $request)
    {
      
        $busqueda = $request->query->get('query');
        $page = $request->query->get('page');
        
        //var_dump($busqueda);
        $rsm = new ResultSetMapping();
        $em = $this->getDoctrine()->getEntityManager();
        $sql = "SELECT concat(est.nombre_estado,', ', ciu.nombre_ciudad) as value,nombre_estado as estado,nombre_ciudad as ciudad "
                . "FROM ctl_ciudad ciu join ctl_estado est on (ciu.ctl_estado_id=est.id)"
                . " WHERE concat(est.nombre_estado,', ', ciu.nombre_ciudad) LIKE '%".$busqueda."%'";
        //var_dump($sql);
        
        //$rsm->addScalarResult('suggestions','suggestions');
        $rsm->addScalarResult('value','value');
        $rsm->addScalarResult('nombre','nombre');
        $rsm->addScalarResult('ciudad','ciudad');
        $abogado['suggestions'] = $em->createNativeQuery($sql, $rsm)
                                ->getResult();
                        
                        //var_dump($abogado['data']);
        
        return new Response(json_encode($abogado));
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
}