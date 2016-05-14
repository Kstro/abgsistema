<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DGAbgSistemaBundle\Entity\AbgFacturacion;
use Symfony\Component\HttpFoundation\Response;
use DGAbgSistemaBundle\Form\AbgFacturacionType;

/**
 * AbgFacturacion controller.
 *
 * @Route("/abgfacturacion")
 */
class AbgFacturacionController extends Controller {

    /**
     * Lists all AbgFacturacion entities.
     *
     * @Route("/", name="abgfacturacion_index")
     * @Method("GET")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction();
        $request = $this->getRequest();
        $result_sub = "";
        $result_especialida = "";
        $Experiencia = "";
        $Certificacion = "";
        $Curso = "";
        try {

                      $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();
            $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                    . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil, p.estado As estado, p.tituloProfesional AS tprofesional "
                    . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
            $result_persona = $em->createQuery($dql_persona)->getArrayResult();

            $dql_tipoPago = "SELECT p.id as id, p.tipoPago As nombre "
                    . " FROM DGAbgSistemaBundle:CtlTipoPago p ORDER BY p.tipoPago ASC";
            $TipoPago = $em->createQuery($dql_tipoPago)->getArrayResult();


            $dqlfoto = "SELECT fot.src as src "
                    . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
            $result_foto = $em->createQuery($dqlfoto)->getArrayResult();


            $dqlfoto = "SELECT fot.src as src, fot.estado As estado "
                    . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and fot.tipoFoto=1 ";
            $fotoP = $em->createQuery($dqlfoto)->getArrayResult();



            //  return $this->render('abgpersona/panelAdministrativoAbg.html.twig', array(
            return $this->render(':abgfacturacion:panelFacturacion.html.twig', array(
                        'abgPersona' => $result_persona,
                        'usuario' => $idPersona,
                        'TipoPago' => $TipoPago,
                        'abgFoto' => $result_foto,
            ));
        } catch (\Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));

            // echo $e->getMessage();   
        }

        return $this->render('abgfacturacion/panelFacturacion.html.twig', array(
                    'abgFacturacions' => $abgFacturacions,
        ));
    }

    /**
     * Creates a new AbgFacturacion entity.
     *
     * @Route("/new", name="abgfacturacion_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $abgFacturacion = new AbgFacturacion();
        $form = $this->createForm('DGAbgSistemaBundle\Form\AbgFacturacionType', $abgFacturacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($abgFacturacion);
            $em->flush();

            return $this->redirectToRoute('abgfacturacion_show', array('id' => $abgFacturacion->getId()));
        }

        return $this->render('abgfacturacion/new.html.twig', array(
                    'abgFacturacion' => $abgFacturacion,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AbgFacturacion entity.
     *
     * @Route("/{id}", name="abgfacturacion_show")
     * @Method("GET")
     */
    public function showAction(AbgFacturacion $abgFacturacion) {
        $deleteForm = $this->createDeleteForm($abgFacturacion);

        return $this->render('abgfacturacion/show.html.twig', array(
                    'abgFacturacion' => $abgFacturacion,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AbgFacturacion entity.
     *
     * @Route("/{id}/edit", name="abgfacturacion_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AbgFacturacion $abgFacturacion) {
        $deleteForm = $this->createDeleteForm($abgFacturacion);
        $editForm = $this->createForm('DGAbgSistemaBundle\Form\AbgFacturacionType', $abgFacturacion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($abgFacturacion);
            $em->flush();

            return $this->redirectToRoute('abgfacturacion_edit', array('id' => $abgFacturacion->getId()));
        }

        return $this->render('abgfacturacion/edit.html.twig', array(
                    'abgFacturacion' => $abgFacturacion,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AbgFacturacion entity.
     *
     * @Route("/{id}", name="abgfacturacion_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AbgFacturacion $abgFacturacion) {
        $form = $this->createDeleteForm($abgFacturacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($abgFacturacion);
            $em->flush();
        }

        return $this->redirectToRoute('abgfacturacion_index');
    }

    /**
     * Creates a form to delete a AbgFacturacion entity.
     *
     * @param AbgFacturacion $abgFacturacion The AbgFacturacion entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AbgFacturacion $abgFacturacion) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('abgfacturacion_delete', array('id' => $abgFacturacion->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

    /**
     * @Route("/abogados/get", name="abogados", options={"expose"=true})
     * @Method("GET")
     */
    public function getAbogadosAction() {
        $request = $this->getRequest();
        $busqueda = $request->query->get('q');

        $em = $this->getDoctrine()->getEntityManager();
        $dql = "SELECT p.id AS idAbg,CONCAT(p.nombres, '  ', p.apellido)   AS nombre "
                . "FROM DGAbgSistemaBundle:AbgPersona p "
                . "WHERE  upper(CONCAT(p.nombres,' ',p.apellido)) LIKE upper(:busqueda)  "
                . "ORDER BY p.nombres ASC ";

        $array = $em->createQuery($dql)
                ->setParameters(array('busqueda' => "%" . $busqueda . "%"))
                ->setMaxResults(10)
                ->getResult();
        if (count($array > 0)) {
            $data['data'] = $array;
        } else {
            $data['data'] = array('id' => 0, 'nombre' => '<a onclick="CambioEmp()"> Nueva empresa</a>');
        }
        return new Response(json_encode($data));
    }

    /**
     * @Route("/facturacion", name="facturacion", options={"expose"=true})
     * @Method("POST")
     */
    public function FacturacionAction() {

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        try {
            $idUser = $this->container->get('security.context')->getToken()->getUser()->getId();
            parse_str($request->get('dato'), $datos);

            $Persona = $em->getRepository("DGAbgSistemaBundle:AbgPersona")->find($datos['Sabogados']);
            $TipoPago = $em->getRepository("DGAbgSistemaBundle:CtlTipoPago")->find($datos['STipoPago']);
            $abgFacturacion = new AbgFacturacion();
            $abgFacturacion->setAbgPersona($Persona);
            $abgFacturacion->setAbgTipoPago($TipoPago);
            $abgFacturacion->setFechaPago(new \DateTime("now"));
            $abgFacturacion->setIdUser($idUser);
            $abgFacturacion->setMonto($datos['txtcosto']);
            $abgFacturacion->setPlazo($datos['Splazo']);
            $abgFacturacion->setServicio($datos['STipoServicio']);
            $abgFacturacion->setDescripcion($datos['txtdescripcion']);
            $em->persist($abgFacturacion);
            $em->flush();
            $data['msj'] = "datos registrados";
            return new Response(json_encode($data));
        } catch (\Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/consulta_fact/get", name="consulta_fact", options={"expose"=true})
     * @Method("GET")
     */
    public function getConsultaFactAction() {
        $request = $this->getRequest();
        $busqueda = $request->query->get('q');
     try {
        $em = $this->getDoctrine()->getEntityManager();
        
         parse_str($request->get('dato'), $datos);
   
        $sql = "SELECT p.id AS idAbg, CONCAT(p.nombres, '  ', p.apellido)   AS nombre, f.id As idFact, f.monto AS monto, f.plazo As plazo, "
                . " f.servicio As servicio, tp.tipo_pago As tipoPago, f.descripcion As descripcion, date_format(f.fecha_pago, '%d/%m/%Y') As fechaPago "
                . " FROM marvinvi_abg.abg_persona p "
                . " JOIN marvinvi_abg.abg_facturacion f"
                . " ON  p.id=f.abg_persona_id AND p.id=".$datos['Sabogado']."  AND f.fecha_pago >=".$datos['txtFechaInicio']."  OR  f.fecha_pago <=".$datos['txtFechaFin']
                . " JOIN marvinvi_abg.ctl_tipo_pago tp "
                . " ON  tp.id=f.abg_tipo_pago_id"
                . " ORDER BY p.nombres ASC ";
        $stm = $this->container->get('database_connection')->prepare($sql);
        $stm->execute();
        $data['facturacion'] = $stm->fetchAll();


        return new Response(json_encode($data));
          } catch (\Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

}
