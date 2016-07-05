<?php

namespace DGAbgSistemaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * AbgPersona controller.
 *
 * @Route("admin/administracion")
 */
class AdministracionController extends Controller {

    /**
     * @Route("/abogados/", name="abogados", options={"expose"=true})
     * @Method("GET")
     */
    public function AbogadosAction() {
        try {
            $em = $this->getDoctrine()->getManager();
            $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();
            $username = $this->container->get('security.context')->getToken()->getUser()->getId();

            $sqlPersona = "select per.id AS id, per.codigo AS codigo, CONCAT(per.nombres, '  ', per.apellido) as nombre, foto.src as src, uper.url as url, fecha_ingreso as fecha,
        fac.fecha_pago AS fechaPago, fac.servicio AS servicio, fac.plazo,TIMESTAMPDIFF(DAY,CURDATE(),fac.fecha_pago) AS caducidad
                        from ctl_usuario usu 
                        inner join abg_persona per on usu.rh_persona_id = per.id
                        inner join abg_foto foto on foto.abg_persona_id = per.id
                        inner join abg_url_personalizada uper on uper.abg_persona_id = per.id
                        inner join abg_facturacion fac on per.id=fac.abg_persona_id and fac.servicio <> 'Espacio publicitario'
                       where foto.estado = 1 and uper.estado = 1
                        order by caducidad desc
                        limit 0, 12";
            $stm = $this->container->get('database_connection')->prepare($sqlPersona);
            $stm->execute();
            $result_abogados = $stm->fetchAll();
          /*     foreach ($result_abogados as $row) {
                $row['fechaPago']
               }*/
      
            /* var_dump($result_persona);
              exit(); */
            $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                    . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil, p.estado As estado, p.tituloProfesional AS tprofesional, p.verificado As verificado "
                    . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
            $result_persona = $em->createQuery($dql_persona)->getArrayResult();
            $nombreCorto = split(" ", $result_persona[0]['nombre'])[0] . " " . split(" ", $result_persona[0]['apellido'])[0];

            $dql_solict_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                    . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil, p.estado As estado "
                    . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.verificado=0 AND p.id=" . $idPersona;
            $result_solict_persona = $em->createQuery($dql_solict_persona)->getArrayResult();


            $dql_tipoPago = "SELECT p.id as id, p.tipoPago As nombre "
                    . " FROM DGAbgSistemaBundle:CtlTipoPago p ORDER BY p.tipoPago ASC";
            $TipoPago = $em->createQuery($dql_tipoPago)->getArrayResult();


            $dqlfoto = "SELECT fot.src as src "
                    . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
            $result_foto = $em->createQuery($dqlfoto)->getArrayResult();


            $dqlfoto = "SELECT fot.src as src, fot.estado As estado "
                    . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and fot.tipoFoto=1 ";
            $fotoP = $em->createQuery($dqlfoto)->getArrayResult();


            $sqlRol = "SELECT  r.id As id, r.rol As rol"
                    . " FROM  ctl_rol_usuario ru "
                    . " JOIN ctl_rol r ON r.id=ru.ctl_rol_id AND ru.ctl_usuario_id=" . $username;
            $stm = $this->container->get('database_connection')->prepare($sqlRol);
            $stm->execute();
            $RolUser = $stm->fetchAll();



            return $this->render(':administracion:panelAbogados.html.twig', array(
                        'nombreCorto' => $nombreCorto,
                        'abgPersona' => $result_persona,
                        'abogados' => $result_abogados,
                        'usuario' => $idPersona,
                        'TipoPago' => $TipoPago,
                        'abgFoto' => $result_foto,
            ));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            return new Response(json_encode($data));
        }
    }
    
    /**
     * Lista de preguntas pendientes de Aprobacion 
     *
     * @Route("/pendientesaprobacion/lista", name="lista_preguntas_pendientes_aprobacion")
     * @Method("GET")
     */
    public function listaPreguntasAprobacionAction() {
        $em = $this->getDoctrine()->getManager();
        
        $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();

        $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil, p.estado As estado, p.tituloProfesional AS tprofesional, p.verificado As verificado "
                . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
        $result_persona = $em->createQuery($dql_persona)->getArrayResult();
        $nombreCorto = split(" ", $result_persona[0]['nombre'])[0] . " " . split(" ", $result_persona[0]['apellido'])[0];

        $dqlfoto = "SELECT fot.src as src "
                . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
        $result_foto = $em->createQuery($dqlfoto)->getArrayResult();
        
        //$preguntas = $em->getRepository('DGAbgSistemaBundle:AbgPregunta')->findBy(array('estado' => 2));
        
        return $this->render('centropreg/panelAprobacionPreguntas.html.twig', array(/*'preguntas' => $preguntas,*/ 'nombreCorto' => $nombreCorto, 'abgFoto' => $result_foto, 'abgPersona' => $result_persona));
    }
    
    /**
     * 
     *
     * @Route("/aprobacion/preguntaspendientes/data", name="preguntas_pendientes_data", options={"expose"=true})
     */
    public function datapreguntasPendientesAprobacionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');
        
        $preguntasPendientes = $em->getRepository('DGAbgSistemaBundle:AbgPregunta')->findBy(array('estado' => 2));
        $preguntas['draw']=$draw++;  
        $preguntas['data']= array();
        
        $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
        $rsm = new ResultSetMapping();

        $sql = "SELECT preg.id as preguntaId, preg.pregunta, preg.estado, preg.fechapregunta "
                . "FROM abg_pregunta preg "
                . "WHERE preg.estado = 2 "
                . "ORDER BY preg.fechapregunta DESC LIMIT $start, $longitud ";
        
        $rsm->addScalarResult('preguntaId','preguntaId');
        $rsm->addScalarResult('pregunta','pregunta');
        $rsm->addScalarResult('estado','estado');
        $rsm->addScalarResult('fechapregunta','fechapregunta');

        $resultadoSql = $em->createNativeQuery($sql, $rsm)
                                  ->getResult();
        
        $pregPtes = array();
        foreach ($resultadoSql as $key => $value) {
            $correlativo = $key + 1;
            $pregPtes['corr'] = '<div class="text-center">' . $correlativo . '</div>';
            $pregPtes['pregunta'] = $value['pregunta'];
            $pregPtes['tiempo'] = '<div class="text-center">' . $this->tiempo_transcurrido($value['fechapregunta']) . '</div>';
            $pregPtes['link'] = '<div class="text-center"><button type="button" class="btn btn-success btn-xs aprobar" style="margin-right: 3px" data-toggle="tooltip"  data-container="body" title="Aprobar" id="' .$value['preguntaId'] . '"><span class="glyphicon glyphicon-ok"></span></button>'
                                . '<button type="button" class="btn btn-danger btn-xs rechazar" data-toggle="tooltip"  data-container="body" title="Rechazar" id="' .$value['preguntaId'] . '"><span class="glyphicon glyphicon-remove"></span></button></div>';
            
            array_push($preguntas['data'], $pregPtes);
        }
        
        $preguntas['recordsTotal'] = count($preguntasPendientes);
        $preguntas['recordsFiltered']= count($preguntasPendientes);
        
        return new Response(json_encode($preguntas));
    }
    
    function tiempo_transcurrido($fecha) 
    {
        if(empty($fecha)) {
            return "No hay fecha";
        }

        $intervalos = array("segundo", "minuto", "hora", "día", "semana", "mes", "año");
        $duraciones = array("60","60","24","7","4.35","12");

        $ahora = time();
        $Fecha_Unix = strtotime($fecha);

        if(empty($Fecha_Unix)) {   
            return "Fecha incorrecta";
        }
        if($ahora > $Fecha_Unix) {   
            $diferencia = $ahora - $Fecha_Unix;
            $tiempo = "Hace";
        } else {
            $diferencia = $Fecha_Unix -$ahora;
            $tiempo = "Dentro de";
        }
        for($j = 0; $diferencia >= $duraciones[$j] && $j < count($duraciones)-1; $j++) {
            $diferencia /= $duraciones[$j];
        }

        $diferencia = round($diferencia);

        if($diferencia != 1) {
            $intervalos[5].="e"; //MESES
            $intervalos[$j].="s";
        }
        
        
        if($intervalos[$j] == 'meses' and $diferencia >= 12){
            $diferencia /= $duraciones[$j];
            $j++;
            $diferencia = round($diferencia);
            
            if($diferencia != 1) {
                $intervalos[$j].="s";
            }
        }

        return "$tiempo $diferencia $intervalos[$j]";
    }
}
