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

$usuario                             = addslashes($_GET['usuario']);
$fecha_anyo                          = addslashes($_GET['fecha_anyo']);

$mostrar_totales_fisico = "SELECT SUM(total_ventas_fisico) AS total_ventas_fisico, fecha, fecha_anyo, fecha_hora, usuario 
FROM caja_registro_fisico WHERE (usuario = '$usuario') AND (fecha_anyo = '$fecha_anyo')";
$consulta_totales_fisico = mysql_query($mostrar_totales_fisico, $conectar) or die(mysql_error());
$datos_totales_fisico = mysql_fetch_assoc($consulta_totales_fisico);

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

$mostrar_datos_sql = "SELECT * FROM caja_registro_fisico ORDER BY fecha DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());

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
<table width="100%">
<tr>
<td align="center"><strong>TOTAL FISICO</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
<td align="center"><strong>COMENTARIO</strong></td>
</tr>
<?php 
$mostrar_totales_fisico = "SELECT total_ventas_fisico, fecha, fecha_anyo, fecha_hora, usuario, comentario, cod_caja_registro_fisico 
FROM caja_registro_fisico WHERE (usuario = '$usuario') AND (fecha_anyo = '$fecha_anyo')";
$consulta_totales_fisico = mysql_query($mostrar_totales_fisico, $conectar) or die(mysql_error());
while ($datos_totales_fisico = mysql_fetch_assoc($consulta_totales_fisico)) {

$cod_caja_registro_fisico              = $datos_totales_fisico['cod_caja_registro_fisico'];
$total_ventas_fisico                   = $datos_totales_fisico['total_ventas_fisico'];
$fecha                                 = $datos_totales_fisico['fecha'];
$fecha_mes                             = $datos_totales_fisico['fecha_mes'];
$fecha_anyo                            = $datos_totales_fisico['fecha_anyo'];
$fecha_hora                            = $datos_totales_fisico['fecha_hora'];
$anyo                                  = $datos_totales_fisico['anyo'];
$usuario                               = $datos_totales_fisico['usuario'];
$comentario                            = $datos_totales_fisico['comentario'];
?>
<tr>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'total_ventas_fisico', <?php echo $cod_caja_registro_fisico;?>)" class="cajbarras" id="<?php echo $cod_caja_registro_fisico;?>" value="<?php echo $total_ventas_fisico;?>" size="7"></td>
<td align="center"><font size='+1'><?php echo date("d/m/Y", $fecha); ?></font></td>
<td align="center"><font size='+1'><?php echo $fecha_hora; ?></font></td>
<td><font size='+1'><?php echo $usuario; ?></font></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'comentario', <?php echo $cod_caja_registro_fisico;?>)" class="cajsuper" id="<?php echo $cod_caja_registro_fisico;?>" value="<?php echo $comentario;?>" size="7"></td>
</tr>
<?php } ?>
</table>
</center>
</body>