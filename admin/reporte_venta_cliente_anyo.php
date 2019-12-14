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
if (isset($_POST['fecha']) <> NULL) {

$fecha = intval($_POST['fecha']);
//-------------------------------------------- FITRO PARA LOS DATOS DE LAS VENTAS --------------------------------------------//
$mostrar_datos_sql = "SELECT Sum(ventas.vlr_total_venta-(ventas.vlr_total_venta*(ventas.descuento_ptj/100))) As total_venta_contado, 
clientes.nombres, clientes.apellidos, clientes.cedula, ventas.precio_venta, ventas.iva, ventas.anyo, 
ventas.cod_clientes, ventas.cod_factura FROM clientes RIGHT JOIN ventas ON clientes.cod_clientes = ventas.cod_clientes
WHERE ((ventas.anyo)='$fecha') GROUP BY ventas.cod_clientes ORDER BY clientes.nombres ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
//-------------------------------------------- CALCULOS PARA LOS TOTALES DE LAS VENTAS POR CONTADO --------------------------------------------//
$mostrar_datos_sql_venta_contado = "SELECT Sum(ventas.vlr_total_venta-(ventas.vlr_total_venta*(ventas.descuento_ptj/100))) As total_venta_contado_smtr, 
sum(ventas.vlr_total_compra) As vlr_total_compra, Sum(((ventas.precio_venta/((ventas.iva/100)+(100/100)))*(ventas.iva/100))*ventas.unidades_vendidas) As sum_iva 
FROM clientes RIGHT JOIN ventas ON clientes.cod_clientes = ventas.cod_clientes
WHERE ((ventas.anyo)='$fecha')";
$consulta_venta_contado = mysql_query($mostrar_datos_sql_venta_contado, $conectar) or die(mysql_error());
$matriz_venta_contado = mysql_fetch_assoc($consulta_venta_contado);

$total_venta_contado_smtr = $matriz_venta_contado['total_venta_contado_smtr'];
//-------------------------------------------- CALCULO PARA LA CAJA --------------------------------------------//
$campo = 'anyo';
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
<br>
<table align="center">
<td nowrap align="right"><font color='yellow' size='+3'>REPORTE VENTAS CLIENTE ANUAL</font><br></td>
</table>

<br>
<?php require_once('../admin/menu_reporte_venta.php'); ?>
<br>

<form method="post" name="formulario" action="">
<table align="center">
<tr>
<td>A&Ntilde;O: <select name="fecha" id="fecha" required>
<?php if (isset($fecha)) { echo "<option value='' ></option>";
} else { echo  "<option value='' selected></option>"; }
$consulta2_sql = "SELECT anyo FROM ventas GROUP BY anyo ORDER BY anyo DESC";
$consulta2 = mysql_query($consulta2_sql, $conectar) or die(mysql_error());
while ($datos2 = mysql_fetch_assoc($consulta2)) {
if(isset($fecha) and $fecha == $datos2['anyo']) {
$seleccionado = "selected"; } else { $seleccionado = ""; }
$codigo = $datos2['anyo'];
$nombre = $datos2['anyo'];
echo "<option value='".$codigo."' $seleccionado >".$nombre."</option>"; } ?></select></td>

<td bordercolor="1"><br><input type="submit" id="boton1" value="Consultar"></td>
</tr>
</table>
</form>
</center>

<?php if (isset($_POST['fecha']) <> NULL) { ?>
<center>
<fieldset><legend><font color='yellow' size='+3'>TOTAL VENTAS A&Ntilde;O: <?php echo $fecha?></font></legend>
<table width='100%'>
<tr><td align="center"><strong>TOTAL VENTA</td></tr>
<tr><td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($total_venta_contado_smtr, 0, ",", "."); ?></font></td></tr>
<table>
<center>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/reporte_venta_cliente_fechas_anyo_xlsx.php?fecha=<?php echo $fecha?>" target="_blank"><img src=../imagenes/excel.png alt="imprimir"></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/imprimir_datos_ventas_agrupado_anyo_cliente_pequena.php?fecha=<?php echo $fecha?>&campo=<?php echo $campo?>" target="_blank"><img src=../imagenes/imprimir_1.png alt="imprimir"></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</table>
</fieldset>
<br>
<fieldset><legend><font color='yellow' size='+3'>VENTAS A&Ntilde;O: <?php echo $fecha?></font></legend>
<table align="center" width='50%'>
<tr>
<td align="center"><strong>NIT</strong></td>
<td align="center"><strong>CLIENTE</strong></td>
<td align="center"><strong>TOTAL</strong></td>
<td align="center"><strong>A&Ntilde;O</strong></td>
</tr>
<?php 
while ($datos = mysql_fetch_assoc($consulta)) {
$cedula = $datos['cedula'];
$nombres = $datos['nombres'];
$apellidos = $datos['apellidos'];
$anyo = $datos['anyo'];
$total_venta_contado = $datos['total_venta_contado'];
?>
<tr>
<td ><?php echo $cedula; ?></td>
<td ><?php echo $nombres.' '.$apellidos; ?></td>
<td align="right"><?php echo number_format($total_venta_contado, 0, ",", "."); ?></td>
<td align="center"><?php echo $anyo; ?></td>
</tr>
<?php } ?>
</table>
</fieldset>

<br>
<fieldset><legend><font color='yellow' size='+3'>ABONOS A&Ntilde;O: <?php echo $fecha?></font></legend>
<table align="center" width='50%'>
<tr>
<td align="center"><strong>NIT</strong></td>
<td align="center"><strong>CLIENTE</strong></td>
<td align="center"><strong>ABONO</strong></td>
<td align="center"><strong>FECHA</strong></td>
</tr>
<?php
$mostrar_datos_abonos = "SELECT clientes.cedula, clientes.nombres, clientes.apellidos, 
cuentas_cobrar_abonos.abonado, cuentas_cobrar_abonos.fecha_mes, cuentas_cobrar_abonos.fecha_pago
FROM clientes RIGHT JOIN cuentas_cobrar_abonos ON clientes.cod_clientes = cuentas_cobrar_abonos.cod_clientes
WHERE (cuentas_cobrar_abonos.anyo='$fecha') ORDER BY cuentas_cobrar_abonos.fecha_invert DESC";
$consulta_abonos = mysql_query($mostrar_datos_abonos, $conectar) or die(mysql_error());

while ($datos_abonos = mysql_fetch_assoc($consulta_abonos)) {
$cedula = $datos_abonos['cedula'];
$nombres = $datos_abonos['nombres'];
$apellidos = $datos_abonos['apellidos'];
$fecha_mes = $datos_abonos['fecha_mes'];
$abonado = $datos_abonos['abonado'];
$fecha_pago = $datos_abonos['fecha_pago'];
$total_abono_smtr = $abonado + $total_abono_smtr;
?>
<tr>
<td ><?php echo $cedula; ?></td>
<td ><?php echo $nombres.' '.$apellidos; ?></td>
<td align="right"><?php echo number_format($abonado, 0, ",", "."); ?></td>
<td align="center"><?php echo $fecha_pago; ?></td>
</tr>
<?php } ?>
</table>

<table align="center" border="1" width="50%">
<tr>
<td align="center"><strong>TOTAL ABONO</strong></td>
<td align="center"><strong><?php echo number_format($total_abono_smtr, 0, ",", "."); ?></strong></td>
</tr>
</table>
</fieldset>

<?php } else { } ?>
</center>
</body>
</html>