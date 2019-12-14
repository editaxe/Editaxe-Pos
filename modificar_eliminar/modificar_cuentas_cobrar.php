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
$edicion_de_formulario = $_SERVER['PHP_SELF'];

$cod_clientes = intval($_GET['cod_clientes']);
$cod_factura = intval($_GET['cod_factura']);
$pagina = $_SERVER['PHP_SELF'];

$calcular_datos_cuenta_cobrar = "SELECT cuentas_cobrar.cod_cuentas_cobrar, cuentas_cobrar.cod_factura, cuentas_cobrar.cod_clientes, 
cuentas_cobrar.monto_deuda  AS total_venta, clientes.nombres, clientes.apellidos, Sum(cuentas_cobrar_abonos.abonado) AS total_abonado
FROM cuentas_cobrar_abonos RIGHT JOIN (clientes RIGHT JOIN cuentas_cobrar ON clientes.cod_clientes = cuentas_cobrar.cod_clientes) 
ON cuentas_cobrar_abonos.cod_factura = cuentas_cobrar.cod_factura
GROUP BY cuentas_cobrar.cod_cuentas_cobrar, cuentas_cobrar.cod_factura, cuentas_cobrar.cod_clientes, cuentas_cobrar.monto_deuda, clientes.nombres, clientes.apellidos
HAVING (((cuentas_cobrar.cod_factura)='$cod_factura'))";
$consulta_datos_cuenta_cobrar = mysql_query($calcular_datos_cuenta_cobrar, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta_datos_cuenta_cobrar);
$datos_cuenta_cobrar = mysql_fetch_assoc($consulta_datos_cuenta_cobrar);

$total_venta     = $datos_cuenta_cobrar['total_venta'];
$total_abonado   = $datos_cuenta_cobrar['total_abonado'];
$total_deuda     = $total_venta - $total_abonado;
$cliente         = $datos_cuenta_cobrar['nombres']." ".$datos_cuenta_cobrar['apellidos'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title></title>
</head>
<body>
<center>

<table>
<td><strong><a href="../admin/cuentas_cobrar_abonos.php?cod_factura=<?php echo $cod_factura;?>&cod_clientes=<?php echo $cod_clientes;?>&cliente=<?php echo $cliente;?>"><font color='yellow' size="5px">REGRESAR</font></a></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<!--
<td><a href="../admin/productos_fiados.php?cod_factura=<?php echo $cod_factura;?>&cod_clientes=<?php echo $cod_clientes;?>&cliente=<?php echo $cliente;?>"><center><strong><font color='yellow' size="5px">VER PRODUCTOS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></strong></center></a></td>
<td><a href="../admin/cuentas_cobrar_abonos.php?cod_factura=<?php echo $cod_factura;?>&cod_clientes=<?php echo $cod_clientes;?>&cliente=<?php echo $cliente;?>"><center><strong><font color='yellow' size="5px">VER ABONOS</font></strong></center></a></td>
-->
</table>
<br><br>
<td><strong><font color='yellow' size="6px">CLIENTE: <?php echo $cliente; ?></font></strong></td><br>
<td><strong><font color='yellow' size="6px">FACTURA: <?php echo $cod_factura; ?></font></strong></td><br><br>

<table align="center">
<tr>
<td nowrap align="left"><font size="6">TOTAL DEUDA:</font></td>
<td><font size="6"><?php echo number_format($total_venta, 0, ",", "."); ?></font></td>
</tr>
<tr valign="baseline">
<td nowrap align="left"><font size="6">DEUDA ACTUAL:</font></td>
<td><font size="6"><?php echo number_format($total_deuda, 0, ",", "."); ?></font></td>
</tr>
</table>

<br>

<form method="post" name="formulario_de_actualizacion" action="modificar_cuentas_cobrar_reg.php">
<table width="90%">
<tr>
<td align="center"><strong>VALOR ABONO</strong></td>
<td align="center"><strong>COMENTARIO</strong></td>
<td align="center"><strong>FECHA PAGO</strong></td>
<tr>
<tr>
<td align="center"><input style="font-size:24px" type="text" name="abonado" value="" size="10"  required autofocus></td>
<td align="center"><input style="font-size:24px" type="text" name="mensaje" value="" size="50"></td>
<td align="center"><input style="font-size:24px" type="text" name="fecha_pago" value="<?php echo date("d/m/Y");?>" size="10" required autofocus></td>
<input style="font-size:24px" type="hidden" name="cod_cuentas_pagar" value="<?php echo $cod_cuentas_pagar?>" size="10">
<input style="font-size:24px" type="hidden" name="cod_factura" value="<?php echo $cod_factura?>" size="10">
<input style="font-size:24px" type="hidden" name="cod_proveedores" value="<?php echo $cod_proveedores?>" size="10">
<input style="font-size:24px" type="hidden" name="nombre_proveedores" value="<?php echo $nombre_proveedores?>" size="10">
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_factura" value="<?php echo $cod_factura; ?>">
<input type="hidden" name="cod_clientes" value="<?php echo $cod_clientes; ?>">
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Agregar"></td>
<input type="hidden" name="insertar_datos" value="formulario">
</tr>
</form>
</center>
</body>
</html>