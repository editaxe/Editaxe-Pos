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

$cod_ventas          = intval($_GET['cod_ventas']);
$tipo_precio_venta     = addslashes($_GET['tipo_precio_venta']);
$pagina                = addslashes($_GET['pagina']);

$sql_ventas = "SELECT * FROM ventas WHERE cod_ventas = '$cod_ventas'";
$consulta_ventas = mysql_query($sql_ventas, $conectar) or die(mysql_error());
$ventas = mysql_fetch_assoc($consulta_ventas);

$cod_productos_var = $ventas['cod_productos'];
$unidades_vendidas = $ventas['unidades_vendidas'];
$cod_factura = $ventas['cod_factura'];

$sql_productos = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos_var'";
$consulta_productos = mysql_query($sql_productos, $conectar) or die(mysql_error());
$productos = mysql_fetch_assoc($consulta_productos);

$precio_venta1 = $productos['precio_venta'];
$precio_venta2 = $productos['precio_venta2'];
$precio_venta3 = $productos['precio_venta3'];

$vlr_total_venta1 = $unidades_vendidas * $precio_venta1;
$vlr_total_venta2 = $unidades_vendidas * $precio_venta2;
$vlr_total_venta3 = $unidades_vendidas * $precio_venta3;

if ($tipo_precio_venta == 'PV1') {
$actualizar_sql = "UPDATE ventas SET detalles = '$tipo_precio_venta', precio_venta = '$precio_venta1', vlr_total_venta = '$vlr_total_venta1' WHERE cod_ventas = '$cod_ventas'";
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; <?php echo $pagina?>?cod_factura=<?php echo $cod_factura ?>">
<?php }

if ($tipo_precio_venta == 'PV2') {
$actualizar_sql = "UPDATE ventas SET detalles = '$tipo_precio_venta', precio_venta = '$precio_venta2', vlr_total_venta = '$vlr_total_venta2' WHERE cod_ventas = '$cod_ventas'";
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; <?php echo $pagina?>?cod_factura=<?php echo $cod_factura ?>">
<?php } 

if ($tipo_precio_venta == 'PV3') {
$actualizar_sql = "UPDATE ventas SET detalles = '$tipo_precio_venta', precio_venta = '$precio_venta3', vlr_total_venta = '$vlr_total_venta3' WHERE cod_ventas = '$cod_ventas'";
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; <?php echo $pagina?>?cod_factura=<?php echo $cod_factura ?>">
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
