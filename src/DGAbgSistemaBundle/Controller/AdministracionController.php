<?php

namespace DGAbgSistemaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

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

            $sqlPersona = "select per.id AS id, per.codigo AS codigo, CONCAT(per.nombres, '  ', per.apellido) as nombre, foto.src as src, uper.url as url, DATE_FORMAT(min(fac.fecha_registro), '%d/%m/%Y') as fecha,
                                DATE_FORMAT(max(fac.fecha_pago), '%d/%m/%Y') AS fechaPago, sum(TIMESTAMPDIFF(DAY, CURDATE(), fac.fecha_pago)) AS caducidad, per.telefono_fijo as fijo, per.telefono_movil as movil
                            from ctl_usuario usu 
                                inner join abg_persona per on usu.rh_persona_id = per.id
                                inner join abg_foto foto on foto.abg_persona_id = per.id
                                inner join abg_url_personalizada uper on uper.abg_persona_id = per.id
                                inner join abg_facturacion fac on per.id=fac.abg_persona_id and fac.servicio <> 'Espacio publicitario'
                            where foto.estado = 1 and uper.estado = 1
                            group by per.id
                            order by caducidad asc";
            
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
     * 
     *
     * @Route("/abogados/inscritos/data", name="abogados_inscritos_data", options={"expose"=true})
     */
    public function dataAbogadosInscritosAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');

        //$abogado = $request->query->get('param1');
        //$caducidad = $request->query->get('param2');
        //$fechafin = $request->query->get('param4');

        //$facturacionTotal = $em->getRepository('DGAbgSistemaBundle:AbgFacturacion')->findAll();
        $abogados['draw'] = $draw++;
        $abogados['data'] = array();

        $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
        $sqlPersona = "select per.id AS id, per.codigo AS codigo, CONCAT(per.nombres, '  ', per.apellido) as nombre, foto.src as src, uper.url as url, min(fac.fecha_registro) as fecha,
                                max(fac.fecha_pago) AS fechaPago, fac.servicio AS servicio, fac.plazo, sum(TIMESTAMPDIFF(DAY,CURDATE(),fac.fecha_pago)) AS caducidad, per.telefono_fijo as fijo, per.telefono_movil as movil
                            from ctl_usuario usu 
                                inner join abg_persona per on usu.rh_persona_id = per.id
                                inner join abg_foto foto on foto.abg_persona_id = per.id
                                inner join abg_url_personalizada uper on uper.abg_persona_id = per.id
                                inner join abg_facturacion fac on per.id=fac.abg_persona_id and fac.servicio <> 'Espacio publicitario'
                            where foto.estado = 1 and uper.estado = 1
                            group by per.id
                            order by caducidad asc";
            
        $stm = $this->container->get('database_connection')->prepare($sqlPersona);
        $stm->execute();
        $abogados['data'] = $stm->fetchAll();

        $sqlPersona = "select per.id AS id, per.codigo AS codigo, CONCAT(per.nombres, '  ', per.apellido) as nombre, foto.src as src, uper.url as url, min(fac.fecha_registro) as fecha,
                                max(fac.fecha_pago) AS fechaPago, fac.servicio AS servicio, fac.plazo, sum(TIMESTAMPDIFF(DAY,CURDATE(),fac.fecha_pago)) AS caducidad, per.telefono_fijo as fijo, per.telefono_movil as movil
                            from ctl_usuario usu 
                                inner join abg_persona per on usu.rh_persona_id = per.id
                                inner join abg_foto foto on foto.abg_persona_id = per.id
                                inner join abg_url_personalizada uper on uper.abg_persona_id = per.id
                                inner join abg_facturacion fac on per.id=fac.abg_persona_id and fac.servicio <> 'Espacio publicitario'
                            where foto.estado = 1 and uper.estado = 1
                            group by per.id
                            order by caducidad asc";
            
        $stm = $this->container->get('database_connection')->prepare($sqlPersona);
        $stm->execute();
        $totalAbogados = $stm->fetchAll();

        $facturacion['recordsTotal'] = count($totalAbogados);
        $facturacion['recordsFiltered'] = count($totalAbogados);

        return new Response(json_encode($facturacion));
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
     * Aprobación de la pregunta seleccionada
     *
     * @Route("/aprobarpregunta", name="aprobar_pregunta", options={"expose"=true})
     * @Method("POST")
     */
    public function aprobarPreguntaAction() {
        
        try {
            $request = $this->getRequest();
            $idpreg = $request->get('idpreg');
            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->beginTransaction();
            $abgPregunta = $em->getRepository('DGAbgSistemaBundle:AbgPregunta')->find($idpreg);
            
            $especialidad = $abgPregunta->getAbgEspecialidad()->getId();
            
            $sql = "SELECT abg_persona.correoelectronico 
                    FROM ctl_usuario JOIN abg_persona
                      on  ctl_usuario.rh_persona_id=abg_persona.id AND ctl_usuario.notificacion = 1 AND abg_persona.estado IN(0,1)
                    JOIN ctl_rol_usuario
                      ON ctl_usuario.id=ctl_rol_usuario.ctl_usuario_id AND ctl_rol_usuario.ctl_rol_id = 2
                    JOIN abg_persona_especialidad 
                      ON abg_persona_especialidad.abg_persona_id=abg_persona.id 
                    AND abg_persona_especialidad.ctl_especialidad_id=" . $especialidad;
            
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $coenv = $stmt->fetchAll();
            
            $abgPregunta->setEstado(1);
            $abgPregunta->setContador(0);
            $em->merge($abgPregunta);
            $em->flush();

             if (count($coenv) > 0) {
                foreach ($coenv as $value) {
                    $email = $value['correoelectronico'];            
                    $this->get('envio_correo')->sendEmail($email, "", "", "", "
                        <table style=\"width: 540px; margin: 0 auto;\">
                            <tr>
                                <td class=\"panel\" style=\"border-radius:4px;border:1px #dceaf5 solid; color:#000 ; font-size:11pt;font-family:proxima_nova,'Open Sans','Lucida Grande','Segoe UI',Arial,Verdana,'Lucida Sans Unicode',Tahoma,'Sans Serif'; padding: 30px !important; background-color: #FFF;\">
                                <center>
                                    <img style=\"width:50%;\" src=\"http://marvinvigil.info/ab/src/img/logogris.png\">
                                </center>                                                
                                    <p>Hola " . $email . " hay una nueva pregunta en la que puedes participar dando tu opinion</p>
                                    <p>Haz click en el enlace y sé el primero en contestar</p>
                                    <a href='http://abg.localhost/app_dev.php/admin/panelrespuestacentro/respuesta_abg?id=" . $idpreg . "'>Haz clik aquí para responder</a> 
                                </td>
                                <td class=\"expander\"></td>
                            </tr>
                        </table>
                    ","Nueva pregunta ". $idpreg);
                }
             }
            $em->getConnection()->commit();
            
            $response = new JsonResponse();
            $response->setData(array(
                                  'exito'   => '1',                                  
                               ));  
            
            return $response;            
            
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            $em->close();

            $data['msj'] = $e->getMessage();
            return new Response(json_encode($data));
        }
    }
    
    /**
     * Rechazo de la pregunta seleccionada
     *
     * @Route("/rechazarpregunta", name="rechazar_pregunta", options={"expose"=true})
     * @Method("POST")
     */
    public function rechazarPreguntaAction() {
        
        try {
            $request = $this->getRequest();
            $idpreg = $request->get('idpreg');
            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->beginTransaction();
            $abgPregunta = $em->getRepository('DGAbgSistemaBundle:AbgPregunta')->find($idpreg);
            
            
            $email = $abgPregunta->getCorreoelectronico();
            
            $this->get('envio_correo')->sendEmail($email, "", "", "", "
                <table style=\"width: 540px; margin: 0 auto;\">
                    <tr>
                        <td class=\"panel\" style=\"border-radius:4px;border:1px #dceaf5 solid; color:#000 ; font-size:11pt;font-family:proxima_nova,'Open Sans','Lucida Grande','Segoe UI',Arial,Verdana,'Lucida Sans Unicode',Tahoma,'Sans Serif'; padding: 30px !important; background-color: #FFF;\">
                        <center>
                            <img style=\"width:50%;\" src=\"http://marvinvigil.info/ab/src/img/logogris.png\">
                        </center>                                                
                            <p>Hola " . $email . " La pregunta que haz realizado no ha sido aprobada.</p>
                            <p>Por tratarse de una pregunta con contenido inapropiado.</p>
                        </td>
                        <td class=\"expander\"></td>
                    </tr>
                </table>
            ","Su pregunta ha sido rechasada".$idpreg);
               
            
            $em->remove($abgPregunta);
            $em->flush();
 
            $em->getConnection()->commit();
            
            $response = new JsonResponse();
            $response->setData(array(
                                  'exito'   => '1',                                  
                               ));  
            
            return $response;            
            
        } catch (Exception $e) {
            $em = $this->getDoctrine()->getManager();
            
            $em->getConnection()->rollback();
            $em->close();

            $data['msj'] = $e->getMessage();
            return new Response(json_encode($data));
        }
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

        $sql = "SELECT preg.id as preguntaId, preg.pregunta, preg.estado, preg.fechapregunta, esp.nombre_especialidad as especialidad "
                . "FROM abg_pregunta preg INNER JOIN ctl_especialidad esp on preg.ctl_especialidad = esp.id "
                . "WHERE preg.estado = 2 "
                . "ORDER BY preg.fechapregunta ASC LIMIT $start, $longitud ";
        
        $rsm->addScalarResult('preguntaId','preguntaId');
        $rsm->addScalarResult('pregunta','pregunta');
        $rsm->addScalarResult('estado','estado');
        $rsm->addScalarResult('fechapregunta','fechapregunta');
        $rsm->addScalarResult('especialidad','especialidad');

        $resultadoSql = $em->createNativeQuery($sql, $rsm)
                                  ->getResult();
        
        $pregPtes = array();
        
        foreach ($resultadoSql as $key => $value) {
            $start++;
            //var_dump($value);
            $pregPtes['corr'] = '<div class="text-center">' . $start . '</div>';
            $pregPtes['pregunta'] = $value['pregunta'];
            $pregPtes['tiempo'] = '<div class="text-center">' . $this->tiempo_transcurrido($value['fechapregunta']) . '</div>';
            $pregPtes['especialidad'] = '<div class="text-center">' . $value['especialidad'] . '</div>';
            $pregPtes['link'] = '<div class="text-center"><button type="button" class="btn btn-default btn-xs detalle" style="margin-right: 3px" data-toggle="tooltip"  data-container="body" title="Mostrar detalle" id="' .$value['preguntaId'] . '"><span class="glyphicon glyphicon-eye-open"></span></button>';
                                //. '<button type="button" class="btn btn-danger btn-xs rechazar" data-toggle="tooltip"  data-container="body" title="Eliminar" id="' .$value['preguntaId'] . '"><span class="glyphicon glyphicon-remove"></span></button></div>';
            
            array_push($preguntas['data'], $pregPtes);
        }
        
        $preguntas['recordsTotal'] = count($preguntasPendientes);
        $preguntas['recordsFiltered']= count($preguntasPendientes);
        
        return new Response(json_encode($preguntas));
    }
    
    /**
     * @Route("/aprobacion/busqueda/pregunta/", name="aprobacion_busqueda_pregunta", options={"expose"=true})
     * @Method("GET")
     * 
     */
    public function busquedaPreguntaAAprobarAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $id = $request->query->get('idpreg');
        
        $abgPregunta = $em->getRepository('DGAbgSistemaBundle:AbgPregunta')->find($id);  
        
        $pregunta = array();
        
        $pregunta['pregunta'] = $abgPregunta->getPregunta();
        $pregunta['email'] = $abgPregunta->getCorreoelectronico();
        $pregunta['detalle'] = $abgPregunta->getDetalle();
        $pregunta['fecha'] = $abgPregunta->getFechaPregunta()->format('d/m/Y');
        $pregunta['especialidad'] = $abgPregunta->getAbgEspecialidad()->getNombreEspecialidad();
        
        $response = new JsonResponse();
        $response->setData(array(
                              'pregunta'  => $pregunta,
                           ));  
            
        return $response; 
        
        
        return new Response(json_encode($pregunta));
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
    
    
    /**
     * Lista de preguntas pendientes de Aprobacion 
     *
     * @Route("/pendientesaprobacion/lista/pago", name="lista_pagos_pendientes_aprobacion")
     * @Method("GET")
     */
    public function listaPagosAprobacionAction() {
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
        
        return $this->render('administracion/panelPagos.html.twig', array(
            /*'preguntas' => $preguntas,*/ 
            'nombreCorto' => $nombreCorto, 
            'abgFoto' => $result_foto, 
            'usuario' => $idPersona,
            'abgPersona' => $result_persona
        ));
    }
    
    
    
    
    
    //Recuperar los pagos no aprobados
    /**
     * 
     *
     * @Route("/facturacionData/table", name="table_pagos_data", options={"expose"=true})
     */
    public function dataFacturacionAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');

//        $abogado = $request->query->get('param1');
//        $servicio = $request->query->get('param2');
//        $fechaini = $request->query->get('param3');
//        $fechafin = $request->query->get('param4');

//        var_dump($abogado);
//        var_dump($servicio);
//        var_dump($fechaini);
//        var_dump($fechafin);

        $facturacionTotal = $em->getRepository('DGAbgSistemaBundle:AbgFacturacion')->findBy(array('aprobado'=>0));
        
        $facturacion['draw'] = $draw++;
        $facturacion['data'] = array();

        $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
        $rsm = new ResultSetMapping();

        $sql = "SELECT fac.id as facturacion, "
                . "concat_ws(abo.codigo, '<div class=\"text-center\">', '</div>') as codigo, "
                . "concat(abo.nombres,' ',abo.apellido) as nombre, "
                . "concat_ws(fac.monto, '<div class=\"text-right\" style=\"margin-right:20px;\">', '</div>') as monto, "
                //. "concat_ws(DATE_FORMAT(fac.fecha_pago,'%d-%m-%Y'), '<div class=\"text-center\">', '</div>') as fecha_pago, "
                . "concat_ws(fac.plazo, '<div class=\"text-right\" style=\"margin-right:20px;\">', '</div>') as plazo, "
                . "concat_ws(tip.tipo_pago, '<div class=\"text-center\">', '</div>') as tipo_pago, "
                . "concat_ws(fac.servicio, '<div class=\"text-center\">', '</div>') as servicio, "
                . "concat_ws(fac.id, '<a class=\"pagoAprobado\" id=\"', '\">Aprobar</a>') as link "
                . "FROM abg_facturacion fac inner join abg_persona abo on fac.abg_persona_id = abo.id "
                . "inner join ctl_tipo_pago tip on fac.abg_tipo_pago_id = tip.id "
                . "WHERE 1 = 1 AND aprobado=0";
        //var_dump($sql);
        $rsm->addScalarResult('codigo', 'codigo');
        $rsm->addScalarResult('nombre', 'nombre');
        $rsm->addScalarResult('monto', 'monto');
        $rsm->addScalarResult('plazo', 'plazo');
        $rsm->addScalarResult('tipo_pago', 'tipo_pago');
        $rsm->addScalarResult('servicio', 'servicio');
        $rsm->addScalarResult('link', 'link');

        $facturacion['data'] = $em->createNativeQuery($sql, $rsm)
                ->getResult();

        $rsm2 = new ResultSetMapping();

        $facturacion['recordsTotal'] = count($facturacionTotal);
        $facturacion['recordsFiltered'] = count($facturacion['data']);

        return new Response(json_encode($facturacion));
    }
    
    
    
    
    
    
    
    
    //Recuperar los pagos aprobados
    /**
     * 
     *
     * @Route("/facturacionData/table/aprobado", name="table_pagos_aprobados_data", options={"expose"=true})
     */
    public function dataFacturacionAprobadoAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');

//        $abogado = $request->query->get('param1');
//        $servicio = $request->query->get('param2');
//        $fechaini = $request->query->get('param3');
//        $fechafin = $request->query->get('param4');

//        var_dump($abogado);
//        var_dump($servicio);
//        var_dump($fechaini);
//        var_dump($fechafin);

        $facturacionTotal = $em->getRepository('DGAbgSistemaBundle:AbgFacturacion')->findBy(array('aprobado'=>0));
        
        $facturacion['draw'] = $draw++;
        $facturacion['data'] = array();

        $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
        $rsm = new ResultSetMapping();

        $sql = "SELECT fac.id as facturacion, "
                . "concat_ws(abo.codigo, '<div class=\"text-left\">', '</div>') as codigo, "
                . "concat(abo.nombres,' ',abo.apellido) as nombre, "
                . "concat_ws(fac.monto, '<div class=\"text-right\" style=\"margin-right:20px;\">', '</div>') as monto, "
                //. "concat_ws(DATE_FORMAT(fac.fecha_pago,'%d-%m-%Y'), '<div class=\"text-center\">', '</div>') as fecha_pago, "
                . "concat_ws(fac.plazo, '<div class=\"text-right\" style=\"margin-right:20px;\">', '</div>') as plazo, "
                . "concat_ws(tip.tipo_pago, '<div class=\"text-center\">', '</div>') as tipo_pago, "
                . "concat_ws(fac.servicio, '<div class=\"text-center\">', '</div>') as servicio "
                //. "concat_ws(fac.id, '<a class=\"pagoAprobado\" id=\"', '\">Aprobar</a>') as link "
                . "FROM abg_facturacion fac inner join abg_persona abo on fac.abg_persona_id = abo.id "
                . "inner join ctl_tipo_pago tip on fac.abg_tipo_pago_id = tip.id "
                . "WHERE 1 = 1 AND aprobado=1";
        //var_dump($sql);
        $rsm->addScalarResult('codigo', 'codigo');
        $rsm->addScalarResult('nombre', 'nombre');
        $rsm->addScalarResult('monto', 'monto');
        $rsm->addScalarResult('plazo', 'plazo');
        $rsm->addScalarResult('tipo_pago', 'tipo_pago');
        $rsm->addScalarResult('servicio', 'servicio');
        $rsm->addScalarResult('link', 'link');

        $facturacion['data'] = $em->createNativeQuery($sql, $rsm)
                ->getResult();

        $rsm2 = new ResultSetMapping();

        $facturacion['recordsTotal'] = count($facturacionTotal);
        $facturacion['recordsFiltered'] = count($facturacion['data']);

        return new Response(json_encode($facturacion));
    }
    
    
    
    
    
    
    
    
    /**
     * Aprobación de la pregunta seleccionada
     *
     * @Route("/aprobarpago/abogado", name="aprobar_pago_abogado", options={"expose"=true})
     * @Method("POST")
     */
    public function aprobarPagoAbogadoAction() {
        
        try {
            $request = $this->getRequest();
            $id = $request->get('id');
            //var_dump($id);
            $em = $this->getDoctrine()->getManager();
            //Al usar esta forma se debe usar el commit al final
            $em->getConnection()->beginTransaction();
            $abgFacturacion = $em->getRepository('DGAbgSistemaBundle:AbgFacturacion')->find($id);
            
            
            $suscripciones = $em->getRepository('DGAbgSistemaBundle:AbgFacturacion')->findBy(array('abgPersona' => $abgFacturacion->getAbgPersona()->getId(),'aprobado'=>1), array('fechaPago' => 'DESC'));
            //var_dump($suscripciones);
            $fechaSuscripcion = $suscripciones[0]->getFechaPago()->format('Y-m-j');
            //var_dump($fechaSuscripcion);
            $plazo = $abgFacturacion->getPlazo();
//            var_dump(strtotime ($fechaSuscripcion));
//            var_dump($plazo);
            $aux = strtotime ('+'. $plazo .' day', strtotime ($fechaSuscripcion));
            //var_dump($aux);
            $fechaVencimiento = date ( 'Y-m-j' , $aux );
            //var_dump($fechaVencimiento);
            //echo "llega";
            //$facturacion->setFechaRegistro($suscripciones[0]->getFechaPago());
            //$facturacion->setFechaPago(new \DateTime ($fechaVencimiento));
            //$facturacion->setPlazo($plazo);
            $abgFacturacion->setFechaPago(new \DateTime ($fechaVencimiento));
            $abgFacturacion->setFechaRegistro(new \DateTime ('now'));
            $abgFacturacion->setAprobado(1);
            
            //$abgPregunta->setContador(0);
            $em->merge($abgFacturacion);
            $em->flush();

//             if (count($coenv) > 0) {
//                foreach ($coenv as $value) {
//                    $email = $value['correoelectronico'];            
//                    $this->get('envio_correo')->sendEmail($email, "", "", "", "
//                        <table style=\"width: 540px; margin: 0 auto;\">
//                            <tr>
//                                <td class=\"panel\" style=\"border-radius:4px;border:1px #dceaf5 solid; color:#000 ; font-size:11pt;font-family:proxima_nova,'Open Sans','Lucida Grande','Segoe UI',Arial,Verdana,'Lucida Sans Unicode',Tahoma,'Sans Serif'; padding: 30px !important; background-color: #FFF;\">
//                                <center>
//                                    <img style=\"width:50%;\" src=\"http://marvinvigil.info/ab/src/img/logogris.png\">
//                                </center>                                                
//                                    <p>Hola " . $email . " hay una nueva pregunta en la que puedes participar dando tu opinion</p>
//                                    <p>Haz click en el enlace y sé el primero en contestar</p>
//                                    <a href='http://abg.localhost/app_dev.php/admin/panelrespuestacentro/respuesta_abg?id=" . $idpreg . "'>Haz clik aquí para responder</a> 
//                                </td>
//                                <td class=\"expander\"></td>
//                            </tr>
//                        </table>
//                    ");
//                }
//             }
            $em->getConnection()->commit();
            
            $response = new JsonResponse();
            $response->setData(array(
                                  'exito'   => '1',                                  
                               ));  
            
            return $response;            
            
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            $em->close();

            $data['msj'] = $e->getMessage();
            return new Response(json_encode($data));
        }
    }
    
    
    
    
    
    
}
