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

$pagina = $_SERVER['PHP_SELF'];

$cod_clientes = intval($_GET['cod_clientes']);

$sql_consulta_cliente = "SELECT cedula, nombres, apellidos FROM clientes WHERE cod_clientes = '$cod_clientes'";
$consulta_cliente = mysql_query($sql_consulta_cliente, $conectar) or die(mysql_error());
$total_cliente = mysql_fetch_assoc($consulta_cliente);

$cedula     = $total_cliente['cedula'];
$nombres    = $total_cliente['nombres'];
$apellidos  = $total_cliente['apellidos'];

$calcular_datos_plan_separe = "SELECT clientes.nombres, clientes.apellidos, plan_separe.cod_clientes, clientes.ciudad, clientes.telefono, clientes.cedula, clientes.direccion,
plan_separe.vendedor, plan_separe.fecha_dia, plan_separe.cod_plan_separe, plan_separe.cod_factura, plan_separe.fecha_ini_plan_separe, plan_separe.fecha_fin_plan_separe, 
plan_separe.total_plan_separe, plan_separe.total_saldo_plan_separe, plan_separe.total_abono_plan_separe
FROM clientes RIGHT JOIN plan_separe ON clientes.cod_clientes = plan_separe.cod_clientes
WHERE (plan_separe.cod_clientes='$cod_clientes') ORDER BY plan_separe.fecha_dia DESC";
$consulta_datos_plan_separe = mysql_query($calcular_datos_plan_separe, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta_datos_plan_separe);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<center>
<td><strong><a href="../admin/lista_plan_separe.php"><font color='white' size="5px">REGRESAR</font></a></strong></td><br><br>
<td><strong><font size="5" color='yellow'>PLAN SEPARE<br><br>
<td><strong><font size="5" color='yellow'>FACTURAS PLAN SEPARE - <?php echo $nombres.' '.$apellidos;?><br><br>
</center>
<center>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../admin/plan_separe_detalle_cliente_imprimir.php?cod_clientes=<?php echo $cod_clientes?>" target="_blank"><img src=../imagenes/imprimir_1.png alt="imprimir"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<table width="90%">
<tr>
<td align="center"><strong>ELIM</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>CLIENTE</strong></td>
<td align="center"><strong>TOTAL PLAN SEPARE</strong></td>
<td align="center"><strong>TOTAL ABONADO</strong></td>
<td align="center"><strong>TOTAL SALDO</strong></td>
<td align="center"><strong>ABONAR</strong></td>
<td align="center"><strong>PRODUCTOS</strong></td>
<td align="center"><strong>FECHA VENCIMIENTO</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
</tr>
<?php 
$monto_deuda_smtr = 0;
$abonado_smtr = 0;
$subtotal_smtr = 0;

while ($datos_plan_separe = mysql_fetch_assoc($consulta_datos_plan_separe)) {
$cod_plan_separe              = $datos_plan_separe['cod_plan_separe'];
$cod_factura                  = $datos_plan_separe['cod_factura'];
$cliente                      = $datos_plan_separe['nombres']." ".$datos_plan_separe['apellidos'];
$total_plan_separe            = $datos_plan_separe['total_plan_separe'];
$total_abono_plan_separe      = $datos_plan_separe['total_abono_plan_separe'];
$total_saldo_plan_separe      = $datos_plan_separe['total_saldo_plan_separe'];
$fecha_fin_plan_separe        = $datos_plan_separe['fecha_fin_plan_separe'];
$vendedor                     = $datos_plan_separe['vendedor'];
$monto_deuda_smtr             = $monto_deuda_smtr + $total_plan_separe;
$abonado_smtr                 = $abonado_smtr + $total_abono_plan_separe;
$subtotal_smtr                = $subtotal_smtr + $total_saldo_plan_separe;
?>
<tr>
<td align="center"><a href="../modificar_eliminar/eliminar_plan_separe_y_abonos.php?cod_plan_separe=<?php echo $cod_plan_separe;?>&cod_clientes=<?php echo $cod_clientes;?>&cliente=<?php echo $cliente;?>&pagina=<?php echo $pagina;?>"><img src=../imagenes/eliminar.png alt="Abonar"></a></td>
<td align="center"><font size='3'><?php echo $cod_plan_separe;?></font></td>
<td><font size='3'><?php echo $cliente;?></font></td>
<td align="right"><font size='3'><?php echo number_format($total_plan_separe, 0, ",", ".")?></font></a></td>
<td align="right"><font size='3'><a href="../admin/plan_separe_abonos.php?cod_plan_separe=<?php echo $cod_plan_separe;?>&cod_clientes=<?php echo $cod_clientes;?>&cliente=<?php echo $cliente;?>"><?php echo number_format($total_abono_plan_separe, 0, ",", ".");?></a></font></td>
<td align="right"><font color='yellow' size='5'><?php echo number_format($total_saldo_plan_separe, 0, ",", "."); ?></font></td>
<td align="center"><a href="../admin/plan_separe_abonos.php?cod_plan_separe=<?php echo $cod_plan_separe;?>&cod_clientes=<?php echo $cod_clientes;?>&cliente=<?php echo $cliente;?>"><img src=../imagenes/base_caja.png alt="Abonar"></a></td>
<td align="center"><a href="../admin/productos_plan_separe.php?cod_plan_separe=<?php echo $cod_plan_separe;?>&cod_clientes=<?php echo $cod_clientes;?>&cliente=<?php echo $cliente;?>"><img src=../imagenes/agregar.png alt="productos"></a></td>
<td align="center"><font size='3'><?php echo $fecha_fin_plan_separe;?></font></td>
<td align="center"><font size='3'><?php echo $vendedor; ?></font></td>
</tr>
<?php } ?>
</table>

<br>

<table width="60%">
<tr>
<td align="center"><strong><font size='5'>TOTAL CREDITO</font></strong></td>
<td align="center"><strong><font size='5'>TOTAL ABONADO</font></strong></td>
<td align="center"><strong><font size='5'>TOTAL DEUDA</font></strong></td>
</tr>
<tr>
<td align="center"><font size='5'><?php echo number_format($monto_deuda_smtr, 0, ",", ".")?></font></a></td>
<td align="center"><font size='5'><?php echo number_format($abonado_smtr, 0, ",", ".");?></font></td>
<td align="center"><font color='yellow' size='5'><?php echo number_format($subtotal_smtr, 0, ",", "."); ?></font></td>
</tr>
</table>
