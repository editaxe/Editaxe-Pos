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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$pagina = $_SERVER['PHP_SELF'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
<body>
<script language="javascript" src="isiAJAX.js"></script>
<script language="javascript">
var last;
function Focus(elemento, valor) {
$(elemento).className = 'cajhabiltada';
last = valor;
}
function Blur(elemento, valor, campo, id) {
$(elemento).className = 'cajdeshabiltada';
if (last != valor)
myajax.Link('guardar_caja_registro_fisico_y_sistema.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>
</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:1;">
<center>


<td><strong><font color='yellow' size='+1'>INFORMACION CAJA FISICA: </font></strong></td><br><br>

<table width="80%">
<tr>
<td align="center"><strong>TOTAL FISICO</strong></td>
<td align="center"><strong>TOTAL SISTEMA</strong></td>
<td align="center"><strong>DIFERENCIA</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
<td align="center"><strong>IMP</strong></td>
</tr>
<?php 
$mostrar_totales_fisico = "SELECT SUM(total_ventas_fisico) AS total_ventas_fisico, fecha, fecha_anyo, fecha_hora, usuario 
FROM caja_registro_fisico GROUP BY usuario, fecha_anyo ORDER BY fecha, fecha_hora DESC";
$consulta_totales_fisico = mysql_query($mostrar_totales_fisico, $conectar) or die(mysql_error());
while ($datos_totales_fisico = mysql_fetch_assoc($consulta_totales_fisico)) {

$total_ventas_fisico                   = $datos_totales_fisico['total_ventas_fisico'];
$fecha                                 = $datos_totales_fisico['fecha'];
$fecha_mes                             = $datos_totales_fisico['fecha_mes'];
$fecha_anyo                            = $datos_totales_fisico['fecha_anyo'];
$fecha_hora                            = $datos_totales_fisico['fecha_hora'];
$anyo                                  = $datos_totales_fisico['anyo'];
$usuario                               = $datos_totales_fisico['usuario'];
 	 	
$mostrar_totales_sistema = "SELECT SUM(vlr_total_venta) AS total_ventas_sistema FROM ventas WHERE (vendedor = '$usuario') AND (fecha_anyo = '$fecha_anyo') GROUP BY fecha_anyo";
$consulta_totales_sistema = mysql_query($mostrar_totales_sistema, $conectar) or die(mysql_error());
$datos_totales_sistema = mysql_fetch_assoc($consulta_totales_sistema);

$total_ventas_sistema                  = $datos_totales_sistema['total_ventas_sistema'];
?>
<tr>
<td align="center"><a href="../admin/ver_caja_registro_fisico_detalle.php?usuario=<?php echo $usuario?>&fecha_anyo=<?php echo $fecha_anyo?>"><?php echo number_format($total_ventas_fisico, 0, ",", "."); ?></a></td> 
<td align="center"><a href="../admin/ver_caja_registro_fisico_detalle.php?usuario=<?php echo $usuario?>&fecha_anyo=<?php echo $fecha_anyo?>"><?php echo number_format($total_ventas_sistema, 0, ",", "."); ?></a></td> 
<td align="center"><a href="../admin/ver_caja_registro_fisico_detalle.php?usuario=<?php echo $usuario?>&fecha_anyo=<?php echo $fecha_anyo?>"><?php echo number_format($total_ventas_fisico - $total_ventas_sistema, 0, ",", "."); ?></a></td> 
<td align="center"><a href="../admin/ver_caja_registro_fisico_detalle.php?usuario=<?php echo $usuario?>&fecha_anyo=<?php echo $fecha_anyo?>"><?php echo $fecha_anyo; ?></a></td> 
<td align="center"><a href="../admin/ver_caja_registro_fisico_detalle.php?usuario=<?php echo $usuario?>&fecha_anyo=<?php echo $fecha_anyo?>"><?php echo $usuario; ?></a></td> 
<td align="center"><a href="../admin/imprimir_caja_reg_fisico_ticket_pdf_.php?usuario=<?php echo $usuario?>&fecha_anyo=<?php echo $fecha_anyo?>" target="_blank"><img src=../imagenes/imprimir.png alt="correcto"></a></td> 
</tr>
<?php } ?>
</table>
</center>
</body>