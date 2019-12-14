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
myajax.Link('guardar_plan_separe_producto_ajax.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>

</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:1;">  
<?php
$cod_plan_separe     = intval($_GET['cod_plan_separe']);
$cod_clientes    = intval($_GET['cod_clientes']);
$cliente         = addslashes($_GET['cliente']);
$pagina          = "productos_fiados";

$sql = "SELECT plan_separe_producto.cod_plan_separe_producto, plan_separe_producto.cod_productos, plan_separe_producto.cod_plan_separe, 
plan_separe_producto.cod_clientes, plan_separe_producto.nombre_productos, plan_separe_producto.unidades_vendidas, 
plan_separe_producto.precio_venta, plan_separe_producto.vlr_total_venta, plan_separe_producto.comentario, plan_separe_producto.descuento_ptj, 
plan_separe_producto.vendedor, plan_separe_producto.ip, plan_separe_producto.fecha_anyo, plan_separe_producto.fecha_hora,
clientes.nombres, clientes.apellidos, clientes.cedula
FROM clientes RIGHT JOIN plan_separe_producto ON clientes.cod_clientes = plan_separe_producto.cod_clientes
WHERE (plan_separe_producto.cod_plan_separe='$cod_plan_separe') ORDER BY plan_separe_producto.fecha DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta);

$sql_consulta = "SELECT SUM(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) AS vlr_total_venta FROM plan_separe_producto WHERE cod_plan_separe = '$cod_plan_separe' AND cod_clientes = '$cod_clientes'";
$consulta_total_venta = mysql_query($sql_consulta, $conectar) or die(mysql_error());
$total_venta = mysql_fetch_assoc($consulta_total_venta);


$sql_sum_abonos = "SELECT Sum(abono_plan_separe) As abono_plan_separe FROM plan_separe_abono WHERE cod_plan_separe = '$cod_plan_separe'";
$consulta_sum_abonos  = mysql_query($sql_sum_abonos, $conectar) or die(mysql_error());
$sum_abonos = mysql_fetch_assoc($consulta_sum_abonos);

$sql_monto_deuda = "SELECT total_plan_separe FROM plan_separe WHERE cod_plan_separe = '$cod_plan_separe'";
$consulta_monto_deuda  = mysql_query($sql_monto_deuda, $conectar) or die(mysql_error());
$sum_monto_deuda = mysql_fetch_assoc($consulta_monto_deuda);

$total_plan_separe                  = $sum_monto_deuda['total_plan_separe'];
$abono_plan_separe                  = $sum_abonos['abono_plan_separe'];
$total_saldo_plan_separe            = $total_plan_separe - $abono_plan_separe;

?>
<br>
<center>
<td><strong><a href="lista_plan_separe.php"><font color='white' size="5px">REGRESAR</font></a></strong></td><br><br>
<td><strong><font color='yellow' size='6'>PRODUCTOS EN PLAN SEPARE CLIENTE: <?php echo $cliente; ?> </font></strong><br>
	<!--<a href="../modificar_eliminar/eliminar_productos_fiados_todos.php?cod_clientes=<?php echo $cod_clientes;?>&pagina=<?php echo $pagina;?>"><img src=../imagenes/plan_separe_producto.png alt="enviar todo a plan_separe_producto"></a></td><br><br>-->
<td><strong><font color='yellow' size="6px">FACTURA: <?php echo $cod_plan_separe; ?></font></strong></td><br><br>

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
<td align="center"><strong>P.VENTA</strong></td>
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
$cod_plan_separe_producto            = $datos['cod_plan_separe_producto'];
$cod_productos         = $datos['cod_productos'];
$cod_clientes          = $datos['cod_clientes'];
$unidades_vendidas     = $datos['unidades_vendidas'];
$precio_venta          = $datos['precio_venta'];
$comentario            = $datos['comentario'];
$descuento_ptj         = ($datos['descuento_ptj']);
$vlr_total_venta       = $datos['vlr_total_venta'];
$vendedor              = $datos['vendedor'];
$ip                    = $datos['ip'];
$fecha_anyo            = $datos['fecha_anyo'];
$fecha_hora            = $datos['fecha_hora'];
$total_venta_desc      = $vlr_total_venta-($vlr_total_venta*($descuento_ptj/100));
?>
<tr>
<!--
<td  nowrap><a href="../modificar_eliminar/eliminar_productos_fiados_devolucion.php?cod_productos=<?php echo $cod_productos?>&cod_productos_fiados=<?php echo $cod_productos_fiados?>&cod_clientes=<?php echo $cod_clientes?>&cliente=<?php echo $cliente?>"><center><img src=../imagenes/eliminar.png alt="Eliminar"></center></a></td>
<td ><?php echo $datos['nombres'].' '.$datos['apellidos']; ?></td>
-->
<td align="center"><?php echo $datos['cod_plan_separe']; ?></td>
<td ><?php echo $datos['cod_productos']; ?></td>
<td ><?php echo $datos['nombre_productos']; ?></td>
<td align="center"><?php echo $datos['unidades_vendidas']; ?></td>
<td align="right"><font size="4"><?php echo number_format($precio_venta, 0, ",", "."); ?></font></td>
<td align="center"><font size="4"><?php echo $descuento_ptj; ?></font></td>
<td align="right"><font size="4"><?php echo number_format($total_venta_desc, 0, ",", "."); ?></font></td>
<td align="center"><?php echo $vendedor; ?></td>
<td align="right"><?php echo $ip; ?></td>
<td align="right"><?php echo $fecha_anyo; ?></td>
<td align="right"><?php echo $fecha_hora; ?></td>
<!--<td><a href="../modificar_eliminar/eliminar_productos_fiados.php?cod_productos_fiados=<?php echo $cod_productos_fiados; ?>&cod_clientes=<?php echo $cod_clientes; ?>&pagina=<?php echo $pagina;?>"><center><img src=../imagenes/plan_separe_producto.png alt="enviar a plan_separe_producto"></center></a></td>
<td><a href="<?php $_SERVER['PHP_SELF']?>"><center><img src=../imagenes/correcto.png alt="Listo"></center></a></td>-->
</tr>
<?php }
?>
</table>
<br>
<table width="50%">
<tr>
<td align="left"><font size="6">TOTAL PLAN SEPARE: </font></td><td align="right"><font size="6"><?php echo number_format($total_plan_separe, 0, ",", "."); ?></font></td>
</tr>
<tr>
<td align="left"><font size="6">TOTAL ABONADO: </font></td><td align="right"><font size="6"><?php echo number_format($abono_plan_separe, 0, ",", "."); ?></font></td>
</tr>
<tr>
<td align="left"><font size="7" color='yellow'>TOTAL SALDO: </font></td><td align="right"><font size="7" color='yellow'><?php echo number_format($total_saldo_plan_separe, 0, ",", "."); ?></font></td>
</tr>
</table>
</form>