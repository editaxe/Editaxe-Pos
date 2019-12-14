<?php error_reporting(E_ALL ^ E_NOTICE);
ini_set("session.use_only_cookies","1");
ini_set("session.use_trans_sid","0"); 
function conexiones($usuario, $clave) {

$conexion_servidor              = 'localhost';
$base_datos                     = 'base_datos';
$conexion_usuario               = 'conexion_usuario';
$conexion_contrasena_descrip    = "conexion_contrasena_descrip";

$clave_serv                     = stripslashes($conexion_contrasena_descrip);
$clave_serv                     = strip_tags($clave_serv);
$conexion_contrasena            = md5($clave_serv);

$conectar                       = mysql_connect($conexion_servidor, $conexion_usuario, $conexion_contrasena);
mysql_select_db($base_datos ,$conectar);

$buscar_usuario = "SELECT cuenta, contrasena, cod_seguridad, cod_dependencia FROM administrador WHERE cuenta = '$usuario' AND contrasena = '$clave'";
$ejecutar_sql = mysql_query($buscar_usuario, $conectar);
$matriz_admin_info = mysql_fetch_assoc($ejecutar_sql);

$cod_dependencia                = $matriz_admin_info['cod_dependencia'];
$cod_seguridad                  = $matriz_admin_info['cod_seguridad'];

if (mysql_num_rows($ejecutar_sql)!=0) {
//session_name("usuario"); 
$cod_base_caja                  = 1;
session_start();
session_set_cookie_params(0, "/", $HTTP_SERVER_VARS["HTTP_HOST"], 0); 
$_SESSION['usuario']            = $usuario;
$_SESSION['cod_base_caja']      = $cod_base_caja;
$_SESSION['cod_dependencia']    = $cod_dependencia;
$_SESSION['cod_seguridad']      = $cod_seguridad;
return true;
} else {
return false;
	}
}
function verificar_usuario() {
/* Establecemos que las paginas no pueden ser cacheadas */
header("Expires: Tue, 01 Jul 2001 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
//session_name("usuario"); 
session_start();
if ($_SESSION['usuario']) {
session_set_cookie_params(0, "/", $HTTP_SERVER_VARS["HTTP_HOST"], 0); 
return true;		
	}
}
?>