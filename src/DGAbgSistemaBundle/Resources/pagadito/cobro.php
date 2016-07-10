<?php
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
require_once('config.php');
require_once('lib/Pagadito.php');

//if (isset($_POST["cantidad1"]) && is_numeric($_POST["cantidad1"]) &&
//    isset($_POST["cantidad2"]) && is_numeric($_POST["cantidad2"]) &&
//    isset($_POST["cantidad3"]) && is_numeric($_POST["cantidad3"])) {
$nombreprod = $_POST["nompro"];
var_dump($_POST["nompro"]);
$cantidad = $_POST["cant"];
//Recuperando parametros personalizados
$param1 = $_POST["param1"];
$param2 = $_POST["param2"];
$param3 = $_POST["param3"];

var_dump($_POST["cant"]);
var_dump($_POST["precio"]);
$precio = $_POST["precio"];
//die();

    /*
     * Lo primero es crear el objeto nusoap_client, al que se le pasa como
     * parámetro la URL de Conexión definida en la constante WSPG
     */
    $Pagadito = new Pagadito(UID, WSK);
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
         * Luego pasamos a agregar los detalles
         */        
        for($i=0; $i<count($cantidad) ; $i++){            
            $Pagadito->add_detail($cantidad[$i], $nombreprod[$i], $precio[$i],"http://beardattitude.com/");            
        }
        $Pagadito->add_detail(1, "Gastos de envio", 5.00,"http://beardattitude.com/");    
                
            
        
        //Agregando campos personalizados de la transacción
        $Pagadito->set_custom_param("param1", $param1);
        $Pagadito->set_custom_param("param2", $param2);
        $Pagadito->set_custom_param("param3", $param3);
        
        //Habilita la recepción de pagos preautorizados para la orden de cobro.
        $Pagadito->enable_pending_payments();

        /*
         * Lo siguiente es ejecutar la transacción, enviandole el ern.
         *
         * A manera de ejemplo el ern es generado como un número
         * aleatorio entre 1000 y 2000. Lo ideal es que sea una
         * referencia almacenada por el Pagadito Comercio.
         */
        $ern = rand(1000, 2000);
        if (!$Pagadito->exec_trans($ern)) {
            /*
             * En caso de fallar la transacción, verificamos el error devuelto.
             * Debido a que la API nos puede devolver diversos mensajes de
             * respuesta, validamos el tipo de mensaje que nos devuelve.
             */
            switch($Pagadito->get_rs_code())
            {
                case "PG2001":
                    /*Incomplete data*/
                case "PG3002":
                    /*Error*/
                case "PG3003":
                    /*Unregistered transaction*/
                case "PG3004":
                    /*Match error*/
                case "PG3005":
                    /*Disabled connection*/
                default:
                    echo "
                        <SCRIPT>
                            alert(\"".$Pagadito->get_rs_code().": ".$Pagadito->get_rs_message()."\");
                            location.href = 'index.php';
                        </SCRIPT>
                    ";
                    break;
            }
        }
    } else {
        /*
         * En caso de fallar la conexión, verificamos el error devuelto.
         * Debido a que la API nos puede devolver diversos mensajes de
         * respuesta, validamos el tipo de mensaje que nos devuelve.
         */
        switch($Pagadito->get_rs_code())
        {
            case "PG2001":
                /*Incomplete data*/
            case "PG3001":
                /*Problem connection*/
            case "PG3002":
                /*Error*/
            case "PG3003":
                /*Unregistered transaction*/
            case "PG3005":
                /*Disabled connection*/
            case "PG3006":
                /*Exceeded*/
            default:
                echo "
                    <SCRIPT>
                        alert(\"".$Pagadito->get_rs_code().": ".$Pagadito->get_rs_message()."\");
                        location.href = 'index.php';
                    </SCRIPT>
                ";
                break;
        }
    }

//} else {
//    echo "
//        <script>
//            alert('No ha llenado los campos adecuadamente.');
//            location.href = 'index.php';
//        </script>
//    ";
//}
