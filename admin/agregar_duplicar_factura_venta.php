<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual                   = addslashes($_SESSION['usuario']);
$cod_base_caja                   = intval($_SESSION['cod_base_caja']);

include ("../seguridad/seguridad_diseno_plantillas.php");
date_default_timezone_set("America/Bogota");

$vendedor                        = $cuenta_actual;
$cod_factura                     = intval($_GET['cod_factura']);
//$pagina                          = $_GET['pagina'];

$sql_modificar_consulta = "SELECT * FROM ventas WHERE cod_factura = '$cod_factura'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);
$existe_producto_inventario = mysql_num_rows($modificar_consulta);

$datos_info_factura = "SELECT * FROM info_impuesto_facturas WHERE (estado = 'abierto') AND (vendedor = '$cuenta_actual') AND (cod_base_caja = '$cod_base_caja')";
$consulta_info_factura = mysql_query($datos_info_factura, $conectar) or die(mysql_error());
$info_factura = mysql_fetch_assoc($consulta_info_factura);
$existe_factura_abierta = mysql_num_rows($consulta_info_factura);

$unidades_vendidas               = '1';
$cajas                           = '0';
$tipo_venta                      = '0';
$ip                              = $_SERVER['REMOTE_ADDR'];
$iva_v                           = '0';
$descuento_info                  = $info_factura['descuento'];
$descuento                       = '0';
$cod_clientes                    = '1';
$vlr_cancelado                   = '';
$vlr_vuelto                      = '';
$fecha_dia                       = strtotime(date("Y/m/d"));
$anyo                            = date("Y");
$fecha_remision                  = date("d/m/Y");
$garantia_meses                  = '';
$observacion                     = '';
$tipo_pago                       = '1';
$fecha_mes                       = date("m/Y");
$fecha_anyo                      = date("d/m/Y");
$fecha_hora                      = date("H:i:s");
$cod_factura_info                = $info_factura['cod_factura'];
$tipo_pago_info                  = $info_factura['tipo_pago'];
$cod_clientes_info               = $info_factura['cod_clientes'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<br>
<body>
<?php
if (isset($_GET['cod_factura'])) {

// SI NO HAY FACTURA ABIERTA PARA EL USUARIO Y EL PRODUCTO EXISTE EN EL INVENTARIO -  ASIGNAR UNA FACTURA AL USUARIO
if (($existe_factura_abierta == 0)) {

$maxima_factura = "SELECT Max(cod_factura) AS cod_factura FROM info_impuesto_facturas";
$consulta_maxima = mysql_query($maxima_factura, $conectar) or die(mysql_error());
$maxima = mysql_fetch_assoc($consulta_maxima);

$cod_factura_max                 = $maxima['cod_factura']+1;
$estado                          = 'abierto';

$sql_data = "INSERT INTO temporal (cod_productos, nombre_productos, unidades_vendidas, unidades_cajas, cajas, detalles, precio_compra, 
precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, precio_compra_con_descuento, descuento, tipo_venta, vendedor, ip, 
cod_factura, tipo_pago, porcentaje_vendedor, iva, iva_v, fecha_mes, fecha_anyo, fecha_hora, cod_base_caja, cod_dependencia, und_min_precio_venta_desc) 
SELECT ventas.cod_productos, ventas.nombre_productos, ventas.unidades_vendidas, '1', '1', ventas.detalles, ventas.precio_compra, 
ventas.precio_costo, ventas.precio_venta, ventas.vlr_total_venta, ventas.vlr_total_compra, ventas.precio_compra_con_descuento, ventas.descuento, 
ventas.tipo_venta, '$vendedor', '$ip', '$cod_factura_max', ventas.tipo_pago, ventas.porcentaje_vendedor, ventas.iva, ventas.iva_v, 
'$fecha_mes', '$fecha_anyo', '$fecha_hora', '$cod_base_caja', ventas.cod_dependencia, ventas.und_min_precio_venta_desc 
FROM ventas WHERE ventas.cod_factura = '$cod_factura'";
$exec_data = mysql_query($sql_data, $conectar) or die(mysql_error());

$agreg_info_factura = "INSERT INTO info_impuesto_facturas (descuento, cod_factura, cod_clientes, vlr_cancelado, vendedor, estado, 
tipo_pago, fecha_dia, fecha_mes, fecha_anyo, anyo, fecha_hora, fecha_remision, nombre_ccosto, garantia_meses, observacion, cod_base_caja) 
VALUES ('$descuento', '$cod_factura_max', '$cod_clientes', '$vlr_cancelado', '$vendedor', '$estado', 
'$tipo_pago', '$fecha_dia', '$fecha_mes', '$fecha_anyo', '$anyo', '$fecha_hora', '$fecha_remision', '$nombre_ccosto', '$garantia_meses', '$observacion', '$cod_base_caja')";
$resultado_info_factura = mysql_query($agreg_info_factura, $conectar) or die(mysql_error());

echo "0";
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/temporal.php">
<?php } 
// SI HAY FACTURA ABIERTA PARA EL USUARIO Y EL PRODUCTO EXISTE EN EL INVENTARIO -  AGREGAR PRODUCTO AL TEMPORAL DEL USUARIO
if (($existe_factura_abierta == 1)) {

$sql_data = "INSERT INTO temporal (cod_productos, nombre_productos, unidades_vendidas, unidades_cajas, cajas, detalles, precio_compra, 
precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, precio_compra_con_descuento, descuento, tipo_venta, vendedor, ip, 
cod_factura, tipo_pago, porcentaje_vendedor, iva, iva_v, fecha_mes, fecha_anyo, fecha_hora, cod_base_caja, cod_dependencia, und_min_precio_venta_desc) 
SELECT ventas.cod_productos, ventas.nombre_productos, ventas.unidades_vendidas, '1', '1', ventas.detalles, ventas.precio_compra, 
ventas.precio_costo, ventas.precio_venta, ventas.vlr_total_venta, ventas.vlr_total_compra, ventas.precio_compra_con_descuento, ventas.descuento, 
ventas.tipo_venta, '$vendedor', '$ip', '$cod_factura_max', ventas.tipo_pago, ventas.porcentaje_vendedor, ventas.iva, ventas.iva_v, 
'$fecha_mes', '$fecha_anyo', '$fecha_hora', '$cod_base_caja', ventas.cod_dependencia, ventas.und_min_precio_venta_desc 
FROM ventas WHERE ventas.cod_factura = '$cod_factura'";
$exec_data = mysql_query($sql_data, $conectar) or die(mysql_error());

$sql_data = "UPDATE info_impuesto_facturas SET fecha_dia = '$fecha_dia', fecha_mes = '$fecha_mes', fecha_anyo = '$fecha_anyo', 
fecha_hora = '$fecha_hora' WHERE (estado = 'abierto') AND (vendedor = '$cuenta_actual') AND (cod_base_caja = '$cod_base_caja')";
$exec_data = mysql_query($sql_data, $conectar) or die(mysql_error());

echo "1";
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0; ../admin/temporal.php">
<?php } ?>

<?php
} 
?>
</body>
</html>