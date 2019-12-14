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

$cod_cuentas_pagar = intval($_GET['cod_cuentas_pagar']);
$cod_factura_get = intval($_GET['cod_factura']);
$cod_proveedores = intval($_GET['cod_proveedores']);

$sql_pagar_cuenta = "SELECT cod_factura FROM cuentas_pagar WHERE cod_cuentas_pagar = '$cod_cuentas_pagar'";
$consulta_pagar_cuenta  = mysql_query($sql_pagar_cuenta, $conectar) or die(mysql_error());
$data_pag_cuenta = mysql_fetch_assoc($consulta_pagar_cuenta);

$cod_factura = $data_pag_cuenta['cod_factura'];

if (isset($_GET['cod_cuentas_pagar'])) {

$borrar_cuentas_pagar  = sprintf("DELETE FROM cuentas_pagar WHERE cod_factura = '$cod_factura'");
$Resultado1 = mysql_query($borrar_cuentas_pagar , $conectar) or die(mysql_error());

$borrar_cuentas_pagar_abonos  = sprintf("DELETE FROM cuentas_pagar_abonos WHERE cod_factura = '$cod_factura'");
$Resultado3 = mysql_query($borrar_cuentas_pagar_abonos , $conectar) or die(mysql_error());

echo "<META HTTP-EQUIV='REFRESH' CONTENT='0.1; ../admin/cuentas_pagar.php'>";
} else {
?>
<td><a href="../admin/cuentas_pagar.php"><center><strong><font color='white'>REGRESAR</font></strong></center></a></td>
<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title></title>
</head>
<body>
</body>
</html>
