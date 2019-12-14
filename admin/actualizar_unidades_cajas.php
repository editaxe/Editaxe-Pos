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

$cod_temporal          = intval($_GET['cod_temporal']);
$tipo_unidades_cajas   = addslashes($_GET['tipo_unidades_cajas']);
$pagina                = addslashes($_GET['pagina']);

$sql_temporal = "SELECT * FROM temporal WHERE cod_temporal = '$cod_temporal'";
$consulta_temporal = mysql_query($sql_temporal, $conectar) or die(mysql_error());
$temporal = mysql_fetch_assoc($consulta_temporal);

$cod_productos_var     = $temporal['cod_productos'];
$precio_venta          = $temporal['precio_venta'];

$sql_productos = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos_var'";
$consulta_productos = mysql_query($sql_productos, $conectar) or die(mysql_error());
$productos = mysql_fetch_assoc($consulta_productos);

$unidades              = $productos['unidades'];

if ($tipo_unidades_cajas == '1') {

$unidades_cajas         = $unidades;
$unidades_vendidas      = $unidades;
$vlr_total_venta        = $unidades_vendidas * $precio_venta;

$actualizar_sql = "UPDATE temporal SET unidades_cajas = '$unidades_cajas', unidades_vendidas = '$unidades_vendidas', vlr_total_venta = '$vlr_total_venta' WHERE cod_temporal = '$cod_temporal'";
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());

$actualizar_sql = "UPDATE temporal_copia SET unidades_cajas = '$unidades_cajas', unidades_vendidas = '$unidades_vendidas', vlr_total_venta = '$vlr_total_venta' WHERE cod_temporal = '$cod_temporal'";
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; <?php echo $pagina?>">
<?php }

elseif ($tipo_unidades_cajas == '0') {

$unidades_cajas         = $unidades;
$unidades_vendidas      = $unidades;
$vlr_total_venta        = $unidades_vendidas * $precio_venta;

$actualizar_sql = "UPDATE temporal SET unidades_cajas = '$unidades_cajas', unidades_vendidas = '$unidades_vendidas', vlr_total_venta = '$vlr_total_venta' WHERE cod_temporal = '$cod_temporal'";
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());

$actualizar_sql = "UPDATE temporal_copia SET unidades_cajas = '$unidades_cajas', unidades_vendidas = '$unidades_vendidas', vlr_total_venta = '$vlr_total_venta' WHERE cod_temporal = '$cod_temporal'";
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; <?php echo $pagina?>">
<?php }
else {

$unidades_cajas         = 1;
$unidades_vendidas      = 1;
$vlr_total_venta        = $unidades_vendidas * $precio_venta;

$actualizar_sql = "UPDATE temporal SET unidades_cajas = '$unidades_cajas', unidades_vendidas = '$unidades_vendidas', vlr_total_venta = '$vlr_total_venta' WHERE cod_temporal = '$cod_temporal'";
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());

$actualizar_sql = "UPDATE temporal_copia SET unidades_cajas = '$unidades_cajas', unidades_vendidas = '$unidades_vendidas', vlr_total_venta = '$vlr_total_venta' WHERE cod_temporal = '$cod_temporal'";
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; <?php echo $pagina?>">
<?php } 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title></title>
</head>
<body>
</body>
</html>
