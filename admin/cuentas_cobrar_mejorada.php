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
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$buscar = addslashes($_POST['palabra']);

$calcular_datos_cuenta_cobrar = "SELECT clientes.cod_clientes, clientes.cedula, clientes.nombres, clientes.apellidos, clientes.direccion, clientes.telefono, clientes.ciudad
FROM clientes RIGHT JOIN cuentas_cobrar ON clientes.cod_clientes = cuentas_cobrar.cod_clientes
GROUP BY clientes.cedula, clientes.nombres, clientes.apellidos, clientes.direccion, clientes.telefono, clientes.ciudad ORDER BY clientes.nombres";
$consulta_datos_cuenta_cobrar = mysql_query($calcular_datos_cuenta_cobrar, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta_datos_cuenta_cobrar);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<?php require_once('../busquedas/buscar_cuentas_cobrar.php');?>
<center>
<td><strong><font color='yellow'>CUENTAS POR COBRAR - <a href="../admin/cuentas_cobrar_pagadas.php"><font color='white'>CUENTAS PAGADAS</a><br><br>
</center>
<center>
<table width="70%">
<tr>
<td align="center"><strong>NIT</strong></td>
<td align="center"><strong>CLIENTE</strong></td>
<td align="center"><strong>DIRECCION</strong></td>
<td align="center"><strong>TELEFONO</strong></td>
<td align="center"><strong>CIUDAD</strong></td>
<!--
<td align="center"><strong>TOTAL ABONADO</strong></td>
<td align="center"><strong>TOTAL DEUDA</strong></td>
-->
</tr>
<?php while ($datos_cuenta_cobrar = mysql_fetch_assoc($consulta_datos_cuenta_cobrar)) {
$cod_clientes     = $datos_cuenta_cobrar['cod_clientes'];
$cod_factura      = $datos_cuenta_cobrar['cod_factura'];
$cliente          = $datos_cuenta_cobrar['nombres']." ".$datos_cuenta_cobrar['apellidos'];
$direccion        = $datos_cuenta_cobrar['direccion'];
$telefono         = $datos_cuenta_cobrar['telefono'];
$ciudad           = $datos_cuenta_cobrar['ciudad'];
$cedula           = $datos_cuenta_cobrar['cedula'];
?>
<tr>
<td><font size='3'><a href="../admin/cuentas_cobrar_detalle_factura.php?cod_clientes=<?php echo $cod_clientes; ?>"><?php echo $cedula;?></a></font></td>
<td><font size='3'><a href="../admin/cuentas_cobrar_detalle_factura.php?cod_clientes=<?php echo $cod_clientes; ?>"><?php echo $cliente;?></a></font></td>
<td align="right"><font size='3'><?php echo $direccion; ?></font></td>
<td align="right"><font size='3'><?php echo $telefono; ?></font></td>
<td align="right"><font size='3'><?php echo $ciudad; ?></font></td>
</tr>
<?php } ?>
</table>
