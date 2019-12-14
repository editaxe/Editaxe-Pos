<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 

$valor_intro = addslashes($_GET['valor']);
$campo = addslashes($_GET['campo']);
$cod_productos = intval($_GET['id']);

mysql_query("UPDATE productos SET $campo = '$valor_intro' WHERE cod_productos = '$cod_productos'", $conectar);
}
?>