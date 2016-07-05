<?php

namespace DGAbgSistemaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

}
