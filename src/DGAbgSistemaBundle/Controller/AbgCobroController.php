<?php

namespace DGAbgSistemaBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGAbgSistemaBundle\Entity\AbgPagadito;
include_once 'src/DGAbgSistemaBundle/Resources/pagadito/config.php';
include_once 'src/DGAbgSistemaBundle/Resources/pagadito/lib/Pagadito_php_1.6.php';

/**
 * Cobro controller.
 *
 * @Route("/cobro")
 */
class AbgCobroController extends Controller{
    
    /**
     * Hace la redireccion hacia pagadito para el pago
     *
     * @Route("/", name="cobro", options={"expose"=true})
     * @Method("GET")
     * 
     */
    public function indexAction()
    {
        
        /**
         * Mi Tienda Pagadito 1.2 (API PHP)
         *
         * Es un ejemplo de plataforma de e-commerce, que realiza venta de productos
         * electrónicos, y efectúa los cobros utilizando Pagadito, a través de la API.
         *
         * cobro.php
         *
         * Este script procesa la transacción a petición del script index.php. Se
         * comunica con Pagadito a través de la API, para conectarse y procesar la
         * transacción.
         *
         * LICENCIA: Éste código fuente es de uso libre. Su comercialización no está
         * permitida. Toda publicación o mención del mismo, debe ser referenciada a
         * su autor original Pagadito.com.
         *
         * @author      Pagadito.com <soporte@pagadito.com>
         * @copyright   Copyright (c) 2013, Pagadito.com
         * @version     2.0
         * @link        https://dev.pagadito.com/index.php?mod=docs&hac=wspg
         */
        /**
         * Se incluye el script config.php que contiene las constantes de conexión.
         * También se incluye la API Pagadito.php para realizar la conexión con
         * Pagadito.
         */
        
        if (1) {
            /*
             * Lo primero es crear el objeto nusoap_client, al que se le pasa como
             * parámetro la URL de Conexión definida en la constante WSPG
             */
            $Pagadito = new \Pagadito(UID, WSK);
            /*
             * Si se está realizando pruebas, necesita conectarse con Pagadito SandBox. Para ello llamamos
             * a la función mode_sandbox_on(). De lo contrario omitir la siguiente linea.
             */
            if (SANDBOX) {
                $Pagadito->mode_sandbox_on();
            }
            /*
             * Validamos la conexión llamando a la función connect(). Retorna
             * true si la conexión es exitosa. De lo contrario retorna false
             */
            if ($Pagadito->connect()) {


                /*
                 * Lo siguiente es ejecutar la transacción, enviandole el ern.
                 *
                 * A manera de ejemplo el ern es generado como un número
                 * aleatorio entre 1000 y 2000. Lo ideal es que sea una
                 * referencia almacenada por el Pagadito Comercio.
                 */
            /* Lo siguiente es ejecutar la transacción, enviandole el ern.
            *
            * A manera de ejemplo el ern es generado como un número
            * aleatorio entre 1000 y 2000. Lo ideal es que sea una
            * referencia almacenada por el Pagadito Comercio.
            */
            //Generacion de ern 
            $em = $this->getDoctrine()->getManager();
            $ern22 = $em->getRepository('DGAbgSistemaBundle:AbgPagadito')->find(1);
            //var_dump($ern22);
            //die();
            $ern = $em->getRepository('DGAbgSistemaBundle:AbgErn')->find(1);
            $newvalor = $ern->getValor() + 1;
            $ern->setValor($newvalor);
            $em->merge($ern);
            $em->flush();

            $ern = $em->getRepository('DGAbgSistemaBundle:AbgErn')->find(1);
            $ern = $ern->getValor();

                $Pagadito->add_detail(1, "Inscripci&oacute;n", 0);
                $Pagadito->add_detail(1, "Mensualidad",1.99);

                //Debe enviarse el cobro con fecha de hoy
                $Pagadito->add_pending_charge($ern, "Pago por paquete premium", date("Y-m-d"));
                
                //Habilitar cobros a usuarios sin Cuenta Pagadito
                $Pagadito->enable_pending_payments();

                /**
                 * Se envía la petición de cobro y de autorización para cargos automáticos
                 * Si los datos son correctos se redireccionará automáticamente.
                 * En este ejemplo se envía el segundo parámetro en false para obtener
                 * la url y hacer la redirección de forma controlada.
                 */
                $url_pago = $Pagadito->authorization_recurring_payments("automatic_charges,initial_payment", false);
                if ($url_pago) {
                    header("location: $url_pago");
                    exit();
                } else {
                    echo $Pagadito->get_rs_code() . ": " . $Pagadito->get_rs_message();
                }
            } else {//Fin de la suscripcion
                /*
                 * En caso de fallar la conexión, verificamos el error devuelto.
                 * Debido a que la API nos puede devolver diversos mensajes de
                 * respuesta, validamos el tipo de mensaje que nos devuelve.
                 */
                switch ($Pagadito->get_rs_code()) {
                    case "PG2001":
                    /* Incomplete data */
                    case "PG3001":
                    /* Problem connection */
                    case "PG3002":
                    /* Error */
                    case "PG3003":
                    /* Unregistered transaction */
                    case "PG3005":
                    /* Disabled connection */
                    case "PG3006":
                    /* Exceeded */
                    default:
                        echo "
                    <SCRIPT>
                        alert(\"" . $Pagadito->get_rs_code() . ": " . $Pagadito->get_rs_message() . "\");
                        location.href = 'index.php';
                    </SCRIPT>
                ";
                        break;
                }
            }
        } else {
            echo "
        <script>
            alert('No ha llenado los campos adecuadamente.');
            location.href = 'index.php';
        </script>
    ";
        }
    }//Fin indexAction
    
    
    /**
     * Recibe los valores de la transacion despues del pago
     *
     * @Route("/retornopago", name="retornopago", options={"expose"=true})
     * @Method({"POST","GET"})
     * 
     */
    public function retornoPagoAction(request $request)
    {
        //$parameters = $request->get('ern');
        //$parameters = $request->get('token');
        //$ern = $parameters['ern'];
        //$token = $parameters['token'];
        $ern = 1;
        $token= 9;
                        
        /*******************/
        //Se inicializa el objeto Pagadito
        $Pagadito = new \Pagadito(UID, WSK);

        //Verificando modo sandbox
        if (SANDBOX)
            $Pagadito->mode_sandbox_on();

        //Se establece una conexión con Pagadito
        if ($Pagadito->connect()) {
            //Se consulta el token de autorización
            if ($Pagadito->get_oauth_recurring_payments_token($token)) {
                /*
                 * Este valor representa la cuenta pagadito a la que se le hizo el
                 * cobro. También es el valor con el que se pueden enviar nuevos
                 * cobros. GUARDAR ESTE VALOR
                 */
                $oauth_token = $Pagadito->get_rs_oauth_token();
                echo "Token de autorizaci&oacute;n: " . $oauth_token . "<br />";

                /*
                 * Se obtiene un array con la información del cobro incluyendo el
                 * token del cobro
                 */
                $cobro = $Pagadito->get_rs_pending_charges();
                $token_pending = $cobro[0]->token_pending;

                //creando una nueva conexión para consultar la referencia de pago
                if ($Pagadito->connect(UID, WSK)) {

                    //se consulta la información del pago
                    if ($Pagadito->get_pending_status($token_pending)) {
                        $estado = $Pagadito->get_rs_status();
                        echo "Estado: " . $estado . "<br />";

                        /*
                         * si el cobro ha sido efectuado, se obtiene el número de
                         * aprobación PG la fecha de cobro
                         */
                        if ($estado == "COMPLETED") {
                            /**
                             * Esta es la referencia de Pago de Pagadito.
                             * GUARDAR ESTE VALOR
                             */
                            $numero_aprobacion_pg = $Pagadito->get_rs_reference();
                            $fecha_cobro = $Pagadito->get_rs_date_trans();

                            echo "N&uacute;mero de aprobaci&oacute;n PG: " . $numero_aprobacion_pg . "<br />";
                            echo "Fecha: " . $fecha_cobro . "<br />";
                        }
                    }
                }
            }
        } else {
            echo $Pagadito->get_rs_code() . ": " . $Pagadito->get_rs_message();
        }

        if ($Pagadito->get_oauth_recurring_payments_token($token)) {
            
        }
        
        $sql = "SELECT DATE_ADD( curdate(), INTERVAL 1 MONTH ) as fechacobro";                
        $stm = $this->container->get('database_connection')->prepare($sql);
        $stm->execute();
        $fechacobro = $stm->fetchAll();

        //var_dump($fechacobro[0]['fechacobro']);
        //die();


        /*************************/
                
        //var_dump($parameters);
        //die();
        $em = $this->getDoctrine()->getManager(); 
        $datosgral = new \DGAbgSistemaBundle\Entity\AbgFacturacion();
        
        $datosgral->setFechaPago(New \DateTime(NOW()));
        $datosgral->setMonto(1.99);
        $datosgral->setServicio('Trial');
        $datosgral->setReferencia(' ');
        $datosgral->setDescripcion(' ');        
        $datosgral->setPlazo(30 );
        $datosgral->setDescuento(' ');
        $datosgral->setIdUser(235);                
        $idAbgPersona = $em->getRepository('DGAbgSistemaBundle:AbgPersona')->find(1);
        $datosgral->setAbgPersona($idAbgPersona);                
        $datosgral->setAbgTipoPago(NULL);
        $datosgral->setCtlEmpresa(NULL);
        $datosgral->setCtlPromociones(NULL);       
        
        $em->persist($datosgral);
        $em->flush();
        
        $datospagadito = new AbgPagadito();
        $datospagadito->setErn($ern);
        $datospagadito->setPg($numero_aprobacion_pg); //Numero de aprobacion
        $datospagadito->setToken_auto($oauth_token); 
        $datospagadito->setToken_cobro(NULL);
        $datospagadito->setFechacobro(New \DateTime($fechacobro[0]['fechacobro']));
        $datospagadito->setEstado(1);
        $datospagadito->setPrimercobro(0);
        $datospagadito->setAbgFacturacion($datosgral);
        $em->persist($datospagadito);
        $em->flush();
        return $this->redirect($this->generateUrl('perfil'));
        //die();
                       
    }//Fin retornoPagoAction
    
    
    /***************************************************************************************************************************************************************************/
    /**
     * 
     *
     * @Route("/programarcobro", name="programarcobro", options={"expose"=true})
     * @Method({"POST","GET"})
     * 
     */
    public function programarCobroAction(request $request)
    {
                         
    }//Fin retornoPagoAction    
}