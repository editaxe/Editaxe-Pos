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

$datos_info = "SELECT * FROM info_impuesto_facturas WHERE (estado = 'abierto') AND (vendedor = '$cuenta_actual')";
$consulta_info = mysql_query($datos_info, $conectar) or die(mysql_error());
$info = mysql_fetch_assoc($consulta_info);
$factura_ocupada = mysql_num_rows($consulta_info);

$valor                         = addslashes($_REQUEST['valor']);
$campo                         = addslashes($_REQUEST['campo']);
$cod_info_impuesto_facturas    = intval($_REQUEST['id']);

if ($campo=='cod_clientes') {
$cod_clientes               = intval($valor);

$data_sql = ("UPDATE cargar_factura_temporal SET cod_clientes = '$cod_clientes' WHERE cod_info_impuesto_facturas = '$cod_info_impuesto_facturas'");
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());
}

if ($campo=='descuento') {
$descuento                  = intval($valor);

$data_sql = ("UPDATE cargar_factura_temporal SET descuento = '$descuento' WHERE cod_info_impuesto_facturas = '$cod_info_impuesto_facturas'");
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());

$data_sql1 = ("UPDATE temporal SET descuento = '$descuento' WHERE vendedor = '$cuenta_actual'");
$exec_data1 = mysql_query($data_sql1, $conectar) or die(mysql_error());
}

if ($campo=='tipo_pago') {
$tipo_pago                  = intval($valor);

$data_sql = ("UPDATE cargar_factura_temporal SET tipo_pago = '$tipo_pago' WHERE cod_info_impuesto_facturas = '$cod_info_impuesto_facturas'");
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());

$data_sql1 = ("UPDATE temporal SET tipo_pago = '$tipo_pago' WHERE vendedor = '$cuenta_actual'");
$exec_data1 = mysql_query($data_sql1, $conectar) or die(mysql_error());
}

if ($campo=='bolsa') {
$bolsa               = intval($valor);

$data_sql = ("UPDATE cargar_factura_temporal SET bolsa = '$bolsa' WHERE cod_info_impuesto_facturas = '$cod_info_impuesto_facturas'");
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());
}

?>