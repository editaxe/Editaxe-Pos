<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
$cuenta_actual = addslashes($_SESSION['usuario']);
include("../session/funciones_admin.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
  } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../registro_movimientos/registro_movimientos.php");

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
//include ("../registro_movimientos/registro_cierre_caja.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<?php
$cod_factura = intval($_GET['cod_factura']);
$pagina = $_SERVER['PHP_SELF'];

$sql = "SELECT * FROM ventas WHERE cod_factura = '$cod_factura'";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta);
?>
<br>
<center>
<?php
if ($total_datos <> 0) {
?>
<td><strong><a href="cuentas_cobrar.php"><font color='white'>REGRESAR</font></a></strong></td><br><br>
<table width="90%">
<tr>
<td><div align="center"><strong>FACTURA</strong></div></td>
<td><div align="center"><strong>C&Oacute;DIGO</strong></div></td>
<td><div align="center"><strong>PRODUCTO</strong></div></td>
<td><div align="center"><strong>UND</strong></div></td>
<td><div align="center"><strong>P.VENTA</strong></div></td>
<td><div align="center"><strong>TOTAL</strong></div></td>
<td><div align="center"><strong>VENDEDOR</strong></div></td>
<td><div align="center"><strong>IP</strong></div></td>
<td><div align="center"><strong>FECHA</strong></div></td>
<td><div align="center"><strong>HORA</strong></div></td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
?>
<tr>
<td align="center"><?php echo $datos['cod_factura']; ?></td>
<td ><?php echo $datos['cod_productos']; ?></td>
<td ><?php echo $datos['nombre_productos']; ?></td>
<td align="center"><?php echo $datos['unidades_vendidas']; ?></td>
<td align="right"><?php echo $datos['precio_venta']; ?></td>
<td align="right"><?php echo $datos['vlr_total_venta']; ?></td>
<td align="center"><?php echo $datos['vendedor']; ?></td>
<td align="right"><?php echo $datos['ip']; ?></td>
<td align="right"><?php echo $datos['fecha_anyo']; ?></td>
<td align="right"><?php echo $datos['fecha_hora']; ?></td>
</tr>
<?php }
?>
</table>
<?php
} else {?>
<strong><a href="cuentas_cobrar.php"><font color='white'>REGRESAR</font></a></strong><br><br>
<strong><font color='white'>LOS PRODUCTOS DE ESTA FACTURA AUN NO HAN SIDO ENVIADOS A LAS VENTAS</font></strong>
<?php
}
?>