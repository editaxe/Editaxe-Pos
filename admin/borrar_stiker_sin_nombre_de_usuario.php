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
include ("../seguridad/seguridad_diseno_plantillas.php");

$pagina = 0;
$tab = 'stiker_productos_estante_temporal';
$tipo = 'eliminar';

if ($tipo == 'eliminar') {
// --------------------------------------------------------------------------------------------------------------//
if ($tab == 'stiker_productos_estante_temporal') {
$cod_factura = intval($_GET['cod_factura']);

$borrar_sql1 = sprintf("DELETE FROM stiker_productos_estante_temporal WHERE vendedor = ''");
$Result2 = mysql_query($borrar_sql1, $conectar) or die(mysql_error());
}
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; cargar_stiker_productos_estante_temporal_por_cod.php">
<?php } ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
</body>
</html>