<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 

$valor_intro                  = addslashes($_GET['valor']);
$campo                        = addslashes($_GET['campo']);
$cod_cuentas_cobrar_abonos    = intval($_GET['id']);

$sql_edit_cuenta_cob = "UPDATE cuentas_cobrar_abonos SET $campo = '$valor_intro' WHERE cod_cuentas_cobrar_abonos = '$cod_cuentas_cobrar_abonos'";
$consulta_edit_cuenta_cob = mysql_query($sql_edit_cuenta_cob, $conectar) or die(mysql_error());

$sql = "SELECT cod_factura, cod_proveedores FROM cuentas_cobrar_abonos WHERE cod_cuentas_cobrar_abonos = '$cod_cuentas_cobrar_abonos'";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);

$cod_clientes = $datos['cod_clientes'];

$sql_sum = "SELECT SUM(abonado) AS abonado FROM cuentas_cobrar_abonos WHERE cod_clientes = '$cod_clientes'";
$consulta_sum = mysql_query($sql_sum, $conectar) or die(mysql_error());
$datos_sum = mysql_fetch_assoc($consulta_sum);

$abonado = $datos_sum['abonado'];

$sql_cuenta_cob = "UPDATE cuentas_cobrar SET abonado = '$abonado' WHERE cod_clientes = '$cod_clientes'";
$consulta_cuenta_cob = mysql_query($sql_cuenta_cob, $conectar) or die(mysql_error());
}
?>