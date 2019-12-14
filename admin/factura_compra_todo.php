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

require_once("menu_facturas_compra.php");
$fecha = addslashes($_POST['fecha']);
$hora = date("H:i:s");
$campo = 'anyo';

//-------------------------------------------- CALCULO PARA EL TOTAL DE COMPRA --------------------------------------------//
$mostrar_datos_totales = "SELECT sum(precio_compra_con_descuento) AS tot_fact, sum(valor_iva) AS valor_iva, 
sum(vlr_total_venta * cajas) AS vlr_total_ventas FROM facturas_cargadas_inv";
$consulta_totales = mysql_query($mostrar_datos_totales, $conectar) or die(mysql_error());
$totales = mysql_fetch_assoc($consulta_totales);

$tot_fact = $totales['tot_fact'];
$valor_iva = $totales['valor_iva'];
$vlr_total_ventas = $totales['vlr_total_ventas'];

$mostrar_datos_sql = "SELECT * FROM facturas_cargadas_inv";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total_resultados = mysql_num_rows($consulta);
$datos = mysql_fetch_assoc($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<center>
<fieldset><legend><font color='yellow' size='+3'>COMPRAS TOTALES</font></legend>
<table width='40%'>
<br>
<tr>

<td align="center"><strong>TOTAL FACTURA COMPRA</td>
<td align="center"><strong>TOTAL VENT PUBLIC (PROYEC)</td>
</tr>
<tr>
<td align="right"><font color="yellow" size="+2"><strong><?php echo number_format($tot_fact, 0, ",", "."); ?></font></td>
<td align="right"><font color="yellow" size="+2"><strong><?php echo number_format($vlr_total_ventas, 0, ",", "."); ?></font></td>
</tr>
</table>
</center>
</fieldset>
<!--
<table>
<center>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/imprimir_datos_ventas_pequena.php?fecha=<?php echo $fecha?>&campo=<?php echo $campo?>" target="_blank"><img src=../imagenes/imprimir_1.png alt="imprimir"></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/imprimir_datos_ventas_grande.php?fecha=<?php echo $fecha?>&campo=<?php echo $campo?>" target="_blank"><img src=../imagenes/imprimir_.png alt="imprimir"></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</table>
-->
<br>
<fieldset><legend><font color='yellow' size='+3'>COMPRAS</font></legend>
<table width='100%'>
<tr>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>MET</strong></td>
<td align="center"><strong>P.COMPRA</strong></td>
<td align="center"><strong>TOTAL COMPRA</strong></td>
<td align="center"><strong>%IVA</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
<td align="center"><strong>IP</strong></td>
</tr>
<?php 
do { 
$base = $datos['precio_venta']/1.16;
$iva_ptj = $datos['iva']/100;
$unidades_total = $datos['unidades_total'];
$iva_valor = ($base * $iva_ptj) * $unidades_total;
$precio_venta = $datos['precio_venta'];
?>
<tr>
<td ><?php echo $datos['cod_productos']; ?></td>
<td ><?php echo $datos['nombre_productos']; ?></td>
<td align="right"><?php echo $datos['unidades_total']; ?></td>
<td align="center"><?php echo $datos['detalles']; ?></td>
<td align="right"><?php echo number_format($datos['precio_costo'], 0, ",", "."); ?></td>
<td align="right"><?php echo number_format($datos['precio_compra_con_descuento'], 0, ",", "."); ?></td>
<td align="center"><?php echo intval($datos['iva']); ?></td>
<td align="center"><?php echo $datos['cod_factura']; ?></td>
<td align="center"><?php echo $datos['vendedor']; ?></td>
<td align="right"><?php echo $datos['fecha_anyo']; ?></td>
<td align="right"><?php echo $datos['fecha_hora']; ?></td>
<td align="right"><?php echo $datos['ip']; ?></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta));?>
</table>
</fieldset>
</body>
</html>
<center>
<?php
?>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>