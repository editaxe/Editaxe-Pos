<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$hora = date("H:i:s");
//--------------------------------------------CUANDO LLEGUE EL DATO DE LA FECHA--------------------------------------------//
//--------------------------------------------CUANDO LLEGUE EL DATO DE LA FECHA--------------------------------------------//
if (isset($_POST['fecha_ini']) <> NULL) {

$fecha_ini          = intval($_POST['fecha_ini']);
$fecha_fin          = intval($_POST['fecha_fin']);

$fecha_inicial      = date("d/m/Y", $fecha_ini);
$fecha_final        = date("d/m/Y", $fecha_fin);
//-------------------------------------------- CALCULOS PARA LOS TOTALES DE LAS VENTAS POR CONTADO --------------------------------------------//
$mostrar_datos_sql_venta_contado = "SELECT Sum(ventas.vlr_total_venta-(ventas.vlr_total_venta*(ventas.descuento_ptj/100))) As total_venta_contado_smtr, 
sum(ventas.vlr_total_compra) As vlr_total_compra, Sum(((ventas.precio_venta/((ventas.iva/100)+(100/100)))*(ventas.iva/100))*ventas.unidades_vendidas) As sum_iva 
FROM clientes RIGHT JOIN ventas ON clientes.cod_clientes = ventas.cod_clientes
WHERE (ventas.fecha BETWEEN '$fecha_ini' AND '$fecha_fin')";
$consulta_venta_contado = mysql_query($mostrar_datos_sql_venta_contado, $conectar) or die(mysql_error());
$matriz_venta_contado = mysql_fetch_assoc($consulta_venta_contado);

$total_venta_contado_smtr = $matriz_venta_contado['total_venta_contado_smtr'];

$mostrar_datos_sql_venta_contado_pvar = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As total_venta_contado_smtr_pvar
FROM ventas WHERE (fecha BETWEEN '$fecha_ini' AND '$fecha_fin') AND (detalles = 'PVAR')";
$consulta_venta_contado_pvar = mysql_query($mostrar_datos_sql_venta_contado_pvar, $conectar) or die(mysql_error());
$matriz_venta_contado_pvar = mysql_fetch_assoc($consulta_venta_contado_pvar);

$total_venta_contado_smtr_pvar = $matriz_venta_contado_pvar['total_venta_contado_smtr_pvar'];
//-------------------------------------------- CALCULO PARA LA CAJA --------------------------------------------//
$campo = 'fecha';
//-------------------------------------------- FIN DEL ISSET FECHA --------------------------------------------//
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<center>

<table align="center">
<td nowrap align="right"><font color='yellow' size='+3'>REPORTE VENTAS FECHAS</font></td>
</table>
<br>
<?php //require_once('../admin/menu_reporte_venta.php'); ?>

<form method="post" name="formulario" action="">
<table align="center">
<tr>
<td>FECHA INI: <select name="fecha_ini" id="fecha_ini" required>
<?php if (isset($fecha_ini)) { echo "<option value='' ></option>";
} else { echo  "<option value='' selected></option>"; }
$consulta2_sql = "SELECT fecha, fecha_anyo FROM ventas GROUP BY fecha ORDER BY fecha DESC";
$consulta2 = mysql_query($consulta2_sql, $conectar) or die(mysql_error());
while ($datos2 = mysql_fetch_assoc($consulta2)) {
if(isset($fecha_ini) and $fecha_ini == $datos2['fecha']) {
$seleccionado = "selected"; } else { $seleccionado = ""; }
$codigo = $datos2['fecha'];
$nombre = $datos2['fecha_anyo'];
echo "<option value='".$codigo."' $seleccionado >".$nombre."</option>"; } ?></select></td>

<td>FECHA FIN: <select name="fecha_fin" id="fecha_fin" required>
<?php if (isset($fecha_fin)) { echo "<option value='' ></option>";
} else { echo  "<option value='' selected></option>"; }
$consulta2_sql = "SELECT fecha, fecha_anyo FROM ventas GROUP BY fecha ORDER BY fecha DESC";
$consulta2 = mysql_query($consulta2_sql, $conectar) or die(mysql_error());
while ($datos2 = mysql_fetch_assoc($consulta2)) {
if(isset($fecha_fin) and $fecha_fin == $datos2['fecha']) {
$seleccionado = "selected"; } else { $seleccionado = ""; }
$codigo = $datos2['fecha'];
$nombre = $datos2['fecha_anyo'];
echo "<option value='".$codigo."' $seleccionado >".$nombre."</option>"; } ?></select></td>

<td bordercolor="1"><br><input type="submit" id="boton1" value="Consultar"></td>
</tr>
</table>
</form>
</center>

<?php if (isset($_POST['fecha_ini']) <> NULL) { ?>
<center>
<fieldset><legend><font color='yellow' size='+3'>VENTAS POR VENDEDOR DEL <?php echo $fecha_inicial ?> AL <?php echo $fecha_final ?></font></legend>
<table align="center" width='50%'>
<tr>
<td align="center"><strong>VENDEDOR</strong></td>
<td align="center"><strong>TOTAL VENTA</strong></td>
<td align="center"><strong>FECHA INI</strong></td>
<td align="center"><strong>FECHA FIN</strong></td>
</tr>
<?php
//-------------------------------------------- FITRO PARA LOS DATOS DE LAS VENTAS --------------------------------------------//
$mostrar_datos_sql = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As total_venta_contado, 
Sum(((vlr_total_venta/((iva/100)+(100/100)))*(iva/100))) As sum_iva, fecha, fecha_anyo, vendedor 
FROM ventas WHERE (fecha BETWEEN '$fecha_ini' AND '$fecha_fin') GROUP BY vendedor ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());

while ($datos = mysql_fetch_assoc($consulta)) {
$vendedor               = $datos['vendedor'];
$total_venta_contado    = $datos['total_venta_contado'];
$sum_iva                = $datos['sum_iva'];
?>
<tr>
<td ><?php echo $vendedor ?></td>
<td align="right"><?php echo number_format($total_venta_contado, 0, ",", "."); ?></td>
<td align="center"><?php echo $fecha_inicial; ?></td>
<td align="center"><?php echo $fecha_final; ?></td>
</tr>
<?php } ?>
</table>
</fieldset>
<br>
<table align="center" border="1" width='50%'>
<tr>
<td align="center"><strong><font color='yellow' size='+2'>TOTAL VENTA</font></strong></td>
<td align="center"><strong><font color='yellow' size='+2'><?php echo number_format($total_venta_contado_smtr, 0, ",", ".") ?></font></strong></td>
</tr>
<tr>
<td align="center"><strong><font color='yellow' size='+2'>TOTAL VENTA PVAR</font></strong></td>
<td align="center"><strong><font color='yellow' size='+2'><?php echo number_format($total_venta_contado_smtr_pvar, 0, ",", ".") ?></font></strong></td>
</tr>
<tr>
<td align="center"><strong><font color='yellow' size='+2'>TOTAL VENTA SIN PVAR</font></strong></td>
<td align="center"><strong><font color='yellow' size='+2'><?php echo number_format($total_venta_contado_smtr - $total_venta_contado_smtr_pvar, 0, ",", ".") ?></font></strong></td>
</tr>
</table>
<br>
<fieldset><legend><font color='yellow' size='+3'>VENTAS POR DEPENDENCIA DEL <?php echo $fecha_inicial ?> AL <?php echo $fecha_final ?></font></legend>
<table align="center" width='50%'>
<tr>
<td align="center"><strong>DEPENDENCIA</strong></td>
<td align="center"><strong>TOTAL VENTA</strong></td>
<td align="center"><strong>FECHA INI</strong></td>
<td align="center"><strong>FECHA FIN</strong></td>
</tr>
<?php
//-------------------------------------------- FITRO PARA LOS DATOS DE LAS VENTAS --------------------------------------------//
$mostrar_datos_sql = "SELECT Sum(ventas.vlr_total_venta-(ventas.vlr_total_venta*(ventas.descuento_ptj/100))) As total_venta_contado, 
Sum(((ventas.vlr_total_venta/((ventas.iva/100)+(100/100)))*(ventas.iva/100))) As sum_iva, ventas.fecha, ventas.fecha_anyo, ventas.vendedor, ventas.cod_dependencia, dependencia.nombre_dependencia 
FROM dependencia RIGHT JOIN ventas ON dependencia.cod_dependencia = ventas.cod_dependencia WHERE (fecha BETWEEN '$fecha_ini' AND '$fecha_fin') GROUP BY ventas.cod_dependencia ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());

while ($datos = mysql_fetch_assoc($consulta)) {
$vendedor               = $datos['vendedor'];
$total_venta_contado    = $datos['total_venta_contado'];
$sum_iva                = $datos['sum_iva'];
$nombre_dependencia     = $datos['nombre_dependencia'];
?>
<tr>
<td ><?php echo $nombre_dependencia ?></td>
<td align="right"><?php echo number_format($total_venta_contado, 0, ",", "."); ?></td>
<td align="center"><?php echo $fecha_inicial; ?></td>
<td align="center"><?php echo $fecha_final; ?></td>
</tr>
<?php } ?>
</table>
</fieldset>
<br>
<fieldset><legend><font color='yellow' size='+3'>VENTAS POR CLIENTES DEL <?php echo $fecha_inicial ?> AL <?php echo $fecha_final ?></font></legend>
<table align="center" width='50%'>
<tr>
<td align="center"><strong>NIT CLIENTE</strong></td>
<td align="center"><strong>NOMBRE CLIENTE</strong></td>
<td align="center"><strong>TOTAL VENTA</strong></td>
<td align="center"><strong>FECHA INI</strong></td>
<td align="center"><strong>FECHA FIN</strong></td>
<!--<td align="center"><strong>TOTAL IVA</strong></td>-->
</tr>
<?php
//-------------------------------------------- FITRO PARA LOS DATOS DE LAS VENTAS --------------------------------------------//
$mostrar_datos_sql = "SELECT Sum(ventas.vlr_total_venta-(ventas.vlr_total_venta*(ventas.descuento_ptj/100))) As total_venta_contado, 
Sum(((ventas.vlr_total_venta/((ventas.iva/100)+(100/100)))*(ventas.iva/100))) As sum_iva, 
ventas.fecha, ventas.fecha_anyo, clientes.nombres, clientes.apellidos, clientes.cedula, ventas.cod_clientes 
FROM clientes RIGHT JOIN ventas ON clientes.cod_clientes = ventas.cod_clientes
WHERE (ventas.fecha BETWEEN '$fecha_ini' AND '$fecha_fin') GROUP BY ventas.cod_clientes ORDER BY clientes.nombres ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());

while ($datos = mysql_fetch_assoc($consulta)) {
$cedula = $datos['cedula'];
$nombres = $datos['nombres'];
$apellidos = $datos['apellidos'];
$total_venta_contado = $datos['total_venta_contado'];
$sum_iva = $datos['sum_iva'];
?>
<tr>
<td ><?php echo $cedula; ?></td>
<td ><?php echo $nombres.' '.$apellidos; ?></td>
<td align="right"><?php echo number_format($total_venta_contado, 0, ",", "."); ?></td>
<td align="center"><?php echo $fecha_inicial; ?></td>
<td align="center"><?php echo $fecha_final; ?></td>
<!--<td align="right"><?php echo number_format($sum_iva, 0, ",", "."); ?></td>-->
</tr>
<?php } ?>
</table>
</fieldset>
<br>
<fieldset><legend><font color='yellow' size='+3'>VENTAS GENERALES DEL <?php echo $fecha_inicial ?> AL <?php echo $fecha_final ?></font></legend>
<table width='100%'>
<tr>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>T.P</strong></td>
<td align="center"><strong>P.VENTA</strong></td>
<!--<td align="center"><strong>%DESC</strong></td>-->
<td align="center"><strong>TOTAL</strong></td>
<td align="center"><strong>%IVA</strong></td>
<td align="center"><strong>$IVA</strong></td>
<td align="center"><strong>TIPO PAGO</strong></td>
<td align="center"><strong>DEPENDENCIA</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
<td align="center"><strong>FECHA VENTA</strong></td>
<td align="center"><strong>HORA</strong></td>
</tr>
<?php 
$mostrar_datos_sql = "SELECT * FROM dependencia RIGHT JOIN ventas ON dependencia.cod_dependencia = ventas.cod_dependencia WHERE (ventas.fecha BETWEEN '$fecha_ini' AND '$fecha_fin') ORDER BY ventas.cod_ventas DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());

while ($datos = mysql_fetch_assoc($consulta)) {
$cod_factura = $datos['cod_factura'];
$cod_productos = $datos['cod_productos'];
$nombre_productos = $datos['nombre_productos'];
$unidades_vendidas = $datos['unidades_vendidas'];
$detalles = $datos['detalles'];
$precio_venta = $datos['precio_venta'];
$descuento_ptj = $datos['descuento_ptj'];
$vlr_total_venta = $datos['vlr_total_venta'];
$iva = $datos['iva'];
$tipo_pago = $datos['tipo_pago'];
$nombre_ccosto = $datos['nombre_ccosto'];
$vendedor = $datos['vendedor'];
$fecha_anyo = $datos['fecha_anyo'];
$fecha_hora = $datos['fecha_hora'];
$nombre_dependencia = $datos['nombre_dependencia'];
$subtotal_base = (($vlr_total_venta - (($descuento_ptj/100) * $vlr_total_venta)) / (($iva/100) + (100/100)));
$total_desc = ($vlr_total_venta * ($descuento_ptj/100));
$total_iva = ((($vlr_total_venta - (($descuento_ptj/100) * $vlr_total_venta)) / (($iva/100) + (100/100))) * ($iva/100));
$total_venta_temp = ($vlr_total_venta - ($vlr_total_venta * ($descuento_ptj/100)));
?>
<tr>
<td align="center"><?php echo $cod_factura; ?></td>
<td ><?php echo $cod_productos; ?></td>
<td ><?php echo $nombre_productos; ?></td>
<td align="right"><?php echo $unidades_vendidas; ?></td>
<td align="center"><?php echo $detalles; ?></td>
<td align="right"><?php echo number_format($precio_venta, 0, ",", "."); ?></td>
<!--<td align="right"><?php echo intval($descuento_ptj); ?></td>-->
<td align="right"><?php echo number_format($total_venta_temp, 0, ",", "."); ?></td>
<td align="center"><?php echo intval($iva); ?></td>
<td align="right"><?php echo number_format($total_iva, 0, ",", "."); ?></td>
<td align="center"><?php echo $tipo_pago; ?></td>
<td align="center"><?php echo $nombre_dependencia; ?></td>
<td align="center"><?php echo $vendedor; ?></td>
<td align="center"><?php echo $fecha_anyo; ?></td>
<td align="center"><?php echo $fecha_hora; ?></td>
</tr>
<?php } ?>
</table>
</fieldset>
<br>
<fieldset><legend><font color='yellow' size='+3'>TOTAL VENTAS DEL <?php echo $fecha_inicial ?> AL <?php echo $fecha_final ?></font></legend>
<table width='100%'>
<tr><td align="center"><strong>TOTAL VENTA</td></tr>
<tr><td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($total_venta_contado_smtr, 0, ",", "."); ?></font></td></tr>
<table>
<center>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/reporte_venta_cliente_fechas_dia_xlsx.php?fecha_ini=<?php echo $fecha_ini?>&fecha_fin=<?php echo $fecha_fin?>" target="_blank"><img src=../imagenes/excel.png alt="imprimir"></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<!--
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/imprimir_datos_ventas_agrupado_mes_cliente_pequena.php?fecha=<?php echo $fecha?>&campo=<?php echo $campo?>" target="_blank"><img src=../imagenes/imprimir_1.png alt="imprimir"></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
-->
</table>
</fieldset>
<br>
<?php } else { } ?>
</center>
</body>
</html>