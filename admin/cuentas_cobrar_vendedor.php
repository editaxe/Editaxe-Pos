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
$mostrar_datos_sql = "SELECT * FROM cuentas_cobrar, clientes WHERE (clientes.cod_clientes = cuentas_cobrar.cod_clientes) AND (nombres like '%$buscar%'
OR apellidos like '%$buscar%' OR cedula like '%$buscar%' OR cod_factura like '%$buscar%') Order by fecha_invert DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
$total_datos = mysql_num_rows($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<?php require_once('../busquedas/buscar_cuentas_cobrar.php');
$cliente = $datos['nombres']." ".$datos['apellidos'];

if ($total_datos <> '0') { ?>
<center>
<td><strong><font color='white'>CUENTAS POR COBRAR: </font></strong></td><br><br>
<table width="100%">
<tr>
<td align="center"><strong>CLIENTE</strong></td>
<td align="center"><strong>PAGAR CUENTA</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>VER PRODUCT</strong></td>
<td align="center"><strong>VLR TOTAL</strong></td>
<td align="center"><strong>ABONADO</strong></td>
<td align="center"><strong>DEUDA</strong></td>
<td align="center"><strong>MENSAJE</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
</tr>
<?php do { 
?>
<tr>
<td><font size='4'><?php echo $datos['nombres']." ".$datos['apellidos'];?></font></td>
<td><a href="../modificar_eliminar/modificar_cuentas_cobrar_vendedor.php?cod_factura=<?php echo $datos['cod_factura']; ?>"><center><img src=../imagenes/vender.png alt="Ver"></center></a></td>
<td align="center"><font size='4'><?php echo $datos['cod_factura']; ?></font></td>
<td><a href="../admin/productos_fiados_vendedor.php?cod_factura=<?php echo $datos['cod_factura'];?>&cliente=<?php echo $cliente;?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></center></a></td>
<td align="right"><font size='4'><?php echo $datos['monto_deuda']; ?></font></td>
<td align="right"><font size='4'><?php echo $datos['abonado'];?></font></td>
<td align="right"><font size='4'><?php echo $datos['subtotal']; ?></font></td>
<td align="justify"><font size='4'><?php echo $datos['mensaje']; ?></font></td>
<td align="right"><font size='4'><?php echo $datos['fecha_pago'];?></font></td>
<td align="right"><font size='4'><?php echo $datos['vendedor']; ?></font></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
<?php
} else {
}
?>