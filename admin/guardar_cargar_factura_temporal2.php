<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 

$valor_intro = addslashes($_GET['valor']);
$cod_productos = intval($_GET['id']);
$campo = addslashes($_GET['campo']);

$sql_modificar_consulta = "SELECT * FROM cargar_factura_temporal WHERE cod_productos = '$cod_productos'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);

$sql_productos = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos'";
$productos_consulta = mysql_query($sql_productos, $conectar) or die(mysql_error());
$productos = mysql_fetch_assoc($productos_consulta);

if ($campo == 'cajas') {
$cajas = $valor_intro;
$unidades_total = ($cajas * $datos['unidades']);
$unidades_vendidas = $unidades_total + $productos['unidades_faltantes'];
$calc_dto1 = $datos['precio_compra'] - ($datos['precio_compra'] * ($datos['dto1']/100));
$calc_precio_compra_descuento = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100))));
$valor_iva = $calc_precio_compra_descuento * ($datos['iva']/100);
$precio_costo = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100))) + $valor_iva) / $unidades_total;
$descuento = $datos['precio_compra'] - $calc_precio_compra_descuento;
$precio_comp_dto = $calc_precio_compra_descuento + $valor_iva;
$precio_costo_unit = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100)))) / $unidades_total;
$vlr_total_compra = $precio_costo_unit * $datos['unidades_total'];

mysql_query("UPDATE cargar_factura_temporal SET cajas = '$cajas', unidades_vendidas = '$unidades_vendidas', unidades_total = '$unidades_total',
precio_costo = '$precio_costo', valor_iva = '$valor_iva', precio_compra_con_descuento = '$precio_comp_dto', descuento = '$descuento', 
vlr_total_compra = '$vlr_total_compra' WHERE cod_productos = '$cod_productos'", $conectar);
} 
if ($campo == 'unidades') {
$unidades = $valor_intro;
$unidades_total = ($unidades * $datos['cajas']);
$unidades_vendidas = $unidades_total + $productos['unidades_faltantes'];
$calc_dto1 = $datos['precio_compra'] - ($datos['precio_compra'] * ($datos['dto1']/100));
$calc_precio_compra_descuento = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100))));
$valor_iva = $calc_precio_compra_descuento * ($datos['iva']/100);
$precio_costo = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100))) + $valor_iva) / $unidades_total;
$descuento = $datos['precio_compra'] - $calc_precio_compra_descuento;
$precio_comp_dto = $calc_precio_compra_descuento + $valor_iva;
$precio_costo_unit = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100)))) / $unidades_total;
$vlr_total_compra = $precio_costo_unit * $datos['unidades_total'];

mysql_query("UPDATE cargar_factura_temporal SET unidades = '$unidades', unidades_vendidas = '$unidades_vendidas', unidades_total = '$unidades_total',
precio_costo = '$precio_costo', valor_iva = '$valor_iva', precio_compra_con_descuento = '$precio_comp_dto', descuento = '$descuento', 
vlr_total_compra = '$vlr_total_compra' WHERE cod_productos = '$cod_productos'", $conectar);
}  
if ($campo == 'precio_compra') {
$precio_compra = $valor_intro;
$unidades_total = ($datos['cajas'] * $datos['unidades']);
$calc_dto1 = $precio_compra - ($precio_compra * ($datos['dto1']/100));
$calc_precio_compra_descuento = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100))));
$valor_iva = $calc_precio_compra_descuento * ($datos['iva']/100);
$precio_costo = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100))) + $valor_iva) / $unidades_total;
$descuento = $precio_compra - $calc_precio_compra_descuento;
$precio_comp_dto = $calc_precio_compra_descuento + $valor_iva;
$precio_costo_unit = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100)))) / $unidades_total;
$vlr_total_compra = $precio_costo_unit * $datos['unidades_total'];

mysql_query("UPDATE cargar_factura_temporal SET precio_compra = '$precio_compra', precio_costo = '$precio_costo', valor_iva = '$valor_iva', 
precio_compra_con_descuento = '$precio_comp_dto', vlr_total_compra = '$vlr_total_compra', descuento = '$descuento' WHERE cod_productos = '$cod_productos'", $conectar);
} 
if ($campo == 'dto1') {
$dto1 = $valor_intro;
$unidades_total = ($datos['cajas'] * $datos['unidades']);
$calc_dto1 = $datos['precio_compra'] - ($datos['precio_compra'] * ($dto1/100));
$calc_precio_compra_descuento = (($calc_dto1 - ($calc_dto1 * ($dto2/100))));
$valor_iva = $calc_precio_compra_descuento * ($datos['iva']/100);
$precio_costo = (($calc_dto1 - ($calc_dto1 * ($dto2/100))) + $valor_iva) / $unidades_total;
$descuento = $datos['precio_compra'] - $calc_precio_compra_descuento;
$precio_comp_dto = $calc_precio_compra_descuento + $valor_iva;
$precio_costo_unit = (($calc_dto1 - ($calc_dto1 * ($dto2/100)))) / $unidades_total;
$vlr_total_compra = $precio_costo_unit * $datos['unidades_total'];

mysql_query("UPDATE cargar_factura_temporal SET dto1 = '$dto1', precio_compra_con_descuento = '$precio_comp_dto', precio_costo = '$precio_costo', 
vlr_total_compra = '$vlr_total_compra', descuento = '$descuento' WHERE cod_productos = '$cod_productos'", $conectar);
}
if ($campo == 'dto2') {
$dto2 = $valor_intro;
$unidades_total = ($datos['cajas'] * $datos['unidades']);
$calc_dto1 = $datos['precio_compra'] - ($datos['precio_compra'] * ($datos['dto1']/100));
$calc_precio_compra_descuento = (($calc_dto1 - ($calc_dto1 * ($dto2/100))));
$valor_iva = $calc_precio_compra_descuento * ($datos['iva']/100);
$precio_costo = (($calc_dto1 - ($calc_dto1 * ($dto2/100))) + $valor_iva) / $unidades_total;
$descuento = $datos['precio_compra'] - $calc_precio_compra_descuento;
$precio_comp_dto = $calc_precio_compra_descuento + $valor_iva;
$precio_costo_unit = (($calc_dto1 - ($calc_dto1 * ($dto2/100)))) / $unidades_total;
$vlr_total_compra = $precio_costo_unit * $datos['unidades_total'];

mysql_query("UPDATE cargar_factura_temporal SET dto2 = '$dto2', precio_compra_con_descuento = '$precio_comp_dto', precio_costo = '$precio_costo', 
vlr_total_compra = '$vlr_total_compra', descuento = '$descuento' WHERE cod_productos = '$cod_productos'", $conectar);
}
if ($campo == 'iva') {
$iva = $valor_intro;
$unidades_total = ($datos['cajas'] * $datos['unidades']);
$calc_dto1 = $datos['precio_compra'] - ($datos['precio_compra'] * ($datos['dto1']/100));
$calc_precio_compra_descuento = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100))));
$valor_iva = $calc_precio_compra_descuento * ($iva/100);
$precio_costo = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100))) + $valor_iva) / $unidades_total;
$descuento = $datos['precio_compra'] - $calc_precio_compra_descuento;
$precio_comp_dto = $calc_precio_compra_descuento + $valor_iva;
$precio_costo_unit = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100)))) / $unidades_total;
$vlr_total_compra = $precio_costo_unit * $datos['unidades_total'];

mysql_query("UPDATE cargar_factura_temporal SET iva = '$iva', valor_iva = '$valor_iva', precio_compra_con_descuento = '$precio_comp_dto',
precio_costo = '$precio_costo', vlr_total_compra = '$vlr_total_compra', descuento = '$descuento' WHERE cod_productos = '$cod_productos'", $conectar);
}
if ($campo == 'precio_venta') {
$precio_venta = $valor_intro;
$unidades_total = ($datos['cajas'] * $datos['unidades']);
$calc_dto1 = $datos['precio_compra'] - ($datos['precio_compra'] * ($datos['dto1']/100));
$calc_precio_compra_descuento = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100))));
$valor_iva = $calc_precio_compra_descuento * ($datos['iva']/100);
$precio_costo = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100))) + $valor_iva) / $unidades_total;
$descuento = $datos['precio_compra'] - $calc_precio_compra_descuento;
$precio_comp_dto = $calc_precio_compra_descuento + $valor_iva;
$precio_costo_unit = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100)))) / $unidades_total;
$vlr_total_compra = $precio_costo_unit * $datos['unidades_total'];

mysql_query("UPDATE cargar_factura_temporal SET precio_venta = '$precio_venta', vlr_total_compra = '$vlr_total_compra', precio_costo = '$precio_costo', 
valor_iva = '$valor_iva', precio_compra_con_descuento = '$precio_comp_dto', descuento = '$descuento' WHERE cod_productos = '$cod_productos'", $conectar);
}
if ($campo == 'porcentaje_vendedor') {
$porcentaje_vendedor = $valor_intro;
mysql_query("UPDATE cargar_factura_temporal SET porcentaje_vendedor = '$porcentaje_vendedor' WHERE cod_productos = '$cod_productos'", $conectar);
}
if ($campo == 'ptj_ganancia') {
$ptj_ganancia = $valor_intro;
mysql_query("UPDATE cargar_factura_temporal SET ptj_ganancia = '$ptj_ganancia' WHERE cod_productos = '$cod_productos'", $conectar);
}
if ($campo == 'fechas_vencimiento') {
$fechas_vencimiento = $valor_intro;
$dato_fecha = explode('/', $fechas_vencimiento);
$dia = $dato_fecha[0];
$mes = $dato_fecha[1];
$anyo = $dato_fecha[2];
$formato_Y_m_d = $anyo.'/'.$mes.'/'.$dia;
$fecha_recibida_segundos = strtotime($formato_Y_m_d);

mysql_query("UPDATE cargar_factura_temporal SET fechas_vencimiento = '$fechas_vencimiento', fechas_vencimiento_seg = '$fecha_recibida_segundos' 
WHERE cod_productos = '$cod_productos'", $conectar);
}
if ($campo == 'tope_min') {
$tope_min = $valor_intro;
mysql_query("UPDATE cargar_factura_temporal SET tope_min = '$tope_min' WHERE cod_productos = '$cod_productos'", $conectar);
}
}
?>