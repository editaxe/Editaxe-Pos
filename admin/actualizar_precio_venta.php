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
$tipo_precio_venta     = addslashes($_GET['tipo_precio_venta']);
$pagina                = addslashes($_GET['pagina']);

$sql_temporal = "SELECT * FROM temporal WHERE cod_temporal = '$cod_temporal'";
$consulta_temporal = mysql_query($sql_temporal, $conectar) or die(mysql_error());
$temporal = mysql_fetch_assoc($consulta_temporal);

$cod_productos_var = $temporal['cod_productos'];
$unidades_vendidas = $temporal['unidades_vendidas'];

$sql_productos = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos_var'";
$consulta_productos = mysql_query($sql_productos, $conectar) or die(mysql_error());
$productos = mysql_fetch_assoc($consulta_productos);

$precio_venta1 = $productos['precio_venta'];
$precio_venta2 = $productos['precio_venta2'];
$precio_venta3 = $productos['precio_venta3'];
$precio_venta4 = $productos['precio_venta4'];
$precio_venta5 = $productos['precio_venta5'];

$vlr_total_venta1 = $unidades_vendidas * $precio_venta1;
$vlr_total_venta2 = $unidades_vendidas * $precio_venta2;
$vlr_total_venta3 = $unidades_vendidas * $precio_venta3;
$vlr_total_venta4 = $unidades_vendidas * $precio_venta4;
$vlr_total_venta5 = $unidades_vendidas * $precio_venta5;

if ($tipo_precio_venta == 'PV1') {
$actualizar_sql = "UPDATE temporal SET detalles = '$tipo_precio_venta', precio_venta = '$precio_venta1', vlr_total_venta = '$vlr_total_venta1' WHERE cod_temporal = '$cod_temporal'";
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());

$actualizar_sql = "UPDATE temporal_copia SET detalles = '$tipo_precio_venta', precio_venta = '$precio_venta1', vlr_total_venta = '$vlr_total_venta1' WHERE cod_temporal = '$cod_temporal'";
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; <?php echo $pagina?>">
<?php }

if ($tipo_precio_venta == 'PV2') {
$actualizar_sql = "UPDATE temporal SET detalles = '$tipo_precio_venta', precio_venta = '$precio_venta2', vlr_total_venta = '$vlr_total_venta2' WHERE cod_temporal = '$cod_temporal'";
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());

$actualizar_sql = "UPDATE temporal_copia SET detalles = '$tipo_precio_venta', precio_venta = '$precio_venta2', vlr_total_venta = '$vlr_total_venta2' WHERE cod_temporal = '$cod_temporal'";
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; <?php echo $pagina?>">
<?php } 

if ($tipo_precio_venta == 'PV3') {
$actualizar_sql = "UPDATE temporal SET detalles = '$tipo_precio_venta', precio_venta = '$precio_venta3', vlr_total_venta = '$vlr_total_venta3' WHERE cod_temporal = '$cod_temporal'";
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());

$actualizar_sql = "UPDATE temporal_copia SET detalles = '$tipo_precio_venta', precio_venta = '$precio_venta3', vlr_total_venta = '$vlr_total_venta3' WHERE cod_temporal = '$cod_temporal'";
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; <?php echo $pagina?>">
<?php }

if ($tipo_precio_venta == 'PV4') {
$actualizar_sql = "UPDATE temporal SET detalles = '$tipo_precio_venta', precio_venta = '$precio_venta4', vlr_total_venta = '$vlr_total_venta4' WHERE cod_temporal = '$cod_temporal'";
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());

$actualizar_sql = "UPDATE temporal_copia SET detalles = '$tipo_precio_venta', precio_venta = '$precio_venta4', vlr_total_venta = '$vlr_total_venta4' WHERE cod_temporal = '$cod_temporal'";
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; <?php echo $pagina?>">
<?php } 

if ($tipo_precio_venta == 'PV5') {
$actualizar_sql = "UPDATE temporal SET detalles = '$tipo_precio_venta', precio_venta = '$precio_venta5', vlr_total_venta = '$vlr_total_venta5' WHERE cod_temporal = '$cod_temporal'";
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());

$actualizar_sql = "UPDATE temporal_copia SET detalles = '$tipo_precio_venta', precio_venta = '$precio_venta5', vlr_total_venta = '$vlr_total_venta5' WHERE cod_temporal = '$cod_temporal'";
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; <?php echo $pagina?>">
<?php } ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title></title>
</head>
<body>
</body>
</html>
