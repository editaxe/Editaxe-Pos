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
/*
$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
*/
//include ("../registro_movimientos/registro_cierre_caja.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>

<script language="javascript" src="isiAJAX.js"></script>
<script language="javascript">
var last;
function Focus(elemento, valor) {
$(elemento).className = 'activo';
last = valor;
}
function Blur(elemento, valor, campo, id) {
$(elemento).className = 'inactivo';
if (last != valor)
myajax.Link('guardar_devoluciones_facturas.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>

</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:1;">  
<?php
$cod_factura     = intval($_GET['cod_factura']);
$cod_clientes    = intval($_GET['cod_clientes']);
$cliente         = addslashes($_GET['cliente']);
$pagina          = "productos_fiados";

$sql = "SELECT * FROM ventas, clientes WHERE ventas.cod_clientes = '$cod_clientes' AND ventas.cod_factura = '$cod_factura'
AND ventas.cod_clientes = clientes.cod_clientes ORDER BY fecha DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta);

$sql_consulta = "SELECT SUM(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) AS vlr_total_venta FROM ventas WHERE cod_factura = '$cod_factura' AND cod_clientes = '$cod_clientes'";
$consulta_total_venta = mysql_query($sql_consulta, $conectar) or die(mysql_error());
$total_venta = mysql_fetch_assoc($consulta_total_venta);


$sql_sum_abonos = "SELECT Sum(abonado) As total_abonado FROM cuentas_cobrar_abonos WHERE cod_factura = '$cod_factura'";
$consulta_sum_abonos  = mysql_query($sql_sum_abonos, $conectar) or die(mysql_error());
$sum_abonos = mysql_fetch_assoc($consulta_sum_abonos);

$sql_monto_deuda = "SELECT monto_deuda AS total_venta FROM cuentas_cobrar WHERE cod_factura = '$cod_factura'";
$consulta_monto_deuda  = mysql_query($sql_monto_deuda, $conectar) or die(mysql_error());
$sum_monto_deuda = mysql_fetch_assoc($consulta_monto_deuda);

$total_venta      = $sum_monto_deuda['total_venta'];
$total_abonado    = $sum_abonos['total_abonado'];
$total_deuda      = $total_venta - $total_abonado;

?>
<br>
<center>
<td><strong><a href="cuentas_cobrar_detalle_factura.php?cod_clientes=<?php echo $cod_clientes ?>"><font color='white' size="5px">REGRESAR</font></a></strong></td><br><br>
<td><strong><font color='yellow' size='6'>PRODUCTOS EN CREDITO CLIENTE: <?php echo $cliente; ?> </font></strong><br>
	<!--<a href="../modificar_eliminar/eliminar_productos_fiados_todos.php?cod_clientes=<?php echo $cod_clientes;?>&pagina=<?php echo $pagina;?>"><img src=../imagenes/ventas.png alt="enviar todo a ventas"></a></td><br><br>-->
<td><strong><font color='yellow' size="6px">FACTURA: <?php echo $cod_factura; ?></font></strong></td><br><br>

<table width="100%">
<tr>
<!--
<td  nowrap><a href="../modificar_eliminar/eliminar_productos_fiados_devolucion.php?cod_productos=<?php echo $cod_productos?>&cod_productos_fiados=<?php echo $cod_productos_fiados?>&cod_clientes=<?php echo $cod_clientes?>&cliente=<?php echo $cliente?>"><center><img src=../imagenes/eliminar.png alt="Eliminar"></center></a></td>
<td ><?php echo $datos['nombres'].' '.$datos['apellidos']; ?></td>
-->
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>MET</strong></td>
<td align="center"><strong>P.VENTA</strong></td>
<td align="center"><strong>COMENTARIO</strong></td>
<td align="center"><strong>%DESC</strong></td>
<td align="center"><strong>TOTAL</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
<td align="center"><strong>IP</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
<!--<td align="center"><strong>VENDER</strong></td>
<td><strong>OK</strong></td>-->
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_ventas            = $datos['cod_ventas'];
$cod_productos         = $datos['cod_productos'];
$cod_clientes          = $datos['cod_clientes'];
$unidades_vendidas     = $datos['unidades_vendidas'];
$precio_venta          = $datos['precio_venta'];
$comentario            = $datos['comentario'];
$descuento_ptj         = intval($datos['descuento_ptj']);
$vlr_total_venta       = $datos['vlr_total_venta'];
$total_venta_desc      = $vlr_total_venta-($vlr_total_venta*($descuento_ptj/100));
?>
<tr>
<!--
<td  nowrap><a href="../modificar_eliminar/eliminar_productos_fiados_devolucion.php?cod_productos=<?php echo $cod_productos?>&cod_productos_fiados=<?php echo $cod_productos_fiados?>&cod_clientes=<?php echo $cod_clientes?>&cliente=<?php echo $cliente?>"><center><img src=../imagenes/eliminar.png alt="Eliminar"></center></a></td>
<td ><?php echo $datos['nombres'].' '.$datos['apellidos']; ?></td>
-->
<td align="center"><?php echo $datos['cod_factura']; ?></td>
<td ><?php echo $datos['cod_productos']; ?></td>
<td ><?php echo $datos['nombre_productos']; ?></td>
<td align="center"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades_vendidas', <?php echo $cod_ventas;?>)" class="cajund" id="<?php echo $cod_ventas;?>" value="<?php echo $unidades_vendidas;?>" size="5"></td>
<td ><?php echo $datos['detalles']; ?></td>
<td align="center"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_venta', <?php echo $cod_ventas;?>)" class="cajund" id="<?php echo $cod_ventas;?>" value="<?php echo $precio_venta;?>" size="5"></td>
<td ><?php echo $comentario ?></td>
<td align="center"><font size="4"><?php echo $descuento_ptj; ?></font></td>
<td align="right"><font size="4"><?php echo number_format($total_venta_desc, 0, ",", "."); ?></font></td>
<td align="center"><?php echo $datos['vendedor']; ?></td>
<td align="right"><?php echo $datos['ip']; ?></td>
<td align="right"><?php echo $datos['fecha_anyo']; ?></td>
<td align="right"><?php echo $datos['fecha_hora']; ?></td>
<!--<td><a href="../modificar_eliminar/eliminar_productos_fiados.php?cod_productos_fiados=<?php echo $cod_productos_fiados; ?>&cod_clientes=<?php echo $cod_clientes; ?>&pagina=<?php echo $pagina;?>"><center><img src=../imagenes/ventas.png alt="enviar a ventas"></center></a></td>
<td><a href="<?php $_SERVER['PHP_SELF']?>"><center><img src=../imagenes/correcto.png alt="Listo"></center></a></td>-->
</tr>
<?php }
?>
</table>
<br>
<table width="50%">
<tr>
<td align="left"><font size="6">TOTAL CREDITO: </font></td><td align="right"><font size="6"><?php echo number_format($total_venta, 0, ",", "."); ?></font></td>
</tr>
<tr>
<td align="left"><font size="6">TOTAL ABONADO: </font></td><td align="right"><font size="6"><?php echo number_format($total_abonado, 0, ",", "."); ?></font></td>
</tr>
<tr>
<td align="left"><font size="7" color='yellow'>TOTAL DEUDA: </font></td><td align="right"><font size="7" color='yellow'><?php echo number_format($total_deuda, 0, ",", "."); ?></font></td>
</tr>
</table>
</form>