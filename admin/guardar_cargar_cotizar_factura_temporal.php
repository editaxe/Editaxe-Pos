<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 

$valor_intro = addslashes($_GET['valor']);
$cod_temporal = intval($_GET['id']);
$campo = addslashes($_GET['campo']);

$sql_modificar_consulta = "SELECT * FROM temporal_cotizar WHERE cod_temporal = '$cod_temporal'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);

$precio_venta_antes_del_descuento = $datos['precio_compra_con_descuento'];

if ($campo == 'unidades_vendidas') {
$unidades_vendidas = $valor_intro;
$vlr_total_venta = $datos['precio_venta'] * $unidades_vendidas;
$vlr_total_compra = $datos['precio_costo'] * $unidades_vendidas;
$descuento = ($precio_venta_antes_del_descuento - $datos['precio_venta']) * $unidades_vendidas;
$vlr_total_venta = $unidades_vendidas * ($datos['precio_venta'] - ($datos['precio_venta'] * ($datos['descuento_ptj'] / 100)));
$cajas = '0';

mysql_query("UPDATE temporal_cotizar SET unidades_vendidas = '$valor_intro', vlr_total_venta = '$vlr_total_venta', vlr_total_compra = '$vlr_total_compra', 
cajas = '$cajas', descuento = '$descuento' WHERE cod_temporal = '$cod_temporal'", $conectar);
} 
if ($campo == 'precio_venta') {
$precio_venta = $valor_intro + ($valor_intro * ($datos['iva_v'] / 100));
$descuento = ($precio_venta_antes_del_descuento - $precio_venta) * $datos['unidades_vendidas'];
$vlr_total_venta = $datos['unidades_vendidas'] * ($precio_venta - ($precio_venta * ($datos['descuento_ptj'] / 100)));

mysql_query("UPDATE temporal_cotizar SET precio_venta = '$precio_venta', vlr_total_venta = '$vlr_total_venta', descuento = '$descuento' 
WHERE cod_temporal = '$cod_temporal'", $conectar);
}
if ($campo == 'descuento_ptj') {
$descuento_ptj = $valor_intro;
$descuento = ($precio_venta_antes_del_descuento - $precio_venta) * $datos['unidades_vendidas'];
$vlr_total_venta = $datos['unidades_vendidas'] * ($datos['precio_venta'] - ($datos['precio_venta'] * ($descuento_ptj / 100)));

mysql_query("UPDATE temporal_cotizar SET vlr_total_venta = '$vlr_total_venta', descuento = '$descuento', 
descuento_ptj = '$descuento_ptj' WHERE cod_temporal = '$cod_temporal'", $conectar);
}
}
?>