<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 

$valor_intro = addslashes($_GET['valor']);
$campo = addslashes($_GET['campo']);
$cod_ventas = intval($_GET['id']);

mysql_query("UPDATE ventas SET $campo = '$valor_intro' WHERE cod_ventas = '$cod_ventas'", $conectar);
}
?>