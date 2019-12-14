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
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

//--------------------------------------------CUANDO LLEGUE EL DATO DE LA FECHA--------------------------------------------//
//--------------------------------------------CUANDO LLEGUE EL DATO DE LA FECHA--------------------------------------------//
if ($_POST['fecha_anyo'] <> NULL) {
$fecha_anyo = addslashes($_POST['fecha_anyo']);
//--------------------------------------------  --------------------------------------------//
//--------------------------------------------  --------------------------------------------//
$vista_reg_venta = "SELECT total_ventas_fisico, total_ventas_sistema, fecha_anyo, usuario FROM caja_registro_fisico 
WHERE (fecha_anyo = '$fecha_anyo') AND usuario = '$cuenta_actual'";
$consulta_vista_reg_venta = mysql_query($vista_reg_venta, $conectar) or die(mysql_error());
$datos_vista_reg_venta = mysql_fetch_assoc($consulta_vista_reg_venta);

$total_venta_caja_fisica = $datos_vista_reg_venta['total_ventas_fisico'];
$total_venta_caja_sistema = $datos_vista_reg_venta['total_ventas_sistema'];
$resta = $total_venta_caja_fisica - $total_venta_caja_sistema;

$mostrar_datos_sql = "SELECT cod_factura, cod_productos, nombre_productos, unidades_vendidas, detalles, precio_venta, vlr_total_venta, vendedor, 
fecha_anyo, fecha_hora, descuento_ptj, tipo_pago 
FROM ventas WHERE fecha_anyo = '$fecha_anyo' AND vendedor = '$cuenta_actual' ORDER BY cod_ventas DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
}
//-------------------------------------------- FIN DEL ISSET FECHA --------------------------------------------//

$sql_data_usuario = "SELECT cod_seguridad FROM administrador WHERE cuenta = '$cuenta_actual'";
$consulta_usuario = mysql_query($sql_data_usuario, $conectar) or die(mysql_error());
$matriz_usuario = mysql_fetch_assoc($consulta_usuario);

$cod_seguridad_user = $matriz_usuario['cod_seguridad'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<br>
<center>
<form method="post" name="formulario" action="">
<table align="center">
<td nowrap align="right">TOTAL DIARIO:</td>
<td bordercolor="0">
<select name="fecha_anyo" id="foco">
<?php 
$sql_consulta1="SELECT fecha, fecha_anyo, usuario FROM caja_registro_fisico WHERE usuario = '$cuenta_actual' GROUP BY fecha DESC";
$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
while ($contenedor = mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['fecha_anyo'] ?>"><?php echo $contenedor['fecha_anyo'].' - '.$contenedor['usuario'] ?></option>
<?php }?>
</select></td></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Consultar"></td>
</tr>
</table>
</form>
</center>
<?php
if ($_POST['fecha_anyo'] <> NULL) { ?>
<center>
<fieldset><legend><font color='yellow' size='+3'>TOTAL <?php echo strtoupper($cuenta_actual) ?> DIA: <?php echo $fecha_anyo ?></font></legend>
<table width='40%'>
<tr>
<?php if ($cod_seguridad_user == 3) { ?> <td align="center"><strong>TOTAL VENTA CAJA SISTEMA</td> <?php } ?>
<td align="center"><strong>TOTAL VENTA CAJA FISICA</td>
<?php if ($cod_seguridad_user == 3) { ?> <td align="center"><strong>RESULTADO</td> <?php } ?>
</tr>
<tr>
<?php if ($cod_seguridad_user == 3) { ?> <td align="center"><font color="yellow" size="+3"><strong><?php echo number_format($total_venta_caja_sistema, 0, ",", "."); ?></font></td><?php } ?>
<td align="center"><font color="yellow" size="+3"><strong><?php echo number_format($total_venta_caja_fisica, 0, ",", "."); ?></font></td>
<?php if ($cod_seguridad_user == 3) { ?> <td align="center"><font color="yellow" size="+3"><strong><?php echo number_format($resta, 0, ",", "."); ?></font></td> <?php } ?>
</tr>
</table>

<br>
<table width='95%'>
<tr>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>MET</strong></td>
<td align="center"><strong>P.VENTA</strong></td>
<td align="center"><strong>%DESC</strong></td>
<td align="center"><strong>TOTAL</strong></td>
<td align="center"><strong>%IVA</strong></td>
<td align="center"><strong>$IVA</strong></td>
<td align="center"><strong>TIPO PAGO</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
<td align="center"><strong>FECHA VENTA</strong></td>
<td align="center"><strong>HORA</strong></td>
</tr>
<?php
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
$vendedor = $datos['vendedor'];
$fecha_anyo = $datos['fecha_anyo'];
$fecha_hora = $datos['fecha_hora'];
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
<td align="right"><?php echo intval($descuento_ptj); ?></td>
<td align="right"><?php echo number_format($total_venta_temp, 0, ",", "."); ?></td>
<td align="center"><?php echo intval($iva); ?></td>
<td align="right"><?php echo number_format($total_iva, 0, ",", "."); ?></td>
<td align="center"><?php echo $tipo_pago; ?></td>
<td align="center"><?php echo $vendedor; ?></td>
<td align="center"><?php echo $fecha_anyo; ?></td>
<td align="center"><?php echo $fecha_hora; ?></td>
</tr>
<?php } ?>
</table>
</fieldset>
</center>
<?php
}
?>