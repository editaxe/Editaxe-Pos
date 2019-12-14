<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php');
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar);
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");

if (isset($_POST["abonado"])) {

$cod_clientes = intval($_POST['cod_clientes']);
$abonado = addslashes($_POST['abonado']);
$fecha_pago = addslashes($_POST['fecha_pago']);
$mensaje = addslashes($_POST['mensaje']);
$hora = date("H:i:s");

$fecha_vector = explode('/', $fecha_pago);
$dia = $fecha_vector[0];
$mes = $fecha_vector[1];
$anyo = $fecha_vector[2];
$fecha_invert = strtotime($anyo.'/'.$mes.'/'.$dia);

$agregar_reg_cuentas_cobrar_abonos = "INSERT INTO cuentas_cobrar_abonos (cod_clientes, abonado, cuenta, fecha_pago, fecha, fecha_invert, hora, mensaje) 
VALUES ('$cod_clientes', '$abonado', '$cuenta_actual', '$fecha_pago', '$fecha_pago', '$fecha_invert', '$hora', '$mensaje')";
$resultado_cuentas_cobrar_abonos = mysql_query($agregar_reg_cuentas_cobrar_abonos, $conectar) or die(mysql_error());


$sum_abonos_valor = "SELECT Sum(abonado) AS abonado FROM cuentas_cobrar_abonos WHERE cod_clientes = '$cod_clientes'";
$consulta_sum_abonos = mysql_query($sum_abonos_valor, $conectar) or die(mysql_error());
$sum_abonos = mysql_fetch_assoc($consulta_sum_abonos);

$sql_prod_fiados = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As monto_deuda FROM productos_fiados 
WHERE cod_clientes = '$cod_clientes'";
$modificar_prod_fiados = mysql_query($sql_prod_fiados, $conectar) or die(mysql_error());
$datos_fiad = mysql_fetch_assoc($modificar_prod_fiados);


$monto_deuda = intval($datos_fiad['monto_deuda']);
$abonado_sum = intval($sum_abonos['abonado']);

$actualizar_sql = "UPDATE cuentas_cobrar SET monto_deuda = '$monto_deuda', abonado = '$abonado_sum', 
mensaje = '$mensaje' WHERE cod_clientes = '$cod_clientes'";
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());

$sql_cuentas_cobrar = "SELECT * FROM cuentas_cobrar WHERE cod_clientes = '$cod_clientes'";
$consult_cuentas_cobrar = mysql_query($sql_cuentas_cobrar, $conectar) or die(mysql_error());
$dato_cuentas_cobrar = mysql_fetch_assoc($consult_cuentas_cobrar);

$monto_deuda_cuenta_cobrar = $dato_cuentas_cobrar['monto_deuda'];
$abono_cuenta_cobrar = $dato_cuentas_cobrar['abonado'];

echo '<br><br>cod_clientes<br><br>';
echo $cod_clientes;
echo '<br><br>abonado<br><br>';
echo $abonado;
echo '<br><br>monto_deuda<br><br>';
echo intval($monto_deuda);
echo '<br><br>abonado_sum<br><br>';
echo $abonado_sum;
echo '<br><br>monto_deuda_cuenta_cobrar<br><br>';
echo intval($monto_deuda_cuenta_cobrar);
echo '<br><br>abono_cuenta_cobrar<br><br>';
echo $abono_cuenta_cobrar;
?>
<META HTTP-EQUIV="REFRESH" CONTENT="50; ../admin/cuentas_cobrar.php">
<?php
}
?>