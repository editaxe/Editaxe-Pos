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

$cod_clientes = intval($_GET['cod_clientes']);

$sql_consulta_cliente = "SELECT cedula, nombres, apellidos FROM clientes WHERE cod_clientes = '$cod_clientes'";
$consulta_cliente = mysql_query($sql_consulta_cliente, $conectar) or die(mysql_error());
$total_cliente = mysql_fetch_assoc($consulta_cliente);

$cedula     = $total_cliente['cedula'];
$nombres    = $total_cliente['nombres'];
$apellidos  = $total_cliente['apellidos'];


$calcular_datos_cuenta_cobrar = "SELECT cuentas_cobrar.cod_cuentas_cobrar, cuentas_cobrar.cod_factura, cuentas_cobrar.cod_clientes, 
cuentas_cobrar.monto_deuda AS total_venta, clientes.nombres, clientes.apellidos, Sum(cuentas_cobrar_abonos.abonado) AS total_abonado, 
cuentas_cobrar.mensaje, cuentas_cobrar.fecha_pago, cuentas_cobrar.vendedor
FROM cuentas_cobrar_abonos RIGHT JOIN (clientes RIGHT JOIN cuentas_cobrar ON clientes.cod_clientes = cuentas_cobrar.cod_clientes) 
ON cuentas_cobrar_abonos.cod_factura = cuentas_cobrar.cod_factura
GROUP BY cuentas_cobrar.cod_cuentas_cobrar, cuentas_cobrar.cod_factura, cuentas_cobrar.cod_clientes, cuentas_cobrar.monto_deuda, clientes.nombres, clientes.apellidos
HAVING (((cuentas_cobrar.cod_clientes)='$cod_clientes'))  ORDER BY cuentas_cobrar.fecha_invert DESC";
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
<center>
<td><strong><a href="../admin/cuentas_cobrar_mejorada.php"><font color='white' size="5px">REGRESAR</font></a></strong></td><br><br>
<td><strong><font size="5" color='yellow'>CUENTAS POR COBRAR<br><br>
<td><strong><font size="5" color='yellow'>FACTURAS EN CREDITO - <?php echo $nombres.' '.$apellidos;?><br><br>
</center>
<center>
<table width="90%">
<tr>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>CLIENTE</strong></td>
<td align="center"><strong>TOTAL CREDITO</strong></td>
<td align="center"><strong>TOTAL ABONADO</strong></td>
<td align="center"><strong>TOTAL DEUDA</strong></td>
<td align="center"><strong>ABONAR</strong></td>
<td align="center"><strong>PRODUCTOS</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
</tr>
<?php while ($datos_cuenta_cobrar = mysql_fetch_assoc($consulta_datos_cuenta_cobrar)) {
$cod_cuentas_cobrar     = $datos_cuenta_cobrar['cod_cuentas_cobrar'];
$cod_factura            = $datos_cuenta_cobrar['cod_factura'];
$cliente                = $datos_cuenta_cobrar['nombres']." ".$datos_cuenta_cobrar['apellidos'];
$total_venta            = $datos_cuenta_cobrar['total_venta'];
$total_abonado          = $datos_cuenta_cobrar['total_abonado'];
$total_deuda            = $total_venta - $total_abonado;
$mensaje                = $datos_cuenta_cobrar['mensaje'];
$fecha_pago             = $datos_cuenta_cobrar['fecha_pago'];
$vendedor               = $datos_cuenta_cobrar['vendedor'];
?>
<tr>
<td align="center"><font size='3'><?php echo $cod_factura;?></font></td>
<td><font size='3'><?php echo $cliente;?></font></td>
<td align="right"><font size='3'><?php echo number_format($total_venta, 0, ",", ".")?></font></a></td>
<td align="right"><font size='3'><?php echo number_format($total_abonado, 0, ",", ".");?></font></td>
<td align="right"><font color='yellow' size='5'><?php echo number_format($total_deuda, 0, ",", "."); ?></font></td>
<td align="center"><a href="../admin/cuentas_cobrar_abonos.php?cod_factura=<?php echo $cod_factura;?>&cod_clientes=<?php echo $cod_clientes;?>&cliente=<?php echo $cliente;?>"><img src=../imagenes/base_caja.png alt="Abonar"></a></td>
<td align="center"><a href="../admin/productos_fiados.php?cod_factura=<?php echo $cod_factura;?>&cod_clientes=<?php echo $cod_clientes;?>&cliente=<?php echo $cliente;?>"><img src=../imagenes/agregar.png alt="productos"></a></td>
<td align="right"><font size='3'><?php echo $fecha_pago;?></font></td>
<td align="right"><font size='3'><?php echo $vendedor; ?></font></td>
</tr>
<?php } ?>
</table>
