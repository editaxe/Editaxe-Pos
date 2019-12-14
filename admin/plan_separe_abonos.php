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
<script language="javascript" src="isiAJAX.js"></script>
<script language="javascript">
var last;
function Focus(elemento, valor) {
$(elemento).className = 'inputon';
last = valor;
}
function Blur(elemento, valor, campo, id) {
$(elemento).className = 'inputoff';
if (last != valor)
myajax.Link('guardar_plan_separe_abonos_editable.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>
<body onLoad="myajax = new isiAJAX();">

</head>
<?php
$cod_clientes    = intval($_GET['cod_clientes']);
$cod_plan_separe     = intval($_GET['cod_plan_separe']);
$cliente        = addslashes($_GET['cliente']);

$sql_sum_abonos = "SELECT Sum(abono_plan_separe) As abono_plan_separe FROM plan_separe_abono WHERE cod_plan_separe = '$cod_plan_separe'";
$consulta_sum_abonos  = mysql_query($sql_sum_abonos, $conectar) or die(mysql_error());
$sum_abonos = mysql_fetch_assoc($consulta_sum_abonos);

$sql_monto_deuda = "SELECT total_plan_separe FROM plan_separe WHERE cod_plan_separe = '$cod_plan_separe'";
$consulta_monto_deuda  = mysql_query($sql_monto_deuda, $conectar) or die(mysql_error());
$sum_monto_deuda = mysql_fetch_assoc($consulta_monto_deuda);

$total_plan_separe      = $sum_monto_deuda['total_plan_separe'];
$total_abono_plan_separe    = $sum_abonos['abono_plan_separe'];
$total_saldo_plan_separe      = $total_plan_separe - $total_abono_plan_separe;

$sql_consulta_cliente = "SELECT cedula, nombres, apellidos FROM clientes WHERE cod_clientes = '$cod_clientes'";
$consulta_cliente = mysql_query($sql_consulta_cliente, $conectar) or die(mysql_error());
$total_cliente = mysql_fetch_assoc($consulta_cliente);

$cedula     = $total_cliente['cedula'];
$nombres    = $total_cliente['nombres'];
$apellidos  = $total_cliente['apellidos'];
?>
<center>
<table width="60%">
<tr>
<td align='center'><strong><a href="../admin/plan_separe_detalle_factura.php?cod_clientes=<?php echo $cod_clientes?>"><font color='white' size="5px">REGRESAR</font></a></strong></td>
</tr>
<tr>
<td align='center'><strong><font color='yellow' size="6px">ABONOS CLIENTE: <?php echo $nombres.' '.$apellidos;?> </font></strong></td>
</tr>
<tr>
<td align='center'><strong><font color='yellow' size="6px">FACTURA: <?php echo $cod_plan_separe; ?></font></strong></td>
</tr>
<tr>
<td align='center'><a href="../modificar_eliminar/modificar_plan_separe.php?cod_clientes=<?php echo $cod_clientes?>&cod_plan_separe=<?php echo $cod_plan_separe?>"><font color='yellow' size="6px">NUEVO ABONO</font></a></td>
</tr>
<tr>
<td align='center'><a href="../admin/plan_separe_detalle_factura_imprimir.php?cod_plan_separe=<?php echo $cod_plan_separe?>&cod_clientes=<?php echo $cod_clientes?>" target="_blank"><img src=../imagenes/imprimir_1.png alt="imprimir"></a></td>
</tr>
</table>

<br>

<table width="80%">
<tr>
<td align="center"><strong>ABONOS</strong></td>
<td align="center"><strong>PAGO A</strong></td>
<td align="center"><strong>MENSAJE</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
<td align="center"><strong>IMP</strong></td>
</tr>
<?php
$sql = "SELECT * FROM plan_separe_abono WHERE cod_plan_separe = '$cod_plan_separe' ORDER BY fecha_anyo DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta);
while ($datos = mysql_fetch_assoc($consulta)) {

$cod_plan_separe_abono = $datos['cod_plan_separe_abono'];
$abono_plan_separe = $datos['abono_plan_separe'];
$vendedor = $datos['vendedor'];
$mensaje = $datos['mensaje'];
$fecha_anyo = $datos['fecha_anyo'];
$hora = $datos['hora'];
?>
<tr>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'abono_plan_separe', <?php echo $cod_plan_separe_abono;?>)" class="cajgrand" id="<?php echo $cod_plan_separe_abono;?>" value="<?php echo $abono_plan_separe;?>" size="3"></td>
<td align="center"><font size="4px"><?php echo $vendedor; ?></font></td>
<td align="left"><font size="4px"><?php echo $mensaje; ?></font></td>
<td align="center"><font size="4px"><?php echo $fecha_anyo; ?></font></td>
<td align="center"><font size="4px"><?php echo $hora; ?></font></td>
<td align="center"><a href="../admin/plan_separe_abono_imprimir.php?cod_plan_separe_abono=<?php echo $cod_plan_separe_abono?>&cod_plan_separe=<?php echo $cod_plan_separe?>&cod_clientes=<?php echo $cod_clientes?>"  target="_blank"><img src=../imagenes/imprimir_imgpeq.png alt="imprimir_imgpeq"></a></td>
</tr>
<?php } ?>
</table>

<table width="50%">
<tr>
<td align="left"><font size="6">TOTAL PLAN SEPARE: </font></td><td align="right"><font size="6"><?php echo number_format($total_plan_separe, 0, ",", "."); ?></font></td>
</tr>
<tr>
<td align="left"><font size="6">TOTAL ABONADO: </font></td><td align="right"><font size="6"><?php echo number_format($total_abono_plan_separe, 0, ",", "."); ?></font></td>
</tr>
<tr>
<td align="left"><font size="7" color='yellow'>TOTAL DEUDA: </font></td><td align="right"><font size="7" color='yellow'><?php echo number_format($total_saldo_plan_separe, 0, ",", "."); ?></font></td>
</tr>
</table>
</form>