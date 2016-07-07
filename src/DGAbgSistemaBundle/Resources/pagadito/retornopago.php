<?php
session_start();
include_once "../../conexion/def.php"; //Para la coneccion a la base para la donacion

//Obtener la cookie para poder eliminar los item del carrito 
if(isset($_COOKIE["migalleta"])){
  $cookie = $_COOKIE["migalleta"];
  $sql = "DELETE FROM orden WHERE cookie = $cookie";
  $db->get_row($sql);
  setcookie("migalleta", "", time()-3600);
}

echo '<script language = javascript> self.location = "../index.php" </script>';