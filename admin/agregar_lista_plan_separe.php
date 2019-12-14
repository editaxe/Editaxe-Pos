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
date_default_timezone_set("America/Bogota");

$vendedor              = addslashes($_SESSION['usuario']);
$cod_productos         = addslashes($_GET['cod_productos']);
$pagina                = addslashes($_GET['pagina']);

$sql_modificar_consulta = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);
$existe_producto_inventario = mysql_num_rows($modificar_consulta);

$maxima_factura = "SELECT Max(cod_plan_separe) AS cod_plan_separe FROM plan_separe_info_impuesto";
$consulta_maxima = mysql_query($maxima_factura, $conectar) or die(mysql_error());
$maxima = mysql_fetch_assoc($consulta_maxima);

$datos_info_factura = "SELECT * FROM plan_separe_info_impuesto WHERE estado = 'abierto' AND vendedor = '$cuenta_actual'";
$consulta_info_factura = mysql_query($datos_info_factura, $conectar) or die(mysql_error());
$info_factura = mysql_fetch_assoc($consulta_info_factura);
$existe_factura_abierta = mysql_num_rows($consulta_info_factura);

$sql_centro_costo = "SELECT * FROM centro_costo WHERE cod_ccosto = '$cod_ccosto'";
$consulta_centro_costo = mysql_query($sql_centro_costo, $conectar) or die(mysql_error());
$datos_centro_costo = mysql_fetch_assoc($consulta_centro_costo);

$cod_plan_separe           = $maxima['cod_plan_separe']+1;
$nombre_productos      = $datos['nombre_productos'];
$unidades_vendidas     = '1';
$cajas                 = '0';
$unidades              = $datos['unidades'];
$unidades_cajas        = $unidades;
$detalles              = $datos['detalles'];
$cod_lineas            = $datos['cod_lineas'];
$precio_compra         = $datos['precio_compra'];
$precio_costo          = $datos['precio_costo'];
$precio_venta          = $datos['precio_venta'];
$vlr_total_venta       = $precio_venta;
$vlr_total_compra      = $precio_compra;
$precio_compra_con_descuento = $precio_venta;
$cod_ccosto            =  $datos['cod_ccosto'];
$tipo_venta            = '0';
$ip                    = $_SERVER['REMOTE_ADDR'];
$porcentaje_vendedor   = $datos['porcentaje_vendedor'];
$iva                   = $datos['iva'];
$iva_v                 = '0';
$descripcion           = $datos['descripcion'];
$estado                = 'abierto';
$descuento_info        = $info_factura['descuento'];
$descuento             = '0';
$cod_clientes          = '1';
$vlr_cancelado         = '';
$vlr_vuelto            = '';
$fecha_dia             = strtotime(date("Y/m/d"));
$anyo                  = date("Y");
$fecha_remision        = date("d/m/Y");
$garantia_meses        = '';
$observacion           = '';
$tipo_pago             = '1';
$fecha_mes             = date("m/Y");
$fecha_anyo            = date("d/m/Y");
$fecha_hora            = date("H:i:s");
$fecha_ini_plan_separe = date("d/m/Y");
$fecha_fin_plan_separe = date("d/m/Y");
$nombre_ccosto         = $datos_centro_costo['nombre_ccosto'];
$cod_plan_separe_info      = $info_factura['cod_plan_separe'];
$tipo_pago_info        = $info_factura['tipo_pago'];
$cod_clientes_info     = $info_factura['cod_clientes'];
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
if (isset($_GET['cod_productos'])) {

if (($existe_factura_abierta == 0) && ($existe_producto_inventario <> 0)) {

$agreg_info_factura = "INSERT INTO plan_separe_info_impuesto (descuento, cod_plan_separe, cod_clientes, vlr_cancelado, vendedor, estado, 
tipo_pago, fecha_dia, fecha_mes, fecha_anyo, anyo, fecha_hora, fecha_remision, nombre_ccosto, garantia_meses, observacion, 
fecha_ini_plan_separe, fecha_fin_plan_separe, total_plan_separe, total_abono_plan_separe, total_saldo_plan_separe) 
VALUES ('$descuento', '$cod_plan_separe', '$cod_clientes', '$vlr_cancelado', '$vendedor', '$estado', 
'$tipo_pago', '$fecha_dia', '$fecha_mes', '$fecha_anyo', '$anyo', '$fecha_hora', '$fecha_remision', '$nombre_ccosto', '$garantia_meses', '$observacion', 
'$fecha_ini_plan_separe', '$fecha_fin_plan_separe', '$total_plan_separe', '$total_abono_plan_separe', '$total_saldo_plan_separe')";
$resultado_info_factura = mysql_query($agreg_info_factura, $conectar) or die(mysql_error());

$sql_data = "INSERT INTO plan_separe_temporal (cod_productos, nombre_productos, unidades_vendidas, unidades_cajas, cajas, detalles, precio_compra, 
precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, precio_compra_con_descuento, descuento, tipo_venta, vendedor, ip, 
cod_plan_separe, tipo_pago, porcentaje_vendedor, iva, iva_v, fecha_mes, fecha_anyo, fecha_hora) 
VALUES ('$cod_productos', '$nombre_productos', '$unidades_vendidas', '$unidades_cajas', '$cajas', '$detalles', '$precio_compra', 
'$precio_compra', '$precio_venta', '$vlr_total_venta', '$vlr_total_compra', '$precio_compra_con_descuento', '$descuento', '$tipo_venta', '$vendedor', '$ip', 
'$cod_plan_separe', '$tipo_pago', '$porcentaje_vendedor', '$iva', '$iva_v', '$fecha_mes', '$fecha_anyo', '$fecha_hora')";
$exec_data = mysql_query($sql_data, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/<?php echo $pagina ?>">
<?php } 
if (($existe_factura_abierta == 1) && ($existe_producto_inventario <> 0)) {

$sql_data = "UPDATE plan_separe_info_impuesto SET fecha_dia = '$fecha_dia', fecha_mes = '$fecha_mes', fecha_anyo = '$fecha_anyo', 
fecha_hora = '$fecha_hora' WHERE vendedor = '$cuenta_actual' AND estado = 'abierto'";
$exec_data = mysql_query($sql_data, $conectar) or die(mysql_error());

$sql_data = "INSERT INTO plan_separe_temporal (cod_productos, nombre_productos, unidades_vendidas, unidades_cajas, cajas, detalles, precio_compra, 
precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, precio_compra_con_descuento, descuento, tipo_venta, vendedor, ip, 
cod_plan_separe, tipo_pago, porcentaje_vendedor, iva, iva_v, fecha_mes, fecha_anyo, fecha_hora) 
VALUES ('$cod_productos', '$nombre_productos', '$unidades_vendidas', '$unidades_cajas', '$cajas', '$detalles', '$precio_compra', 
'$precio_compra', '$precio_venta', '$vlr_total_venta', '$vlr_total_compra', '$precio_compra_con_descuento', '$descuento_info', '$tipo_venta', '$vendedor', '$ip', 
'$cod_plan_separe_info', '$tipo_pago_info', '$porcentaje_vendedor', '$iva', '$iva_v', '$fecha_mes', '$fecha_anyo', '$fecha_hora')";
$exec_data = mysql_query($sql_data, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0; ../admin/<?php echo $pagina ?>">
<?php } ?>
<?php if (($existe_producto_inventario == 0)) { ?>
<br>
<center><font color='white' size= '+2'><img src=../imagenes/advertencia.gif alt='Advertencia'> <strong>EL CODIGO </font><font color='yellow' size= '+2'><?php echo $cod_productos?> </font><font color='white' size= '+2'>NO EXISTE EN EL INVENTARIO.</font></strong> <img src=../imagenes/advertencia.gif alt='Advertencia'><center><br>
<META HTTP-EQUIV="REFRESH" CONTENT="4; ../admin/<?php echo $pagina ?>">
<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='true'></embed>
<?php } } ?>
</body>
</html>