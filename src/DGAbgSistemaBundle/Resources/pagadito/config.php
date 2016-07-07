<?php
/**
 * Mi Tienda Pagadito 1.2 (API PHP)
 *
 * Es un ejemplo de plataforma de e-commerce, que realiza venta de productos
 * electrónicos, y efectúa los cobros utilizando Pagadito, a través del WSPG.
 *
 * config.php
 *
 * Este script es para definir las constantes a usarse en los demás scripts.
 * Estas constantes son utilizadas para la comunicación con el WSPG.
 *
 * LICENCIA: Éste código fuente es de uso libre. Su comercialización no está
 * permitida. Toda publicación o mención del mismo, debe ser referenciada a
 * su autor original Pagadito.com.
 *
 * @author      Pagadito.com <soporte@pagadito.com>
 * @copyright   Copyright (c) 2011, Pagadito.com
 * @version     1.0
 * @link        https://dev.pagadito.com/index.php?mod=docs&hac=wspg
 */

/**
 * UID es la clave que identifica al Pagadito Comercio en Pagadito
 * WSK es la clave de acceso para conectarse con Pagadito
 * SANDBOX es una constante que puede utilizar para conectarse a producción o mantenerse en modo sandbox
 * 
 * Las siguientes constantes deben ser definidas con las credenciales de
 * conexión de su Pagadito Comercio y con la URL de Conexión a Pagadito.
 *
 * Este ejemplo utiliza credenciales de Conexión de Pagadito SandBox.
 *
 * Al momento de pasar a producción, estas deben ser sustituidas por sus
 * equivalentes para conectarse con Pagadito.
 */
define("UID", "0a8584d798ac297a90d6391b38c0e593");
define("WSK", "cfac68c76218e9a4211eb0141eb6c5a0");
define("SANDBOX", true);
