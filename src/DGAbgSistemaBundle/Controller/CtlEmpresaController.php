<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DGAbgSistemaBundle\Entity\CtlEmpresa;
use DGAbgSistemaBundle\Entity\AbgFoto;
use DGAbgSistemaBundle\Entity\AbgPersona;
use DGAbgSistemaBundle\Entity\CtlUsuario;
use DGAbgSistemaBundle\Entity\AbgSitioWeb;
use DGAbgSistemaBundle\Entity\AbgUrlPersonalizada;
use DGAbgSistemaBundle\Entity\AbgVisitas;
use DGAbgSistemaBundle\Form\CtlEmpresaType;
use Symfony\Component\HttpFoundation\Response;
use DGAbgSistemaBundle\Entity\AbgPersonaEspecialida;
use DGAbgSistemaBundle\Resources\Tinypng\lib\lib\Tinify;

include_once '../src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify.php';
include_once '../src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify/Exception.php';
include_once '../src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify/ResultMeta.php';
include_once '../src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify/Result.php';
include_once '../src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify/Source.php';
include_once '../src/DGAbgSistemaBundle/Resources/tinypng/lib/lib/Tinify/Client.php';

/**
 * CtlEmpresa controller.
 *
 * @Route("/")
 */
class CtlEmpresaController extends Controller {

    /**
     * Lists all CtlEmpresa entities.
     *
     * @Route("/registro", name="registro")
     * @Method({"GET", "POST"})
     */
    public function IndexAction() {

        return $this->render('ctlempresa/index.html.twig', array(
        ));
    }

    /**
     * Lists all CtlEmpresa entities.
     *
     * @Route("admin/empresa", name="empresa")
     * @Method({"GET", "POST"})
     */
    public function EmpresaAction() {
        $username = $this->container->get('security.context')->getToken()->getUser();
        //$establecimiento = $user->getIdEmpleado()->getIdEstablecimiento()->getId();
        //var_dump($username);


        $em = $this->getDoctrine()->getManager();
        $RepositorioPersona = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlUsuario')->findByUsername($username->getUsername()); //->getRhPersona();
        //var_dump($RepositorioPersona);
        $ctlEmpresaId = $username->getCtlEmpresa()->getId();

        //Coleccion de datos de la empresa

        $dqlempresa = "SELECT  e.nombreEmpresa AS nombreEmpresa, e.correoelectronico as correoEmpresa, e.direccion, e.sitioWeb,e.movil, e.telefono, e.color,e.cantidadEmpleados ,e.latitude, e.longitude ,"
                . "date_format(e.fechaFundacion, '%Y') As fechaFundacion"
                . " FROM DGAbgSistemaBundle:CtlEmpresa e WHERE e.id=" . $ctlEmpresaId;

        $result_empresa = $em->createQuery($dqlempresa)->getArrayResult();

        //Valor de la foto de la empresa

        $dqlfoto = "SELECT fot.src as src "
                . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.ctlEmpresa=" . $ctlEmpresaId . " and fot.estado=1 and fot.tipoFoto=1";
        $result_fotoEmp = $em->createQuery($dqlfoto)->getArrayResult();


        //Array de si se lista o no dentro del perfil de la empresa
        $RepositorioListaEmpresa = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlEmpresa')->find($ctlEmpresaId); //->getRhPersona();
        $lista = $RepositorioListaEmpresa->getListaEmpleado();

        if ($lista) {
            //Listar los empleados de la empresa
            $sqlEmpEmp = "SELECT per.id as idPersona, per.nombres as nombres, per.apellido as apellido, per.correoelectronico as correoelectronico, "
                    . " per.telefono_fijo as telefonoFijo, per.telefono_movil as telefonoMovil, per.titulo_profesional AS tituloProfesional, "
                    . " per.id, fot.src, sw.nombre AS sitioWeb, per.verificado AS verificado, exp.puesto AS puesto "
                    . "FROM marvinvi_abg.abg_foto fot "
                    . "JOIN marvinvi_abg.abg_persona per "
                    . " ON fot.abg_persona_id=per.id AND fot.tipo_foto=0 AND fot.tipo_foto <> 5 "
                    . "JOIN marvinvi_abg.abg_persona_empresa emp "
                    . "ON  emp.ctl_empresa_id=" . $ctlEmpresaId
                    . " JOIN marvinvi_abg.abg_experiencia_laboral exp "
                    . " ON exp.ctl_empresa_id=" . $ctlEmpresaId
                    . " LEFT JOIN marvinvi_abg.abg_sitio_web sw "
                    . " ON per.id=sw.abg_persona_id "
                    . " GROUP BY  per.id ORDER BY per.nombres ASC";
            $stm = $this->container->get('database_connection')->prepare($sqlEmpEmp);
            $stm->execute();
            $registro_empleados = $stm->fetchAll();
        } else {

            $registro_empleados = null;
        }

        //valor de los tipos de empresa  
        $dqlTipoEmpresa = "SELECT tipo.tipoEmpresa as tipoEmpresa  "
                . "FROM DGAbgSistemaBundle:CtlEmpresa emp "
                . "JOIN emp.ctlTipoEmpresa tipo "
                . "WHERE emp.id =" . $ctlEmpresaId;

        $registro_tipoempresa = $em->createQuery($dqlTipoEmpresa)->getResult();


        //Valor de la persona
        // $RepositorioPersonas = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlUsuario')->findByUsername($username); //->getRhPersona();
        $idPersona = $username->getRhPersona()->getId();
        $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo "
                . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
        $result_persona = $em->createQuery($dql_persona)->getArrayResult();



        //Foto de la persona de perfil cuando este dentro del modulo de empresa
        /*   $dqlfoto = "SELECT fot.src as src "
          . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and fot.tipoFoto=1";
          $result_fotoAbogado = $em->createQuery($dqlfoto)->getArrayResult(); */
        $dqlfoto = "SELECT fot.src as src "
                . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
        $result_foto = $em->createQuery($dqlfoto)->getArrayResult();

        //Selccion de las URL personalizadas de los abogados de las empresas
        $dqlUrl = "SELECT per.id as idpersonaUrl, per.nombres, url.url  FROM DGAbgSistemaBundle:AbgUrlPersonalizada url "
                . "JOIN url.abgPersona per  "
                . "JOIN per.ctlEmpresa emp "
                . " WHERE emp.id=" . $ctlEmpresaId
                . " AND url.estado=1";

        $result_url = $em->createQuery($dqlUrl)->getArrayResult();

        $dql_especialida = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre, pe.descripcion AS descripcion "
                . " FROM  DGAbgSistemaBundle:CtlEspecialidad e "
                . " JOIN DGAbgSistemaBundle:AbgPersonaEspecialida pe WHERE e.id=pe.ctlEspecialidad AND pe.ctlEmpresa=" . $ctlEmpresaId
                . " GROUP by e.id "
                . " ORDER BY e.nombreEspecialidad";
        $result_especialida = $em->createQuery($dql_especialida)->getArrayResult();


        return $this->render('ctlempresa/paneladministrativoempresa.html.twig', array(
                    'ctlEmpresa' => $result_empresa,
                    'abgFotoEmp' => $result_fotoEmp,
                    'ctlEmpresaId' => $ctlEmpresaId,
                    'empleados' => $registro_empleados,
                    'tipoEmpresa' => $registro_tipoempresa,
                    'usuario' => $username,
                    'abgPersona' => $result_persona,
                    //     'abgFotoP' => $result_fotoAbogado,
                    'abgFoto' => $result_foto,
                    'url' => $result_url,
                    'RegistroEspecialida' => $result_especialida,
        ));
    }

    /**
     * @Route("/especialidad_emp", name="especialidad_emp", options={"expose"=true})
     * @Method("GET")
     */
    public function getEspecialidadEmpAction() {
        try {
            $n = 0;
            $subEspS = "";
            $em = $this->getDoctrine()->getManager();
            $request = $this->getRequest();
            $subEspecialidadesSeleccionadas = "";
            if (($request->get('empresaId') != null)) {



                $sql = "SELECT  e.id AS id, e.nombre_especialidad AS nombre, pe.descripcion AS descripcion, pe.id As idPE, pe.ctl_especialidad_id AS idEsp "
                        . " FROM  marvinvi_abg.ctl_especialidad e "
                        . " left JOIN marvinvi_abg.abg_persona_especialidad pe ON e.id=pe.ctl_especialidad_id AND pe.ctl_empresa_id=" . $request->get('empresaId')
                        . " ORDER BY e.nombre_especialidad";
                $stm = $this->container->get('database_connection')->prepare($sql);
                $stm->execute();
                $subEspecialidadesSeleccionadas = $stm->fetchAll();
            }
            /*  $dql_departamento = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre"
              . " FROM  DGAbgSistemaBundle:CtlEspecialidad e "
              . "JOIN  DGAbgSistemaBundle:CtlSubespecialidad sub WHERE e.id=sub.abgEspecialidad group by e.id";
              $result_especialida = $em->createQuery($dql_departamento)->getArrayResult();
             */


            return $this->render('ctlempresa/especialidades_emp.html.twig', array(
                        'especialidadesS' => $subEspecialidadesSeleccionadas
            ));
        } catch (\Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * Lists all CtlEmpresa entities.
     *
     * @Route("/empresapublico", name="empresapublico")
     * @Method({"GET"})
     */
    public function EmpresapublicoAction() {

        $em = $this->getDoctrine()->getManager();

        $ctlEmpresaId = $this->container->get('security.context')->getToken()->getUser()->getCtlEmpresa()->getId();
        $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();
        /*
          $RepositorioPersona = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlUsuario')->findByUsername($username); //->getRhPersona();
          $ctlEmpresaId = $RepositorioPersona[0]->getCtlEmpresa()->getId(); */


        //Completo los elementos de las visitas
        $entity = $em->getRepository('DGAbgSistemaBundle:AbgVisitas')->findBy(array("ctlEmpresa" => $ctlEmpresaId));
        $valor = $entity[0]->getVisita();

        $contador = $valor + 1;
        $entity[0]->setVisita($contador);
        $em->merge($entity[0]);
        $em->flush();

        //Coleccion de datos de la empresa

        $dqlempresa = "SELECT  e.nombreEmpresa AS nombreEmpresa, e.correoelectronico as correoEmpresa, e.direccion, e.sitioWeb,e.movil, e.telefono, e.color,e.cantidadEmpleados ,e.latitude, e.longitude ,"
                . "date_format(e.fechaFundacion, '%Y') As fechaFundacion"
                . " FROM DGAbgSistemaBundle:CtlEmpresa e WHERE e.id=" . $ctlEmpresaId;

        $result_empresa = $em->createQuery($dqlempresa)->getArrayResult();

        //Valor de la foto de la empresa

        $dqlfoto = "SELECT fot.src as src "
                . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.ctlEmpresa=" . $ctlEmpresaId . " and fot.estado=1 and fot.tipoFoto=1";
        $result_foto = $em->createQuery($dqlfoto)->getArrayResult();


        //Array de si se lista o no dentro del perfil de la empresa
        $RepositorioListaEmpresa = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlEmpresa')->find($ctlEmpresaId); //->getRhPersona();
        $lista = $RepositorioListaEmpresa->getListaEmpleado();

        if ($lista) {
            //Listar los empleados de la empresa
            $sqlEmpEmp = "SELECT per.id as idPersona, per.nombres as nombres, per.apellido as apellido, per.correoelectronico as correoelectronico, "
                    . " per.telefono_fijo as telefonoFijo, per.telefono_movil as telefonoMovil, per.titulo_profesional AS tituloProfesional, "
                    . " per.id, fot.src, sw.nombre AS sitioWeb, per.verificado AS verificado, exp.puesto AS puesto "
                    . "FROM marvinvi_abg.abg_foto fot "
                    . "JOIN marvinvi_abg.abg_persona per "
                    . " ON fot.abg_persona_id=per.id AND fot.tipo_foto=0 AND fot.tipo_foto <> 5 "
                    . "JOIN marvinvi_abg.abg_persona_empresa emp "
                    . "ON  emp.ctl_empresa_id=" . $ctlEmpresaId
                    . " JOIN marvinvi_abg.abg_experiencia_laboral exp "
                    . " ON exp.ctl_empresa_id=" . $ctlEmpresaId
                    . " LEFT JOIN marvinvi_abg.abg_sitio_web sw "
                    . " ON per.id=sw.abg_persona_id "
                    . " GROUP BY  per.id ORDER BY per.nombres ASC";
            $stm = $this->container->get('database_connection')->prepare($sqlEmpEmp);
            $stm->execute();
            $registro_empleados = $stm->fetchAll();
        } else {

            $registro_empleados = null;
        }

        //valor de los tipos de empresa  
        $dqlTipoEmpresa = "SELECT tipo.tipoEmpresa as tipoEmpresa  "
                . "FROM DGAbgSistemaBundle:CtlEmpresa emp "
                . "JOIN emp.ctlTipoEmpresa tipo "
                . "WHERE emp.id =" . $ctlEmpresaId;

        $registro_tipoempresa = $em->createQuery($dqlTipoEmpresa)->getResult();

        //Valores de las persona
        /*
          $RepositorioPersonas = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlUsuario')->findByUsername($username); //->getRhPersona();
          $idPersona = $RepositorioPersonas[0]->getRhPersona()->getId(); */




        $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo "
                . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
        $result_persona = $em->createQuery($dql_persona)->getArrayResult();

        //metodo que me retorna Especialidades
        $dql_especialida = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre, pe.descripcion AS descripcion "
                . " FROM  DGAbgSistemaBundle:CtlEspecialidad e "
                . " JOIN DGAbgSistemaBundle:AbgPersonaEspecialida pe WHERE e.id=pe.ctlEspecialidad AND pe.ctlEmpresa=" . $ctlEmpresaId
                . " GROUP by e.id "
                . " ORDER BY e.nombreEspecialidad";
        $result_especialida = $em->createQuery($dql_especialida)->getArrayResult();



        //Selccion de las URL personalizadas de los abogados de las empresas
        $dqlUrl = "SELECT per.id as idpersonaUrl, per.nombres, url.url  FROM DGAbgSistemaBundle:AbgUrlPersonalizada url "
                . "JOIN url.abgPersona per  "
                . "JOIN per.ctlEmpresa emp "
                . " WHERE emp.id=" . $ctlEmpresaId
                . " AND url.estado=1";

        $result_url = $em->createQuery($dqlUrl)->getArrayResult();



        return $this->render('ctlempresa/perfilEmpresa.html.twig', array(
                    'ctlEmpresa' => $result_empresa,
                    'abgFoto' => $result_foto,
                    'ctlEmpresaId' => $ctlEmpresaId,
                    'empleados' => $registro_empleados,
                    'tipoEmpresa' => $registro_tipoempresa,
                    'abgPersona' => $result_persona,
                    'visitas' => $valor,
                    'RegistroEspecialida' => $result_especialida,
                    'url' => $result_url,
        ));
    }

    /**
     * @Route("/ingresar_usuarioEmpresa/", name="ingresar_usuarioEmpresa", options={"expose"=true})
     * @Method("POST")
     */
    public function RegistrarUsuarioAction(Request $request) {



        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if ($isAjax) {


            $em = $this->getDoctrine()->getManager();
            $datos = $this->get('request')->request->get('frm');
            $frm = json_decode($datos);

            $ctlEmpresa = new CtlEmpresa();
            $abgPersona = new AbgPersona();
            $ctlUsuario = new CtlUsuario();
            $abgFoto = new AbgFoto();



            $nombreAbogado = $frm->txtnombre;
            $apellidoAbogado = $frm->txtapellido;
            $correoUsuario = $frm->correoEmpresa;
            $contrasenha = $frm->contrasenha;

            //Ingreso de una persona

            $codigo = $this->generarIdClienteAbogado();
            $abgPersona->setNombres($nombreAbogado);
            $abgPersona->setApellido($apellidoAbogado);
            $abgPersona->setCorreoelectronico($correoUsuario);
            $abgPersona->setFechaIngreso(new \DateTime("now"));
            $abgPersona->setEstado(0);
            $abgPersona->setCodigo($codigo);
            $abgPersona->setVerificado(0);
            $em->persist($abgPersona);

            $em->flush();
            $idPersona = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:AbgPersona')->find($abgPersona->getId());


            //Ingreso null de una empresa

            $ctlEmpresa->setNombreEmpresa('Nombre de la empresa');
            $ctlEmpresa->setCtlCiudad(null);
            $ctlEmpresa->setCtlTipoEmpresa(null);
            $ctlEmpresa->setMovil('00000000');
            $ctlEmpresa->setTelefono('00000000');
            $ctlEmpresa->setSitioWeb('Sitio Web');
            $ctlEmpresa->setServicios(null);
            $ctlEmpresa->setNit(null);
            $ctlEmpresa->setFotoPerfil(null);
            $ctlEmpresa->setFechaFundacion(null);
            $ctlEmpresa->setFax(null);
            $ctlEmpresa->setDireccion('Direccion empresa');
            $ctlEmpresa->setDescripcion(null);
            $ctlEmpresa->setCorreoelectronico('ejemplo@ejemplo.com');
            $ctlEmpresa->setColor("#000035");

            $ctlEmpresa->setCantidadEmpleados(null);
            $ctlEmpresa->setLatitude(13.70036411285400400000);
            $ctlEmpresa->setLongitude(-89.22023010253906000000);
            $ctlEmpresa->setListaEmpleado(1);

            $ctlEmpresa->setUrlPermiso(0);
            $em->persist($ctlEmpresa);
            $em->flush();

            $idEmpresa = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlEmpresa')->find($ctlEmpresa->getId());


            //Ingreso de un usuario
            $rol = $em->getRepository('DGAbgSistemaBundle:CtlRol')->find(2);

            $ctlUsuario->setUsername($correoUsuario);
            $ctlUsuario->setPassword($contrasenha);
            $ctlUsuario->setEstado('1');
            $ctlUsuario->setRhPersona($idPersona);
            $ctlUsuario->setCtlEmpresa($idEmpresa);
            $ctlUsuario->addCtlRol($rol);
            $this->setSecurePassword($ctlUsuario, $contrasenha);
            $em->persist($ctlUsuario);
            $em->flush();


            //Creo el registro de visitas de los perfiles publicos de empresa y persona

            $visitasE = new AbgVisitas();
            $visitasE->setAbgPersona(null);
            $visitasE->setCtlEmpresa($idEmpresa);
            $visitasE->setVisita(0);
            $visitasE->setVisitaUnica(0);
            $em->persist($visitasE);
            $em->flush();


            $visitasP = new AbgVisitas();
            $visitasP->setAbgPersona($idPersona);
            $visitasP->setCtlEmpresa(null);
            $visitasP->setVisita(0);
            $visitasP->setVisitaUnica(0);
            $em->persist($visitasP);
            $em->flush();

            //Creacion de las registros de la URL personalizada
            //Ingreso de url de la empresa

            $url = $this->generarCorrelativoEmpresa();
            $urlPerE = new AbgUrlPersonalizada();
            $urlPerE->setCtlEmpresa($idEmpresa);
            $urlPerE->setAbgPersonas(null);
            $urlPerE->setEstado(1);
            $urlPerE->setUrl($url);
            $em->persist($urlPerE);
            $em->flush();



            //Ingreso de la url del abogado

            $urlA = $this->generarCorrelativoAbogado();

            $urlPerA = new AbgUrlPersonalizada();
            $urlPerA->setCtlEmpresa(null);
            $urlPerA->setAbgPersonas($idPersona);
            $urlPerA->setEstado(1);
            $urlPerA->setUrl($urlA);
            $em->persist($urlPerA);
            $em->flush();






//            $em->getConnection()->commit();
//            $em->close();
            //Insercion del registro de la foto de la empresa
            //Ojo que posteriormente tengo que sacar los valores con el id de la variable de sesion que este presente

            $idEmpresas = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlEmpresa')->find($idEmpresa);
            $abgFotoE = new AbgFoto();
            //Aqui termina lo del id
            $abgFotoE->setAbgPersona(null);
            $abgFotoE->setCtlEmpresa($idEmpresas);
            $abgFotoE->setTipoFoto(1);
            $abgFotoE->setSrc('Photos/defecto/defecto.png');
            $abgFotoE->setFechaRegistro(null);
            $abgFotoE->setFechaExpiracion(null);
            $abgFotoE->setEstado(1);
            $em->persist($abgFotoE);
            $em->flush();


            $abgFoto->setAbgPersona($idPersona);
            $abgFoto->setCtlEmpresa(null);
            $abgFoto->setTipoFoto(0);
            $abgFoto->setSrc('Photos/defecto/defecto.png');
            $abgFoto->setFechaRegistro(null);
            $abgFoto->setFechaExpiracion(null);
            $abgFoto->setEstado(1);
            $em->persist($abgFoto);
            $em->flush();




            $data['username'] = $ctlUsuario->getUsername();
            $data['estado'] = true;

            return new Response(json_encode($data));
        } else {


            $data['estado'] = false;
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/validar_correo/", name="validar_correo", options={"expose"=true})
     * @Method("POST")
     */
    public function ValidarCorreoAction(Request $request) {

        $isAjax = $this->get('Request')->isXMLhttpRequest();

        if ($isAjax) {

            $em = $this->getDoctrine()->getManager();
            $response = new JsonResponse();
            $datos = $this->get('request')->request->get('frm');
            $frm = json_decode($datos);

            $correo = $frm->correoEmpresa;

            $dqlEmp = "SELECT COUNT(emp.id) AS res FROM DGAbgSistemaBundle:CtlEmpresa emp WHERE"
                    . " emp.correoelectronico = :correo ";

            $dqlPer = "SELECT COUNT(per.id) AS resp FROM DGAbgSistemaBundle:AbgPersona per WHERE"
                    . " per.correoelectronico = :correo ";


            $resultadoEmpresa = $em->createQuery($dqlEmp)
                    ->setParameters(array('correo' => $correo))
                    ->getResult();


            $resultadoPersona = $em->createQuery($dqlPer)
                    ->setParameters(array('correo' => $correo))
                    ->getResult();



            $rp = $resultadoPersona[0]['resp'];
            $re = $resultadoEmpresa[0]['res'];

            $num = $rp + $re;

            if ($num == 0) {

                $data = true;
            } else {

                $data = false;
            }

            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("ingresar_foto/get", name="ingresar_foto", options={"expose"=true})
     * @Method("POST")
     */
    public function IngresarFotoAction() {

        $request = $this->getRequest();
        //data es el valor de retorno de ajax donde puedo ver los valores que trae dependiendo de las instrucciones que hace dentro del controlador


        $path2 = $this->container->getParameter('photo.perfil.temporal');
        $horaFecha = date('Y-m-d His');
        $nombreTemporal = $horaFecha;
        $nombreTemporal = str_replace(" ", "", $nombreTemporal);

        define('UPLOAD_DIR', $path2);
        $img = $request->get('imageDatas');
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);

        $data = base64_decode($img);
        $file = UPLOAD_DIR . $nombreTemporal . '.png';
        $success = file_put_contents($file, $data);
        /*    var_dump($request->get('imageDatas'));
          var_dump($success);
          exit(); */
        $nombreTemporal = $nombreTemporal . '.png';



        if ($success) {

            $EmpresaId = $request->get("empresaId");




            if ($nombreTemporal != null) {

                //Direccion fisica del la imagen  
                $path1 = $this->container->getParameter('photo.perfil');

                $path = "Photos/Perfil/E";
                $nombreArchivo = $nombreTemporal;

                $nombreBASE = $path . $nombreArchivo;

                $nombreSERVER = $nombreArchivo;

                //Codigo para poder redimensionar la  imagenes que se suben
                \Tinify\setKey("H9jR26ywRdh6J3Es7TXAPjIRAz5xuQHZ");

                $source = \Tinify\fromFile($path2 . $nombreSERVER);
                $resized = $source->resize(array(
                    "method" => "cover",
                    "width" => 300,
                    "height" => 300
                ));


                $resultado = $resized->toFile($path1 . "E" . $nombreSERVER);
                $numero = unlink($path2 . $nombreSERVER);

                if ($resultado) {
                    $ctlEmpresa = new CtlEmpresa();
                    $foto = new AbgFoto();
                    $em = $this->getDoctrine()->getManager();
                    $idEmpresa = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlEmpresa')->find($EmpresaId);
                    $src = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:AbgFoto')->findBy(array("ctlEmpresa" => $idEmpresa, "tipoFoto" => 1));
                    $direccion = $src[0]->getSrc();

                    $direccion = str_replace("\\", "", $direccion);
                    $direccion = str_replace("Photos/Perfil/", "", $direccion);

                    if ($direccion != '' && $direccion != 'Photos/defecto/defecto.png') {
                        $eliminacionRegistroExixtente = unlink($path1 . $direccion);

                        if ($eliminacionRegistroExixtente) {

                            $entity = $em->getRepository('DGAbgSistemaBundle:AbgFoto')->findBy(array("ctlEmpresa" => $idEmpresa, "tipoFoto" => 1));
                            $entity[0]->setSrc($nombreBASE);
                            $entity[0]->setFechaRegistro(new \DateTime("now"));
                            $entity[0]->setFechaExpiracion(null);
                            $entity[0]->setEstado(1);
                            $em->merge($entity[0]);
                            $em->flush();
                            $src = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:AbgFoto')->findBy(array("ctlEmpresa" => $idEmpresa, "tipoFoto" => 1));
                            $direcciones = $src[0]->getSrc();
                            $direccionParaAjax = str_replace("\\", "", $direcciones);
                            $datas['direccion'] = $direccionParaAjax;
                            $datas['estado'] = true;
                        }
                    } else {


                        $entity = $em->getRepository('DGAbgSistemaBundle:AbgFoto')->findBy(array("ctlEmpresa" => $idEmpresa, "tipoFoto" => 1));
                        $entity[0]->setSrc($nombreBASE);
                        $entity[0]->setFechaRegistro(new \DateTime("now"));
                        $entity[0]->setFechaExpiracion(null);
                        $entity[0]->setEstado(1);
                        $em->merge($entity[0]);
                        $em->flush();
                        $enti = $em->getRepository('DGAbgSistemaBundle:AbgFoto')->findBy(array("ctlEmpresa" => $idEmpresa, "tipoFoto" => 1));
                        $direcciones = $enti[0]->getSrc();
                        $direccionPara = str_replace("\\", "", $direcciones);
                        $datas['direccion'] = $direccionPara;
                        $datas['estado'] = true;
                    }
                } else {
                    $datas['servidor'] = "No se pudo mover la imagen al servidor";
                }
            } else {
                $datas['imagen'] = "Imagen invalida";
            }
        }

        return new Response(json_encode($datas));
    }

    private function setSecurePassword(&$entity, $contrasenia) {
        $entity->setSalt(md5(time()));
        $encoder = new \Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword($contrasenia, $entity->getSalt());
        $entity->setPassword($password);
    }

    private function comparePassword(&$entity, $contrasenia) {

        $encoder = new \Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword($contrasenia, $entity->getSalt());

        return $password;
    }

    /**
     * @Route("admin/edit/empresa", name="edit_empresa", options={"expose"=true})
     * @Method("POST")
     */
    public function EditPersonaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        try {



            $empresa = $em->getRepository("DGAbgSistemaBundle:CtlEmpresa")->find($request->get('empresa'));


            switch ($request->get('n')) {
                case 0:

                    $nombreEmpresa = ($request->get('nombreEmpresa'));
                    $empresa->setNombreEmpresa($nombreEmpresa);
                    $empresa->setUrlPermiso(1);

                    break;
                case 1:
                    $numeroMovil = $request->get('movil');
                    $empresa->setMovil($numeroMovil);

                    break;
                case 2:
                    $numeroFijo = $request->get('fijo');
                    $empresa->setTelefono($numeroFijo);
                    break;
                case 3:
                    $correoEmpresa = $request->get('correoEmpresa');
                    $empresa->setCorreoelectronico($correoEmpresa);
                    break;
                case 4:
                    $direccionEmpresa = $request->get('direccionEmpresa');
                    $empresa->setDireccion($direccionEmpresa);
                    break;
                case 5:
                    $sitioWebEmpresa = $request->get('sitiowebEmpresa');
                    $empresa->setSitioWeb($sitioWebEmpresa);
                    break;
                case 6:

                    $nombreTipoEmpresa = $request->get('tipoEmpresas');
                    $dato = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlTipoEmpresa')->findBy(array("tipoEmpresa" => $nombreTipoEmpresa));
                    $idTipoEmpresa = $dato[0];
                    $empresa->setCtlTipoEmpresa($idTipoEmpresa);
                    break;

                case 7:
                    $anhoFundacion = $request->get('anhoFundacion');


                    $sql = "select YEAR(NOW()) as num";

                    $stm = $this->container->get('database_connection')->prepare($sql);
                    $stm->execute();
                    $anio = $stm->fetchAll();
                    $numero = $anio[0]['num'];

                    if ($anhoFundacion <= $numero) {


                        $anhoFundacion = $anhoFundacion . "-12-31";
                        $empresa->setFechaFundacion(new \DateTime($anhoFundacion));

                        $data['valor'] = true;
                    } else {
                        $data['valor'] = false;
                    }

                    break;

                case 8:
                    $cantidadEmpleados = $request->get('cantidadEmpleados');
                    $empresa->setCantidadEmpleados($cantidadEmpleados);
                    break;

                case 9:
                    $latitude = $request->get('latitudes');
                    $longitud = $request->get('longitudes');

                    $empresa->setLatitude($latitude);
                    $empresa->setLongitude($longitud);

                    break;
                case 10:
                    $valor = $request->get('valor');
                    $empresa->setListaEmpleado($valor);
                    break;
            }


            $em->merge($empresa);
            $em->flush();

            $data['estado'] = true;

            return new Response(json_encode($data));
        } catch (\Exception $e) {

            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/subespecialidad", name="subespecialidad", options={"expose"=true})
     * @Method("GET")
     */
    public function SubespecialidadAction() {
        try {
            $request = $this->getRequest();
            $em = $this->getDoctrine()->getManager();
            //$idPersona = $this->container->get('security.context')->getToken()->getUser()->getId();


            $Persona = $em->getRepository("DGAbgSistemaBundle:CtlEmpresa")->find($request->get('empresaId'));
            $array = $request->get('DataEspecialida');
            parse_str($request->get('dato'), $datos);


            $RepositorioSubEsp = $em->getRepository("DGAbgSistemaBundle:AbgPersonaEspecialida");
            if (is_null($RepositorioSubEsp->findBy(array('ctlEmpresa' => $request->get('empresaId'))))) {
                
            } else {
                $PersonaSub = $RepositorioSubEsp->findBy(array('ctlEmpresa' => $request->get('empresaId')));
                foreach ($PersonaSub as $obj) {
                    $em->remove($obj);
                    $em->flush();
                }
            }
            if (is_null($array)) {
                
            } else {
                foreach ($array as $obj) {

                    $PersonaEspecialidad = new AbgPersonaEspecialida();
                    $idSub = $em->getRepository("DGAbgSistemaBundle:CtlEspecialidad")->find(intval($obj['0']));
                    $PersonaEspecialidad->setCtlEmpresa($Persona);
                    $PersonaEspecialidad->setCtlEspecialidad($idSub);
                    $PersonaEspecialidad->setDescripcion($datos[$obj['1']]);
                    $em->persist($PersonaEspecialidad);
                    $em->flush();
                }
                $data['msj'] = "Especialida registrada";
                $dql_especialida = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre, pe.descripcion AS descripcion "
                        . " FROM  DGAbgSistemaBundle:CtlEspecialidad e "
                        . "JOIN DGAbgSistemaBundle:AbgPersonaEspecialida pe WHERE e.id=pe.ctlEspecialidad AND pe.ctlEmpresa=" . $request->get('empresaId')
                        . " GROUP by e.id";
                $data['Esp'] = $em->createQuery($dql_especialida)->getArrayResult();
            }




            return new Response(json_encode($data));
        } catch (\Exception $e) {
            $data['error'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/edit/colorEmpresa", name="edit_color", options={"expose"=true})
     * @Method("POST")
     */
    public function EditColorAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        try {



            $empresa = $em->getRepository("DGAbgSistemaBundle:CtlEmpresa")->find($request->get('idEmpresa'));
            $colorEmpresa = $request->get('colorEmpresa');
            $empresa->setColor($colorEmpresa);
            $em->merge($empresa);
            $em->flush();

            $dato = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlEmpresa')->find($empresa);
            $colorBase = $dato->getColor();
            $data['color'] = $colorBase;


            return new Response(json_encode($data));
        } catch (\Exception $e) {

            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("admin/ctlempresa/mostrarTipoEmpresa", name="mostarTipoEmpresa", options={"expose"=true})
     * @Method("POST")
     */
    public function mostarTipoEmpresaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        try {

            $valor = $request->get('exa');
            $dqlTipoEmpresa = "SELECT te.tipoEmpresa as nombre, te.id as id"
                    . " FROM DGAbgSistemaBundle:CtlTipoEmpresa te";

            $dato['valores'] = $em->createQuery($dqlTipoEmpresa)->getResult();


            return new Response(json_encode($dato));
        } catch (\Exception $e) {

            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("admin/ingresar_foto_persona", name="ingresar_foto_persona", options={"expose"=true})
     * @Method("POST")
     */
    public function IngresarFotoPersonaAction() {
        //data es el valor de retorno de ajax donde puedo ver los valores que trae dependiendo de las instrucciones que hace dentro del controlador


        $request = $this->getRequest();
        //data es el valor de retorno de ajax donde puedo ver los valores que trae dependiendo de las instrucciones que hace dentro del controlador

        $path2 = $this->container->getParameter('photo.perfil.temporal');
        $horaFecha = date('Y-m-d His');
        $nombreTemporal = $horaFecha;
        $nombreTemporal = str_replace(" ", "", $nombreTemporal);


        define('UPLOAD_DIR', $path2);
        $img = $request->get('imageDatas');
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $file = UPLOAD_DIR . $nombreTemporal . '.png';
        $success = file_put_contents($file, $data);

        $nombreTemporal = $nombreTemporal . '.png';


        if ($success) {
            $personaId = $request->get("personaId");


            if ($nombreTemporal != null) {


                //Direccion fisica del la imagen  
                $path1 = $this->container->getParameter('photo.perfil');

                $path = "Photos/Perfil/E";
                $nombreArchivo = $nombreTemporal;

                $nombreBASE = $path . $nombreArchivo;

                $nombreSERVER = $nombreArchivo;

                //Codigo para poder redimensionar la  imagenes que se suben
                \Tinify\setKey("H9jR26ywRdh6J3Es7TXAPjIRAz5xuQHZ");

                $source = \Tinify\fromFile($path2 . $nombreSERVER);
                $resized = $source->resize(array(
                    "method" => "cover",
                    "width" => 300,
                    "height" => 300
                ));


                $resultado = $resized->toFile($path1 . "E" . $nombreSERVER);
                $numero = unlink($path2 . $nombreSERVER);


                if ($resultado) {
                    $abgPersona = new AbgPersona();
                    $foto = new AbgFoto();
                    $em = $this->getDoctrine()->getManager();
                    $idPersona = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:AbgPersona')->find($personaId);


                    $src = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:AbgFoto')->findBy(array("abgPersona" => $idPersona->getId(), "tipoFoto" => 0));


                    $direccion = $src[0]->getSrc();

                    $direccion = str_replace("\\", "", $direccion);
                    $direccion = str_replace("Photos/Perfil/", "", $direccion);

                    if ($direccion != "" && $direccion != 'Photos/defecto/defecto.png') {


                        $eliminacionRegistroExixtente = unlink($path1 . $direccion);

                        if ($eliminacionRegistroExixtente) {

                            $entity = $em->getRepository('DGAbgSistemaBundle:AbgFoto')->findBy(array("abgPersona" => $idPersona, "tipoFoto" => 0));
                            $entity[0]->setSrc($nombreBASE);
                            $entity[0]->setFechaRegistro(new \DateTime("now"));
                            $entity[0]->setFechaExpiracion(null);
                            $entity[0]->setEstado(1);
                            $em->merge($entity[0]);
                            $em->flush();
                            $src = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:AbgFoto')->findBy(array("abgPersona" => $idPersona, "tipoFoto" => 0));
                            $direccion = $src[0]->getSrc();
                            $direccionParaAjax = str_replace("\\", "", $direccion);
                            $datas['direccion'] = $direccionParaAjax;
                            $datas['estado'] = true;
                        }
                    } else {
                        $entity = $em->getRepository('DGAbgSistemaBundle:AbgFoto')->findBy(array("abgPersona" => $idPersona, "tipoFoto" => 0));
                        $entity[0]->setSrc($nombreBASE);
                        $entity[0]->setFechaRegistro(new \DateTime("now"));
                        $entity[0]->setFechaExpiracion(null);
                        $entity[0]->setEstado(1);
                        $em->merge($entity[0]);
                        $em->flush();

                        $enti = $em->getRepository('DGAbgSistemaBundle:AbgFoto')->findBy(array("abgPersona" => $idPersona, "tipoFoto" => 0));
                        $direcciones = $enti[0]->getSrc();


                        $direccionPara = str_replace("\\", "", $direcciones);
                        $datas['direccion'] = $direccionPara;
                        $datas['estado'] = true;
                    }
                } else {
                    $datas['servidor'] = "No se pudo mover la imagen al servidor";
                }
            } else {
                $datas['imagen'] = "Imagen invalida";
            }
        }


        return new Response(json_encode($datas));
    }

    public function generarCorrelativoEmpresa() {


        $em = $this->getDoctrine()->getManager();
        $dqlNumerocorrelativo = "SELECT COUNT(u.id) as numero FROM DGAbgSistemaBundle:AbgUrlPersonalizada u"
                . " WHERE u.url like '%EMP%' ";
        $resultCorrelativo = $em->createQuery($dqlNumerocorrelativo)->getArrayResult();
        $numero_base = $resultCorrelativo[0]['numero'];


        $primerLetras = "EMP";
        $valor = "";

        $numero = $numero_base + 1;
        switch (strlen($numero_base)) {
            case 1:
                $valor = $primerLetras.="0000" . $numero;
                break;
            case 2:
                $valor = $primerLetras.="000" . $numero;
                break;
            case 3:
                $valor = $primerLetras.="00" . $numero;
                break;
            case 4:
                $valor = $primerLetras.="0" . $numero;
                break;
            case 5:
                $valor = $primerLetras.=$numero;
                break;
        }
        return $valor;
    }

    public function generarCorrelativoAbogado() {


        $em = $this->getDoctrine()->getManager();
        $dqlNumerocorrelativo = "SELECT COUNT(u.id) as numero FROM DGAbgSistemaBundle:AbgUrlPersonalizada u"
                . " WHERE u.url like '%AB%' ";
        $resultCorrelativo = $em->createQuery($dqlNumerocorrelativo)->getArrayResult();
        $numero_base = $resultCorrelativo[0]['numero'];


        $primerLetras = "AB";
        $valor = "";

        $numero = $numero_base + 1;
        switch (strlen($numero_base)) {
            case 1:
                $valor = $primerLetras.="0000" . $numero;
                break;
            case 2:
                $valor = $primerLetras.="000" . $numero;
                break;
            case 3:
                $valor = $primerLetras.="00" . $numero;
                break;
            case 4:
                $valor = $primerLetras.="0" . $numero;
                break;
            case 5:
                $valor = $primerLetras.=$numero;
                break;
        }
        return $valor;
    }

    /**
     * @Route("/validarUrlPersonalizada/", name="validarUrlPersonalizada", options={"expose"=true})
     * @Method("POST")
     */
    public function ValidarUrlPersonalizada(Request $request) {

        $isAjax = $this->get('Request')->isXMLhttpRequest();

        if ($isAjax) {


            $em = $this->getDoctrine()->getManager();
            $username = $this->container->get('security.context')->getToken()->getUser();

//            $RepositorioPersona = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlUsuario')->findByUsername($username->getUsername()); 
            $abgPersonaId = $username->getRhPersona()->getId();

            $direccion = $request->get('url');


            $dqlEmp = "SELECT COUNT(u.id) AS res FROM DGAbgSistemaBundle:AbgUrlPersonalizada u WHERE"
                    . " u.url = :url ";

            $resultadoBusqueda = $em->createQuery($dqlEmp)
                    ->setParameters(array('url' => $direccion))
                    ->getResult();


            $numero = $resultadoBusqueda[0]['res'];



            $dqlNumeroRegistrosExistentes = "SELECT COUNT(u.id) AS numR FROM DGAbgSistemaBundle:AbgUrlPersonalizada u WHERE"
                    . " u.abgPersona = :abgPersona ";

            $resultadoBusquedas = $em->createQuery($dqlNumeroRegistrosExistentes)
                    ->setParameters(array('abgPersona' => $abgPersonaId))
                    ->getResult();

            $numR = $resultadoBusquedas[0]['numR'];


            //Evaluacion si  ya ingreso la cantidad de veces permitidas

            if ($numR >= 1 && $numR < 3) {

                $data['registro'] = true;
            } else {

                $data['registro'] = false;
            }

            //Evaluacion si la url que viene ya existe dentro del sistema
            if ($numero == 0) {

                $data['estado'] = true;
            } else {

                $data['estado'] = false;
            }

            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/insertarUrl/", name="insertarUrl", options={"expose"=true})
     * @Method("POST")
     */
    public function InsertarUrlPersonalizada(Request $request) {

        $isAjax = $this->get('Request')->isXMLhttpRequest();

        if ($isAjax) {

            $em = $this->getDoctrine()->getManager();
            $username = $this->container->get('security.context')->getToken()->getUser();
            $abgPersonaId = $username->getRhPersona();


            $entity = $em->getRepository('DGAbgSistemaBundle:AbgUrlPersonalizada')->findBy(array("abgPersona" => $abgPersonaId, "estado" => 1));
            $entity[0]->setEstado(0);
            $em->merge($entity[0]);
            $em->flush();



            $direccionUrl = $request->get('url');
            $urlPe = new AbgUrlPersonalizada();
            $urlPe->setCtlEmpresa(null);
            $urlPe->setAbgPersonas($abgPersonaId);
            $urlPe->setEstado(1);
            $urlPe->setUrl($direccionUrl);
            $em->persist($urlPe);
            $em->flush();

            $data['estados'] = true;

            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/evaluarPermisoUrlEmp/", name="evaluarPermisoUrlEmp", options={"expose"=true})
     * @Method("POST")
     */
    public function EvaluarPermisoUrlEmpAction(Request $request) {

        $isAjax = $this->get('Request')->isXMLhttpRequest();

        if ($isAjax) {

            $em = $this->getDoctrine()->getManager();
            $username = $this->container->get('security.context')->getToken()->getUser();


            $ctlEmpresaId = $username->getCtlEmpresa();

            $valor = $ctlEmpresaId->getUrlPermiso();

            if ($valor == 1) {
                $data['estado'] = true;
            } else {
                $data['estado'] = false;
            }


            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/validarUrlPersonalizadaEmpresa/", name="validarUrlPersonalizadaEmpresa", options={"expose"=true})
     * @Method("POST")
     */
    public function ValidarUrlPersonalizadaEmpresa(Request $request) {

        $isAjax = $this->get('Request')->isXMLhttpRequest();

        if ($isAjax) {


            $em = $this->getDoctrine()->getManager();
            $username = $this->container->get('security.context')->getToken()->getUser();

//            $RepositorioPersona = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlUsuario')->findByUsername($username->getUsername()); 


            $ctlEmpresaId = $username->getCtlEmpresa()->getId();
            $direccion = $request->get('url');


            $dqlEmp = "SELECT COUNT(u.id) AS res FROM DGAbgSistemaBundle:AbgUrlPersonalizada u WHERE"
                    . " u.url = :url ";

            $resultadoBusqueda = $em->createQuery($dqlEmp)
                    ->setParameters(array('url' => $direccion))
                    ->getResult();


            $numero = $resultadoBusqueda[0]['res'];



            $dqlNumeroRegistrosExistentes = "SELECT COUNT(u.id) AS numR FROM DGAbgSistemaBundle:AbgUrlPersonalizada u WHERE"
                    . " u.ctlEmpresa = :ctlEmpresa ";

            $resultadoBusquedas = $em->createQuery($dqlNumeroRegistrosExistentes)
                    ->setParameters(array('ctlEmpresa' => $ctlEmpresaId))
                    ->getResult();

            $numR = $resultadoBusquedas[0]['numR'];


            //Evaluacion si  ya ingreso la cantidad de veces permitidas

            if ($numR >= 1 && $numR < 3) {

                $data['registro'] = true;
            } else {

                $data['registro'] = false;
            }

            //Evaluacion si la url que viene ya existe dentro del sistema
            if ($numero == 0) {

                $data['estado'] = true;
            } else {

                $data['estado'] = false;
            }

            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/insertarUrlEmpresa/", name="insertarUrlEmpresa", options={"expose"=true})
     * @Method("POST")
     */
    public function InsertarUrlEmpresa(Request $request) {

        $isAjax = $this->get('Request')->isXMLhttpRequest();

        if ($isAjax) {

            $em = $this->getDoctrine()->getManager();
            $username = $this->container->get('security.context')->getToken()->getUser();
            $ctlEmpresaId = $username->getCtlEmpresa();


            $entity = $em->getRepository('DGAbgSistemaBundle:AbgUrlPersonalizada')->findBy(array("ctlEmpresa" => $ctlEmpresaId, "estado" => 1));
            $entity[0]->setEstado(0);
            $em->merge($entity[0]);
            $em->flush();



            $direccionUrl = $request->get('url');
            $urlPe = new AbgUrlPersonalizada();
            $urlPe->setCtlEmpresa($ctlEmpresaId);
            $urlPe->setAbgPersonas(null);
            $urlPe->setEstado(1);
            $urlPe->setUrl($direccionUrl);
            $em->persist($urlPe);
            $em->flush();

            $data['estados'] = true;

            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("admin/mostrarUrl/", name="mostrarUrl", options={"expose"=true})
     * @Method("POST")
     */
    public function MostrarUrl(Request $request) {

        $isAjax = $this->get('Request')->isXMLhttpRequest();

        if ($isAjax) {

            $em = $this->getDoctrine()->getManager();
            $username = $this->container->get('security.context')->getToken()->getUser();


            $personaId = $username->getRhPersona();


            $entity = $em->getRepository('DGAbgSistemaBundle:AbgUrlPersonalizada')->findBy(array("abgPersona" => $personaId, "estado" => 1));
            $url = $entity[0]->getUrl();

            $numero = "http://www.abogados.com.sv/" . $url;
            $numero = str_replace("\\", "", $numero);
            $data['url'] = $numero;
            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("admin/mostrarUrlBadge/", name="mostrarUrlBadge", options={"expose"=true})
     * @Method("POST")
     */
    public function MostrarUrlBadge(Request $request) {

        $isAjax = $this->get('Request')->isXMLhttpRequest();

        if ($isAjax) {

            $em = $this->getDoctrine()->getManager();
            $username = $this->container->get('security.context')->getToken()->getUser();


            $personaId = $username->getRhPersona();


            $entity = $em->getRepository('DGAbgSistemaBundle:AbgUrlPersonalizada')->findBy(array("abgPersona" => $personaId, "estado" => 1));
            $url = $entity[0]->getUrl();

            $numero = $url;
            $direccionAncla = "http://www.abogados.com.sv/" . $url;

            $numero = str_replace("\\", "", $numero);
            $data['url'] = $numero;
            $data['direccionWeb'] = $direccionAncla;
            return new Response(json_encode($data));
        }
    }

    public function generarIdClienteAbogado() {


        $em = $this->getDoctrine()->getManager();
        $dqlNumerocorrelativoAbogado = "SELECT COUNT(Abg.id) as numero FROM DGAbgSistemaBundle:AbgPersona Abg";
        $resultCorrelativo = $em->createQuery($dqlNumerocorrelativoAbogado)->getArrayResult();
        $numero_baseAbo = $resultCorrelativo[0]['numero'];


        $dqlLetrasPais = "SELECT pa.nombrePais as nombre FROM DGAbgSistemaBundle:CtlPais pa"
                . " WHERE pa.estado=1";
        $resultLetras = $em->createQuery($dqlLetrasPais)->getArrayResult();
        $nombrePais = $resultLetras[0]['nombre'];


        $primerLetras = "";

        switch ($nombrePais) {
            case 'El Salvador':
                $primerLetras = "SV";
                break;
            case 'Nicaragua':
                $primerLetras = "NC";
                break;
            case 'Guatemala':
                $primerLetras = "GT";
                break;
            case 'Costa Rica':
                $primerLetras = "CR";
                break;
            case 'Honduras':
                $primerLetras = "H";
                break;
        }



        $numero = $numero_baseAbo + 1;
        switch (strlen($numero_baseAbo)) {
            case 1:
                $valor = $primerLetras.="0000" . $numero;
                break;
            case 2:
                $valor = $primerLetras.="000" . $numero;
                break;
            case 3:
                $valor = $primerLetras.="00" . $numero;
                break;
            case 4:
                $valor = $primerLetras.="0" . $numero;
                break;
            case 5:
                $valor = $primerLetras.=$numero;
                break;
        }

        return $valor;
    }

//Configuracion de nueva contraseña

    /**
     * @Route("admin/cambiarContra/", name="cambiarContra", options={"expose"=true})
     * @Method("POST")
     */
    public function CambiarContra(Request $request) {

        $isAjax = $this->get('Request')->isXMLhttpRequest();

        if ($isAjax) {

            $em = $this->getDoctrine()->getManager();
            $contraNueva = $request->get('contraNueva');
            $contraVieja = $request->get('contraVieja');


            $username = $this->container->get('security.context')->getToken()->getUser();
            $contraBase = $username->getPassword();



            $resultadoConsulta = $this->comparePassword($username, $contraVieja);


            if ($resultadoConsulta == $contraBase) {

                $this->setSecurePassword($username, $contraNueva);
                $em->persist($username);
                $em->flush();


                $data['estado'] = true;
            } else {
                $data['estado'] = false;
            }

            return new Response(json_encode($data));
        }
    }

    /**
     * Lists all CtlEmpresa entities.
     *
     * @Route("/reset-password/", name="restablecerContra")
     * @Method({"GET", "POST"})
     */
    public function RestablecerContraAction() {

        return $this->render('ctlempresa/ayuda.html.twig', array(
                    'mensaje' => '',
                    'redirect' => 'Login',
                    'header' => '',
        ));
    }

    /**
     * @Route("/validar_correoR/", name="validar_correoR", options={"expose"=true})
     * @Method("POST")
     */
    public function ValidarCorreoForResetAction(Request $request) {

        $isAjax = $this->get('Request')->isXMLhttpRequest();

        if ($isAjax) {

            $em = $this->getDoctrine()->getManager();

            $datos = $request->get('email');


            $dqlPer = "SELECT COUNT(us.id) AS resp FROM DGAbgSistemaBundle:CtlUsuario us WHERE"
                    . " us.username = :username ";

            $resultadoPersona = $em->createQuery($dqlPer)
                    ->setParameters(array('username' => $datos))
                    ->getResult();



            $rp = $resultadoPersona[0]['resp'];

            if ($rp == 0) {

                $data['valido'] = 0;
            } else {

                $data['valido'] = 1;
            }

            return new Response(json_encode($data));
        }
    }

    //Busqueda de perfiles de la empresa en base a la URL

    /**
     * Lists all CtlEmpresa entities.
     *
     * @Route("/{url}", name="busquedaPerfil", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function BusquedaAction($url) {

        $em = $this->getDoctrine()->getManager();
        $ObjetoUrl = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:AbgUrlPersonalizada')->findByUrl($url);
        if (!empty($ObjetoUrl)) {

            $persona = $ObjetoUrl[0]->getAbgPersona();
            $empresa = $ObjetoUrl[0]->getCtlEmpresa();

            if ($empresa != null) {

                //Completo los elementos de las visitas
                $entity = $em->getRepository('DGAbgSistemaBundle:AbgVisitas')->findBy(array("ctlEmpresa" => $empresa->getId()));
                $valor = $entity[0]->getVisita();

                $ctlEmpresaId = $empresa->getId();
                $contador = $valor + 1;
                $entity[0]->setVisita($contador);
                $em->merge($entity[0]);
                $em->flush();

                //Coleccion de datos de la empresa

                $dqlempresa = "SELECT  e.nombreEmpresa AS nombreEmpresa, e.correoelectronico as correoEmpresa, e.direccion, e.sitioWeb,e.movil, e.telefono, e.color,e.cantidadEmpleados ,e.latitude, e.longitude ,"
                        . "date_format(e.fechaFundacion, '%Y') As fechaFundacion"
                        . " FROM DGAbgSistemaBundle:CtlEmpresa e WHERE e.id=" . $ctlEmpresaId;

                $result_empresa = $em->createQuery($dqlempresa)->getArrayResult();

                //Valor de la foto de la empresa

                $dqlfoto = "SELECT fot.src as src "
                        . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.ctlEmpresa=" . $ctlEmpresaId . " and fot.estado=1 and fot.tipoFoto=1";
                $result_foto = $em->createQuery($dqlfoto)->getArrayResult();


                //Array de si se lista o no dentro del perfil de la empresa
                $RepositorioListaEmpresa = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlEmpresa')->find($ctlEmpresaId); //->getRhPersona();
                $lista = $RepositorioListaEmpresa->getListaEmpleado();

                if ($lista) {
                    //Listar los empleados de la empresa
                    $sqlEmpEmp = "SELECT  emp.ctl_empresa_id, per.id as idPersona, per.nombres as nombres, per.apellido as apellido, per.correoelectronico as correoelectronico, "
                    . " per.telefono_fijo as telefonoFijo, per.telefono_movil as telefonoMovil, per.titulo_profesional AS tituloProfesional, "
                            . " per.id, fot.src, sw.nombre AS sitioWeb, per.verificado AS verificado, exp.puesto AS puesto, "
                            . " pesp.ctl_especialidad_id,GROUP_CONCAT(distinct (esp.nombre_especialidad)) AS especialida "
                            . " FROM marvinvi_abg.abg_foto fot "
                            . "JOIN marvinvi_abg.abg_persona per "
                            . "ON fot.abg_persona_id=per.id AND fot.tipo_foto=0 AND fot.tipo_foto <> 5 "
                            . " JOIN marvinvi_abg.abg_persona_empresa emp "
                            . " ON  emp.ctl_empresa_id=" . $ctlEmpresaId . " AND emp.abg_persona_id=per.id "
                            . "JOIN marvinvi_abg.abg_experiencia_laboral exp "
                            . " ON exp.ctl_empresa_id=" . $ctlEmpresaId
                            . " LEFT JOIN marvinvi_abg.abg_sitio_web sw "
                            . " ON per.id = sw.abg_persona_id "
                            . " LEFT JOIN marvinvi_abg.abg_persona_especialidad pesp "
                            . "ON pesp.abg_persona_id = per.id "
                            . "LEFT JOIN marvinvi_abg.ctl_especialidad esp "
                            . "ON pesp.ctl_especialidad_id = esp.id AND esp.id = pesp.ctl_especialidad_id "
                            . " GROUP BY pesp.abg_persona_id "
                            . " ORDER  BY per.nombres ASC";
                    $stm = $this->container->get('database_connection')->prepare($sqlEmpEmp);
                    $stm->execute();
                    $registro_empleados = $stm->fetchAll();
                } else {

                    $registro_empleados = null;
                }

                //valor de los tipos de empresa  
                $dqlTipoEmpresa = "SELECT tipo.tipoEmpresa as tipoEmpresa  "
                        . "FROM DGAbgSistemaBundle:CtlEmpresa emp "
                        . "JOIN emp.ctlTipoEmpresa tipo "
                        . "WHERE emp.id =" . $ctlEmpresaId;

                $registro_tipoempresa = $em->createQuery($dqlTipoEmpresa)->getResult();

                //metodo que me retorna Especialidades
                $dql_especialida = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre, pe.descripcion AS descripcion "
                        . " FROM  DGAbgSistemaBundle:CtlEspecialidad e "
                        . " JOIN DGAbgSistemaBundle:AbgPersonaEspecialida pe WHERE e.id=pe.ctlEspecialidad AND pe.ctlEmpresa=" . $ctlEmpresaId
                        . " GROUP by e.id "
                        . " ORDER BY e.nombreEspecialidad";
                $result_especialida = $em->createQuery($dql_especialida)->getArrayResult();

                //Selccion de las URL personalizadas de los abogados de las empresas
                $dqlUrl = "SELECT per.id as idpersonaUrl, per.nombres, url.url  FROM DGAbgSistemaBundle:AbgUrlPersonalizada url "
                        . "JOIN url.abgPersona per  "
                        . "JOIN per.ctlEmpresa emp "
                        . " WHERE emp.id=" . $ctlEmpresaId
                        . " AND url.estado=1";

                $result_url = $em->createQuery($dqlUrl)->getArrayResult();
//                $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();
//                $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo "
//                        . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
//                $result_persona = $em->createQuery($dql_persona)->getArrayResult();

                return $this->render('ctlempresa/perfilPublico.html.twig', array(
                            'ctlEmpresa' => $result_empresa,
                            'abgFoto' => $result_foto,
                            'ctlEmpresaId' => $ctlEmpresaId,
                            'empleados' => $registro_empleados,
                            'tipoEmpresa' => $registro_tipoempresa,
                            'visitas' => $valor,
                            'RegistroEspecialida' => $result_especialida,
                            'url' => $result_url
                ));
            } else {
// perfil persona
                $em = $this->getDoctrine()->getManager();
                $result_sub = "";
                $result_especialida = "";
                $Experiencia = "";
                $Certificacion = "";
                $Curso = "";

                try {
                    $idPersona = $ObjetoUrl[0]->getAbgPersona()->getId();
                    $dql_persona = "SELECT  p.id AS id, p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo, p.descripcion AS  descripcion,"
                            . " p.direccion AS direccion, p.telefonoFijo AS Tfijo, p.telefonoMovil AS movil, p.estado As estado,  p.tituloProfesional AS tprofesional,"
                            . " p.verificado As verificado "
                            . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
                    $result_persona = $em->createQuery($dql_persona)->getArrayResult();



                    $dql_ciudad = "SELECT c.nombreCiudad As nombre, es.nombreEstado estado"
                            . " FROM DGAbgSistemaBundle:AbgPersona p "
                            . " JOIN DGAbgSistemaBundle:CtlCiudad c WHERE p.ctlCiudad=c.id AND p.id=" . $idPersona
                            . " JOIN DGAbgSistemaBundle:CtlEstado es WHERE es.id=c.ctlEstado ";
                    $result_ciuda = $em->createQuery($dql_ciudad)->getArrayResult();

                    $dql_especialida = "SELECT  e.id AS id, e.nombreEspecialidad AS nombre, pe.descripcion AS descripcion "
                            . " FROM  DGAbgSistemaBundle:CtlEspecialidad e "
                            . " JOIN DGAbgSistemaBundle:AbgPersonaEspecialida pe WHERE e.id=pe.ctlEspecialidad AND pe.abgPersona=" . $idPersona
                            . " GROUP by e.id "
                            . " ORDER BY e.nombreEspecialidad";
                    $result_especialida = $em->createQuery($dql_especialida)->getArrayResult();


                    $sql = "SELECT  el.id AS id, el.puesto AS puesto, el.compania AS empresa, el.funcion AS funcion,"
                            . "f.src AS src, DATEDIFF(el.fecha_fin,el.facha_inicio) AS dias, date_format(el.facha_inicio, '%M %Y') As fechaIn, "
                            . " date_format(el.fecha_fin, '%M %Y') As fechaFin, el.ubicacion AS hubicacion, urle.url AS url "
                            . " FROM  marvinvi_abg.abg_experiencia_laboral el "
                            . " JOIN marvinvi_abg.abg_persona p on p.id=el.abg_persona_id AND el.abg_persona_id=" . $idPersona
                            . " left JOIN marvinvi_abg.ctl_empresa em on em.id=el.ctl_empresa_id "
                            . " left JOIN marvinvi_abg.abg_foto AS f on f.ctl_empresa_id=em.id"
                            . " left JOIN marvinvi_abg.abg_url_personalizada urle ON em.id=urle.ctl_empresa_id "
                            . " GROUP by el.id,el.abg_persona_id,em.id"
                            . " ORDER BY el.facha_inicio Desc";
                    $stm = $this->container->get('database_connection')->prepare($sql);
                    $stm->execute();
                    $Experiencia = $stm->fetchAll();

                    $sqlEdu = "SELECT e.id AS idEs, e.institucion AS institucion, e.titulo AS titulo, e.anio_inicio AS anioIni, e.anio_graduacion AS anio, tp.abg_titulocol AS disciplina "
                            . " FROM marvinvi_abg.abg_estudio e "
                            . " JOIN  marvinvi_abg.abg_persona p ON e.abg_persona_id=p.id AND e.abg_persona_id=" . $idPersona
                            . " JOIN marvinvi_abg.ctl_titulo_profesional tp ON tp.id=e.abg_titulo_profesional_id "
                            . " ORDER BY e.anio_inicio Asc";
                    $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                    $stm->execute();
                    $Edu = $stm->fetchAll();

                    $sqlCert = "SELECT c.id AS id, c.certficacion_nombre AS nombre,c.institucion As institucion, "
                            . " date_format(c.fecha_inicio, '%M %Y') As fechaIn,date_format(c.fecha_fin, '%M %Y') AS fechaFin "
                            . " FROM  marvinvi_abg.abg_certificacion c "
                            . " JOIN marvinvi_abg.abg_persona p on p.id=c.abg_persona_id AND c.abg_persona_id=" . $idPersona
                            . " ORDER BY c.fecha_inicio";
                    $stm = $this->container->get('database_connection')->prepare($sqlCert);
                    $stm->execute();
                    $Certificacion = $stm->fetchAll();

                    $sqlCurso = "SELECT s.id AS id, s.nombre AS nombre,s.institucion As institucion, "
                            . " date_format(s.fecha_incio, '%M %Y') As fechaIn,date_format(s.fecha_fin, '%M %Y') AS fechaFin, s.descripcion AS descripcion "
                            . " FROM  marvinvi_abg.seminario s "
                            . " JOIN marvinvi_abg.abg_persona p on p.id=s.abg_persona_id AND s.abg_persona_id=" . $idPersona
                            . " ORDER BY s.fecha_incio";
                    $stm = $this->container->get('database_connection')->prepare($sqlCurso);
                    $stm->execute();
                    $Curso = $stm->fetchAll();

                    $sqlOrg = "SELECT org.id AS id, org.nombre AS nombre,org.puesto As puesto,org.descripcion AS descripcion, "
                            . " date_format(org.fecha_inicio, '%M %Y') As fechaIn,date_format(org.fecha_fin, '%M %Y') AS fechaFin"
                            . " FROM  marvinvi_abg.abg_organizacion org "
                            . " JOIN marvinvi_abg.abg_persona p on p.id=org.abg_persona_id AND org.abg_persona_id=" . $idPersona
                            . " ORDER BY org.fecha_inicio";
                    $stm = $this->container->get('database_connection')->prepare($sqlOrg);
                    $stm->execute();
                    $Organizacion = $stm->fetchAll();

                    $sqlEdu = "SELECT i.id As idIdioma,pi.id AS idPi,i.idioma As nombre, pi.nivel As nivel "
                            . " FROM marvinvi_abg.abg_persona_idioma pi "
                            . " join marvinvi_abg.ctl_idioma i on i.id=pi.ctl_idioma_id "
                            . " join marvinvi_abg.abg_persona p on p.id=pi.abg_persona_id "
                            . " AND p.id=" . $idPersona
                            . " order by i.idioma";
                    $stm = $this->container->get('database_connection')->prepare($sqlEdu);
                    $stm->execute();
                    $Idiomas = $stm->fetchAll();

                    $dql_sitio = "SELECT  w.id AS id, w.nombre AS nombre "
                            . " FROM  DGAbgSistemaBundle:AbgSitioWeb w "
                            . " JOIN DGAbgSistemaBundle:AbgPersona p WHERE p.id=w.abgPersona AND p.id=" . $idPersona;
                    $sitio = $em->createQuery($dql_sitio)->getArrayResult();

                    $dql_url = "SELECT  u.id AS id, u.url AS url "
                            . " FROM  DGAbgSistemaBundle:AbgUrlPersonalizada u "
                            . " JOIN DGAbgSistemaBundle:AbgPersona p WHERE p.id=u.abgPersona AND u.abgPersona=" . $idPersona;
                    $url = $em->createQuery($dql_url)->getArrayResult();

                    //Esta consulta  es la que jala el src de la foto dejela

                    $dqlfoto = "SELECT fot.src as src, fot.estado As estado "
                            . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and (fot.tipoFoto=0 or fot.tipoFoto=1)";
                    $result_foto = $em->createQuery($dqlfoto)->getArrayResult();

                    $dqlfoto = "SELECT fot.src as src, fot.estado As estado "
                            . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . " and fot.estado=1 and fot.tipoFoto=1 ";
                    $fotoP = $em->createQuery($dqlfoto)->getArrayResult();


                    return $this->render('abgpersona/perfilPublico.html.twig', array(
                                'abgPersona' => $result_persona,
                                'active' => 'verperfil',
                                'RegistrosubEsp' => $result_sub,
                                'RegistroEspecialida' => $result_especialida,
                                'RegistradaExperiencia' => $Experiencia,
                                'Edu' => $Edu,
                                'Certificacion' => $Certificacion,
                                'Curso' => $Curso,
                                'Organizacion' => $Organizacion,
                                'Idiomas' => $Idiomas,
                                'sitio' => $sitio,
                                'ciuda' => $result_ciuda,
                                'url' => $url,
                                'abgFoto' => $result_foto
                    ));
                } catch (\Exception $e) {
                    $data['msj'] = $e->getMessage();
                    return new Response(json_encode($data));
                }
            }
        } else {
            var_dump("Lo sentimos mucho esa url no existe");
        }
    }

    /**
     * @Route("/admin/verificacion/{username}", name="verificacion", options={"expose"=true})
     * @Method("GET")
     */
    public function AjustesAction($username) {
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction();
        try {
            //Se agrego la seleccion del campo codigo para en
            $RepositorioPersona = $this->getDoctrine()->getRepository('DGAbgSistemaBundle:CtlUsuario')->findByUsername($username); //->getRhPersona();
            $idPersona = $RepositorioPersona[0]->getRhPersona()->getId();

            $dql_persona = "SELECT  p.nombres AS nombre, p.apellido AS apellido, p.correoelectronico AS correo,p.codigo as codigo "
                    . " FROM DGAbgSistemaBundle:AbgPersona p WHERE p.id=" . $idPersona;
            $result_persona = $em->createQuery($dql_persona)->getArrayResult();

            //Este es la consulta que carga la foto del panel de ajuste de
            $dqlfoto = "SELECT fot.src as src "
                    . " FROM DGAbgSistemaBundle:AbgFoto fot WHERE fot.abgPersona=" . $idPersona . "  and fot.tipoFoto=1";
            $result_foto = $em->createQuery($dqlfoto)->getArrayResult();


            return $this->render('abgpersona/panelVerificacion.html.twig', array(
                        'AbgFoto' => $result_foto,
                        'abgPersona' => $result_persona,
                        'usuario' => $username
            ));
        } catch (\Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
            $em->getConnection()->rollback();
            $em->close();

            // echo $e->getMessage();   
        }
    }

    /**
     * @Route("/verficacionAbogado/get", name="verficacionAbogado", options={"expose"=true})
     * @Method("POST")
     */
    public function VerificacionAbogadoAction(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $idPersona = $this->container->get('security.context')->getToken()->getUser()->getRhPersona()->getId();
            $Persona = $em->getRepository("DGAbgSistemaBundle:AbgPersona")->find($idPersona);
            $carnet = $em->getRepository("DGAbgSistemaBundle:AbgFoto")->findBy(array('abgPersona' => $idPersona, 'tipoFoto' => 5));


            $nombreimagen2 = "";
            if (count($carnet) == 0) {
                $path2 = $this->container->getParameter('photo.verificacion');

                $nombreimagen = $_FILES['imagen']['name'];

                $tipo = $_FILES['imagen']['type'];
                $extension = explode('/', $tipo);
                $nombreimagen2.="." . $extension[1];
                $fecha = date('Y-m-dHis');
                $nombreArchivo = $nombreimagen . "-" . $fecha . $nombreimagen2;
                $nombreSERVER = str_replace(" ", "", $nombreArchivo);

                $resultados = move_uploaded_file($_FILES["imagen"]["tmp_name"], $path2 . $nombreSERVER);

                if ($resultados) {

                    // registar solicitud de verificacion
                    $AbgFoto = new AbgFoto();

                    $path = "Photos/verificacion/";
                    $nombreBASE = $path . $nombreArchivo;

                    $AbgFoto->setAbgPersona($Persona);
                    $AbgFoto->setTipoFoto(5);
                    $AbgFoto->setCtlEmpresa(null);

                    $AbgFoto->setSrc($nombreBASE);
                    $AbgFoto->setFechaRegistro(new \DateTime("now"));
                    $AbgFoto->setFechaExpiracion(null);
                    $AbgFoto->setEstado(0);
                    $em->persist($AbgFoto);
                    $em->flush();

                    $data['estado'] = true;
                } else {
                    $data['estado'] = false;
                }
            } else {

                $AbgFoto = $em->getRepository("DGAbgSistemaBundle:AbgFoto")->find($carnet[0]->getIdargFoto());

                $path2 = $this->container->getParameter('photo.verificacion');

                $nombreimagen = $_FILES['imagen']['name'];

                $tipo = $_FILES['imagen']['type'];
                $extension = explode('/', $tipo);
                $nombreimagen2.="." . $extension[1];
                $fecha = date('Y-m-dHis');
                $nombreArchivo = $nombreimagen . "-" . $fecha . $nombreimagen2;
                $nombreSERVER = str_replace(" ", "", $nombreArchivo);

                $resultados = move_uploaded_file($_FILES["imagen"]["tmp_name"], $path2 . $nombreSERVER);

                if ($resultados) {

                    // registar solicitud de verificacion

                    $path = "Photos/verificacion/";
                    $nombreBASE = $path . $nombreArchivo;


                    $AbgFoto->setCtlEmpresa(null);

                    $AbgFoto->setSrc($nombreBASE);
                    $AbgFoto->setFechaRegistro(new \DateTime("now"));
                    $AbgFoto->setFechaExpiracion(null);
                    $AbgFoto->setEstado(0);
                    $em->persist($AbgFoto);
                    $em->flush();

                    $data['estado'] = true;
                } else {
                    $data['estado'] = false;
                }
            }
            return new Response(json_encode($data));
        } catch (\Exception $e) {
            $data['error'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * Lists all CtlEmpresa entities.
     *
     * @Route("busqueda/contacto", name="contacto")
     * @Method({"GET", "POST"})
     */
    public function ContactoAction(Request $request) {

        return $this->render('ctlempresa/formularioContacto.html.twig', array(
                    'correoAbogado' => $request->get('correo'),
        ));
    }

    /**
     * Lists all CtlEmpresa entities.
     *
     * @Route("busqueda/recomendacion", name="recomendacion")
     * @Method({"GET", "POST"})
     */
    public function RecomedacionAction(Request $request) {

        return $this->render('ctlempresa/recomendacion.html.twig', array(
                    'correoAbogado' => $request->get('correo'),
        ));
    }

}
