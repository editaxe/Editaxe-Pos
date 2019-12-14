<?php
$conexion_servidor              = "localhost";
$base_datos                     = "base_datos";
$conexion_usuario               = "conexion_usuario";
$conexion_contrasena_descrip    = "conexion_contrasena_descrip";

$clave                          = stripslashes($conexion_contrasena_descrip);
$clave                          = strip_tags($clave);
$conexion_contrasena            = md5($clave);

$conectar                       = mysql_pconnect($conexion_servidor, $conexion_usuario, $conexion_contrasena) or trigger_error(mysql_error(),E_USER_ERROR); 
?>
