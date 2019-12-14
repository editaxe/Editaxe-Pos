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

$calcular_datos_plan_separe = "SELECT clientes.nombres, clientes.apellidos, plan_separe.cod_clientes, clientes.ciudad, clientes.telefono, clientes.cedula, clientes.direccion,
plan_separe.cod_plan_separe, plan_separe.cod_plan_separe, plan_separe.fecha_ini_plan_separe, plan_separe.fecha_fin_plan_separe, 
Sum(plan_separe.total_plan_separe) AS total_plan_separe, Sum(plan_separe.total_saldo_plan_separe) AS total_saldo_plan_separe, 
Sum(plan_separe.total_abono_plan_separe) AS total_abono_plan_separe
FROM clientes RIGHT JOIN plan_separe ON clientes.cod_clientes = plan_separe.cod_clientes
GROUP BY plan_separe.cod_clientes ORDER BY clientes.nombres";
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
<br><br>
<center>
<td><strong><font color='yellow'>CUENTAS PLAN SEPARE<br><br>
</center>
<center>
<br>
<table width="90%">
<tr>
<td align="center"><strong><font size='3'>NIT</font></strong></td>
<td align="center"><strong><font size='3'>CLIENTE</font></strong></td>
<td align="center"><strong><font size='3'>TOTAL PLAN SEPARE</font></strong></td>
<td align="center"><strong><font size='3'>TOTAL ABONADO</font></strong></td>
<td align="center"><strong><font size='3'>TOTAL SALDO</font></strong></td>
<td align="center"><strong><font size='3'>DIRECCION</font></strong></td>
<td align="center"><strong><font size='3'>TELEFONO</font></strong></td>
<td align="center"><strong><font size='3'>OK</font></strong></td>
</tr>
<?php while ($datos_plan_separe = mysql_fetch_assoc($consulta_datos_plan_separe)) {
$cod_plan_separe                       = $datos_plan_separe['cod_plan_separe'];
$total_plan_separe                     = $datos_plan_separe['total_plan_separe'];
$total_saldo_plan_separe               = $datos_plan_separe['total_saldo_plan_separe'];
$total_abono_plan_separe               = $datos_plan_separe['total_abono_plan_separe'];
$cod_clientes                          = $datos_plan_separe['cod_clientes'];
$cliente                               = $datos_plan_separe['nombres']." ".$datos_plan_separe['apellidos'];
$direccion                             = $datos_plan_separe['direccion'];
$telefono                              = $datos_plan_separe['telefono'];
$ciudad                                = $datos_plan_separe['ciudad'];
$cedula                                = $datos_plan_separe['cedula'];
?>
<tr>
<td><font size='4'><a href="../admin/plan_separe_detalle_factura.php?cod_clientes=<?php echo $cod_clientes; ?>"><?php echo $cedula;?></a></font></td>
<td><font size='4'><a href="../admin/plan_separe_detalle_factura.php?cod_clientes=<?php echo $cod_clientes; ?>"><?php echo $cliente;?></a></font></td>
<td align="right"><font size='4'><?php echo number_format($total_plan_separe, 0, ",", "."); ?></font></td>
<td align="right"><font size='4'><?php echo number_format($total_abono_plan_separe, 0, ",", "."); ?></font></td>

<?php if ($total_saldo_plan_separe <= 0) { ?> <td align="right"><font size='4' color="yellow"><?php echo number_format($total_saldo_plan_separe, 0, ",", "."); ?></font></td>
<?php } else { ?> <td align="right"><font size='4'><?php echo number_format($total_saldo_plan_separe, 0, ",", "."); ?></font></td> <?php } ?>

<td align="right"><font size='4'><?php echo $direccion; ?></font></td>
<td align="right"><font size='4'><?php echo $telefono; ?></font></td>
<td align="center"><font size='4'><a href="../admin/plan_separe_cliente_actualizar.php?cod_clientes=<?php echo $cod_clientes; ?>&pagina=<?php echo $pagina; ?>"><img src="../imagenes/correcto.png"></a></font></td>
</tr>
<?php } ?>
</table>
