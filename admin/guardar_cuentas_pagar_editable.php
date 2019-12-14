<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 

$valor_intro = addslashes($_GET['valor']);
$campo = addslashes($_GET['campo']);
$cod_cuentas_pagar = intval($_GET['id']);

mysql_query("UPDATE cuentas_pagar SET $campo = '$valor_intro' WHERE cod_cuentas_pagar = '$cod_cuentas_pagar'", $conectar);
}
?>