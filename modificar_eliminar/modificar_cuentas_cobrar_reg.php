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
//-------------------------------------- REGISTRAR DATOS --------------------------------------//
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion")) {

$cod_factura     = intval($_POST['cod_factura']);
$cod_clientes    = intval($_POST['cod_clientes']);
$abonado         = addslashes($_POST['abonado']);
$mensaje         = addslashes($_POST['mensaje']);
$fecha_pago      = addslashes($_POST['fecha_pago']);
//-------------------------------------- REGISTRAR DATOS --------------------------------------//
$calcular_datos_cuenta_cobrar = "SELECT cuentas_cobrar.cod_cuentas_cobrar, cuentas_cobrar.cod_factura, cuentas_cobrar.cod_clientes, clientes.nombres, 
clientes.apellidos, Sum(ventas.vlr_total_venta-(ventas.vlr_total_venta*(ventas.descuento_ptj/100))) AS total_venta, 
Sum(cuentas_cobrar_abonos.abonado) AS total_abonado, 
Sum(ventas.vlr_total_venta - cuentas_cobrar_abonos.abonado) AS total_deuda, cuentas_cobrar_abonos.fecha_invert, 
cuentas_cobrar.mensaje, cuentas_cobrar.fecha_pago, cuentas_cobrar.vendedor
FROM ventas RIGHT JOIN ((cuentas_cobrar_abonos RIGHT JOIN cuentas_cobrar ON cuentas_cobrar_abonos.cod_factura = cuentas_cobrar.cod_factura) 
LEFT JOIN clientes ON cuentas_cobrar.cod_clientes = clientes.cod_clientes) ON ventas.cod_factura = cuentas_cobrar.cod_factura
GROUP BY cuentas_cobrar.cod_factura, cuentas_cobrar.cod_cuentas_cobrar, cuentas_cobrar.cod_clientes, clientes.nombres, clientes.apellidos
HAVING (((cuentas_cobrar.cod_factura)='$cod_factura'))";
$consulta_datos_cuenta_cobrar = mysql_query($calcular_datos_cuenta_cobrar, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta_datos_cuenta_cobrar);
$datos_cuenta_cobrar = mysql_fetch_assoc($consulta_datos_cuenta_cobrar);

$total_venta = $datos_cuenta_cobrar['total_venta'];
$total_abonado = $datos_cuenta_cobrar['total_abonado'];
$total_deuda = $datos_cuenta_cobrar['total_deuda'];
//-------------------------------------- REGISTRAR DATOS --------------------------------------//
$fecha_vector = explode('/', $fecha_pago);
$dia = $fecha_vector[0];
$mes = $fecha_vector[1];
$anyos = $fecha_vector[2];
$fecha_anyo = $fecha_pago;
$fecha_mes = $mes.'/'.$anyos;
$anyo = $anyos;
$fecha_invert = strtotime($anyos.'/'.$mes.'/'.$dia);
$hora = date("H:i:s");
//-------------------------------------- REGISTRAR DATOS --------------------------------------//
//$actualizar_sql = sprintf("UPDATE cuentas_cobrar SET monto_deuda = '$total_venta', mensaje = '$mensaje' WHERE cod_factura = '$cod_factura'");
//$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
//-------------------------------------- REGISTRAR DATOS --------------------------------------//
$agregar_reg_cuentas_cobrar_abonos = "INSERT INTO cuentas_cobrar_abonos (cod_clientes, cod_factura, abonado, monto_deuda, cuenta, fecha_pago, fecha_anyo, fecha_mes, anyo, fecha_invert, hora, mensaje) 
VALUES ('$cod_clientes', '$cod_factura', '$abonado', '$total_venta', '$cuenta_actual', '$fecha_pago', '$fecha_anyo', '$fecha_mes', '$anyo', '$fecha_invert', '$hora', '$mensaje')";
$resultado_cuentas_cobrar_abonos = mysql_query($agregar_reg_cuentas_cobrar_abonos, $conectar) or die(mysql_error());
//-------------------------------------- REGISTRAR DATOS --------------------------------------//
$deuda = $total_venta - ($total_abonado + $abonado);

if ($deuda <= '0') {
$borrar_alerta  = sprintf("DELETE FROM notificacion_alerta WHERE cod_clientes = '$cod_clientes'");
$Resultado1 = mysql_query($borrar_alerta , $conectar) or die(mysql_error());
} ?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/cuentas_cobrar_abonos.php?cod_factura=<?php echo $cod_factura ?>&cod_clientes=<?php echo $cod_clientes ?>&cliente=<?php echo $cliente ?>">
<?php } ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title></title>
</head>
<body>
<center>