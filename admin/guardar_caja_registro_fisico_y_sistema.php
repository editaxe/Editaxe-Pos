<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 

$valor_intro = addslashes($_GET['valor']);
$campo = addslashes($_GET['campo']);
$cod_caja_registro_fisico = intval($_GET['id']);

$total_ventas_fisico = $valor_intro;

$sql = "UPDATE caja_registro_fisico SET $campo = '$valor_intro' WHERE cod_caja_registro_fisico = '$cod_caja_registro_fisico'";
$actualizar_consulta = mysql_query($sql, $conectar) or die(mysql_error());
}
?>