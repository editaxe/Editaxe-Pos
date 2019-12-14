<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);

$tab       = addslashes($_POST['tab']);
$tipo      = addslashes($_POST['tipo']);
$campo     = addslashes($_POST['campo']);
//----------------------------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------------------------//
if ($tipo == 'eliminar' && $tab == 'temporal') {
$llave                     = intval($_POST['llave']);

$borrar_sql = sprintf("DELETE FROM $tab WHERE $campo = '$llave'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());
}
elseif ($tipo == 'eliminar' && $tab == '') {
$llave                     = intval($_POST['llave']);

}