<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 

$valor_intro = addslashes($_GET['valor']);
$campo = addslashes($_GET['campo']);
$cod_cargar_factura_temporal = intval($_GET['id']);

$sql_modificar_consulta = "SELECT * FROM cargar_factura_temporal WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);

$cod_productos = $datos['cod_productos'];
$detalles = $datos['detalles'];

$sql_productos = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos'";
$productos_consulta = mysql_query($sql_productos, $conectar) or die(mysql_error());
$productos = mysql_fetch_assoc($productos_consulta);

//PARA CAJAS
if ($campo == 'cajas') {
$cajas = $valor_intro;
$unidades_total = ($datos['unidades'] * $cajas);
$unidades_vendidas = $unidades_total + $productos['unidades_faltantes'];
$calc_dto1 = ($datos['precio_compra'] * $cajas) - (($datos['precio_compra'] * $cajas) * ($datos['dto1']/100));
$calc_precio_compra_descuento = ($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100)));
//$precio_costo = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100))) + $valor_iva) / $unidades_total;
//$precio_costo = $datos['precio_compra'] + ($datos['precio_compra'] * ($datos['iva']/100));
$precio_costo = $datos['precio_compra'] - ($datos['precio_compra'] - ($datos['precio_compra']/(($datos['iva']/100)+1)));
$valor_iva = $datos['precio_compra'] - $precio_costo;
$descuento = ($datos['precio_compra'] * $cajas) - $calc_precio_compra_descuento;
//$precio_compra_con_descuento = $calc_precio_compra_descuento + $valor_iva;
$precio_compra_con_descuento = $datos['precio_compra'] * $unidades_total;
$precio_costo_unit = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100)))) / $unidades_total;
$vlr_total_compra = $precio_costo_unit * $unidades_total;

mysql_query("UPDATE cargar_factura_temporal SET cajas = '$cajas', unidades_vendidas = '$unidades_vendidas', unidades_total = '$unidades_total',
precio_costo = '$precio_costo', valor_iva = '$valor_iva', precio_compra_con_descuento = '$precio_compra_con_descuento', descuento = '$descuento', 
vlr_total_compra = '$vlr_total_compra' WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'", $conectar);
}
//PARA UNIDADES
if ($campo == 'unidades') {
$unidades = $valor_intro;
$unidades_total = ($unidades * $datos['cajas']);
$unidades_vendidas = $unidades_total + $productos['unidades_faltantes'];
$calc_dto1 = ($datos['precio_compra'] * $datos['cajas']) - (($datos['precio_compra'] * $datos['cajas']) * ($datos['dto1']/100));
$calc_precio_compra_descuento = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100))));
//$precio_costo = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100))) + $valor_iva) / $unidades_total;
//$precio_costo = $datos['precio_compra'] + ($datos['precio_compra'] * ($datos['iva']/100));
$precio_costo = $datos['precio_compra'] - ($datos['precio_compra'] - ($datos['precio_compra']/(($datos['iva']/100)+1)));
$valor_iva = $datos['precio_compra'] - $precio_costo;
$descuento = ($datos['precio_compra'] * $datos['cajas']) - $calc_precio_compra_descuento;
//$precio_compra_con_descuento = $calc_precio_compra_descuento + $valor_iva;
$precio_compra_con_descuento = $datos['precio_compra'] * $unidades_total;
$precio_costo_unit = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100)))) / $unidades_total;
$vlr_total_compra = $precio_costo_unit * $unidades_total;

mysql_query("UPDATE cargar_factura_temporal SET unidades = '$unidades', unidades_vendidas = '$unidades_vendidas', unidades_total = '$unidades_total',
precio_costo = '$precio_costo', valor_iva = '$valor_iva', precio_compra_con_descuento = '$precio_compra_con_descuento', descuento = '$descuento', 
vlr_total_compra = '$vlr_total_compra' WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'", $conectar);
}
//PARA PRECIO COMPRA
if ($campo == 'precio_compra') {
$precio_compra = $valor_intro;
$unidades_total = ($datos['cajas'] * $datos['unidades']);
$calc_dto1 = ($precio_compra * $datos['cajas']) - (($precio_compra * $datos['cajas']) * ($datos['dto1']/100));
$calc_precio_compra_descuento = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100))));
//$precio_costo = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100))) + $valor_iva) / $unidades_total;
//$precio_costo = $precio_compra + ($precio_compra * ($datos['iva']/100));
$precio_costo = $precio_compra - ($precio_compra - ($precio_compra/(($datos['iva']/100)+1)));
$valor_iva = $precio_compra - $precio_costo;
$descuento = ($precio_compra * $datos['cajas']) - $calc_precio_compra_descuento;
//$precio_compra_con_descuento = $calc_precio_compra_descuento + $valor_iva;
$precio_compra_con_descuento = $precio_compra * $unidades_total;
$precio_costo_unit = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100)))) / $unidades_total;
$vlr_total_compra = $precio_costo_unit * $unidades_total;
$descripcion = $precio_compra.' - '.$datos['vlr_total_venta'];

mysql_query("UPDATE cargar_factura_temporal SET precio_compra = '$precio_compra', precio_costo = '$precio_costo', valor_iva = '$valor_iva', 
precio_compra_con_descuento = '$precio_compra_con_descuento', vlr_total_compra = '$vlr_total_compra', descuento = '$descuento', 
fecha_mes = '$descripcion' WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'", $conectar);
}
//PARA IVA
if ($campo == 'iva') {
$iva = $valor_intro;	
$unidades_total = ($datos['cajas'] * $datos['unidades']);
$unidades_vendidas = $unidades_total + $productos['unidades_faltantes'];
$calc_dto1 = ($datos['precio_compra'] * $datos['cajas']) - (($datos['precio_compra'] * $datos['cajas']) * ($datos['dto1']/100));
$calc_precio_compra_descuento = ($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100)));
$precio_costo = $datos['precio_compra'] - ($datos['precio_compra'] - ($datos['precio_compra']/(($iva/100)+1)));
$valor_iva = $datos['precio_compra'] - $precio_costo;
$descuento = ($datos['precio_compra'] * $datos['cajas']) - $calc_precio_compra_descuento;
$precio_compra_con_descuento = $datos['precio_compra'] * $unidades_total;
$precio_costo_unit = (($calc_dto1 - ($calc_dto1 * ($datos['dto2']/100)))) / $unidades_total;
$vlr_total_compra = $precio_costo_unit * $unidades_total;

mysql_query("UPDATE cargar_factura_temporal SET iva = '$iva', valor_iva = '$valor_iva', precio_compra_con_descuento = '$precio_compra_con_descuento',
precio_costo = '$precio_costo', vlr_total_compra = '$vlr_total_compra', descuento = '$descuento' 
WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'", $conectar);
}
//PARA PRECIO VENTA TOTAL
if ($campo == 'precio_venta') {
$precio_venta = $valor_intro;
$descripcion = $datos['precio_compra'].' - '.$datos['vlr_total_venta'];

mysql_query("UPDATE cargar_factura_temporal SET precio_venta = '$precio_venta', fecha_mes = '$descripcion' 
WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'", $conectar);
}
if ($campo == 'precio_venta2') {
$precio_venta2 = $valor_intro;
$descripcion = $datos['precio_compra'].' - '.$datos['vlr_total_venta'];

mysql_query("UPDATE cargar_factura_temporal SET precio_venta2 = '$precio_venta2', fecha_mes = '$descripcion' 
WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'", $conectar);
}
if ($campo == 'precio_venta3') {
$precio_venta3 = $valor_intro;
$descripcion = $datos['precio_compra'].' - '.$datos['vlr_total_venta'];

mysql_query("UPDATE cargar_factura_temporal SET precio_venta3 = '$precio_venta3', fecha_mes = '$descripcion' 
WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'", $conectar);
}
if ($campo == 'precio_venta4') {
$precio_venta4 = $valor_intro;
$descripcion = $datos['precio_compra'].' - '.$datos['vlr_total_venta'];

mysql_query("UPDATE cargar_factura_temporal SET precio_venta4 = '$precio_venta4', fecha_mes = '$descripcion' 
WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'", $conectar);
}
if ($campo == 'precio_venta5') {
$precio_venta5 = $valor_intro;
$descripcion = $datos['precio_compra'].' - '.$datos['vlr_total_venta'];

mysql_query("UPDATE cargar_factura_temporal SET precio_venta5 = '$precio_venta5', fecha_mes = '$descripcion' 
WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'", $conectar);
}
//PARA PRECIO VENTA LITROS
if ($campo == 'vlr_total_venta') {
$vlr_total_venta = $valor_intro;
$precio_venta = $vlr_total_venta / $datos['unidades'];
$descripcion = $datos['precio_compra'].' - '.$vlr_total_venta;

mysql_query("UPDATE cargar_factura_temporal SET vlr_total_venta = '$vlr_total_venta', precio_venta = '$precio_venta', fecha_mes = '$descripcion' 
WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'", $conectar);
}
//PARA PORCENTAJE GANANCIA
if ($campo == 'ptj_ganancia') {
$ptj_ganancia = $valor_intro;
$vlr_total_venta = $datos['precio_compra'] + ($datos['precio_compra'] * ($ptj_ganancia/100));
$precio_venta = $vlr_total_venta / $datos['unidades'];

mysql_query("UPDATE cargar_factura_temporal SET ptj_ganancia = '$ptj_ganancia', vlr_total_venta = '$vlr_total_venta', precio_venta = '$precio_venta' 
WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'", $conectar);
}
//PARA DTO1
if ($campo == 'dto1') {
$dto1 = $valor_intro;
$unidades_total = ($datos['cajas'] * $datos['unidades']);
$calc_dto1 = ($datos['precio_compra'] * $datos['cajas']) - (($datos['precio_compra'] * $datos['cajas']) * ($dto1/100));
$calc_precio_compra_descuento = (($calc_dto1 - ($calc_dto1 * ($dto2/100))));
$valor_iva = $calc_precio_compra_descuento * ($datos['iva']/100);
$precio_costo = (($calc_dto1 - ($calc_dto1 * ($dto2/100))) + $valor_iva) / $unidades_total;
$descuento = ($datos['precio_compra'] * $datos['cajas']) - $calc_precio_compra_descuento;
$precio_compra_con_descuento = $calc_precio_compra_descuento + $valor_iva;
$precio_costo_unit = (($calc_dto1 - ($calc_dto1 * ($dto2/100)))) / $unidades_total;
$vlr_total_compra = $precio_costo_unit * $unidades_total;

mysql_query("UPDATE cargar_factura_temporal SET dto1 = '$dto1', precio_compra_con_descuento = '$precio_compra_con_descuento', precio_costo = '$precio_costo', 
vlr_total_compra = '$vlr_total_compra', descuento = '$descuento' WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'", $conectar);
}
//PARA DTO2
if ($campo == 'dto2') {
$dto2 = $valor_intro;
$unidades_total = ($datos['cajas'] * $datos['unidades']);
$calc_dto1 = ($datos['precio_compra'] * $datos['cajas']) - (($datos['precio_compra'] * $datos['cajas']) * ($datos['dto1']/100));
$calc_precio_compra_descuento = (($calc_dto1 - ($calc_dto1 * ($dto2/100))));
$valor_iva = $calc_precio_compra_descuento * ($datos['iva']/100);
$precio_costo = (($calc_dto1 - ($calc_dto1 * ($dto2/100))) + $valor_iva) / $unidades_total;
$descuento = ($datos['precio_compra'] * $datos['cajas']) - $calc_precio_compra_descuento;
$precio_compra_con_descuento = $calc_precio_compra_descuento + $valor_iva;
$precio_costo_unit = (($calc_dto1 - ($calc_dto1 * ($dto2/100)))) / $unidades_total;
$vlr_total_compra = $precio_costo_unit * $unidades_total;

mysql_query("UPDATE cargar_factura_temporal SET dto2 = '$dto2', precio_compra_con_descuento = '$precio_compra_con_descuento', precio_costo = '$precio_costo', 
vlr_total_compra = '$vlr_total_compra', descuento = '$descuento' WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'", $conectar);
}
//PARA PORCENTAJE VENDEDOR
if ($campo == 'porcentaje_vendedor') {
$porcentaje_vendedor = $valor_intro;
mysql_query("UPDATE cargar_factura_temporal SET porcentaje_vendedor = '$porcentaje_vendedor' WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'", $conectar);
}
//PARA FECHA VENCIMIENTO
if ($campo == 'fechas_vencimiento') {
$fechas_vencimiento = $valor_intro;
$dato_fecha = explode('/', $fechas_vencimiento);
$dia = $dato_fecha[0];
$mes = $dato_fecha[1];
$anyo = $dato_fecha[2];
$formato_Y_m_d = $anyo.'/'.$mes.'/'.$dia;
$fecha_recibida_segundos = strtotime($formato_Y_m_d);

mysql_query("UPDATE cargar_factura_temporal SET fechas_vencimiento = '$fechas_vencimiento', fechas_vencimiento_seg = '$fecha_recibida_segundos' 
WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'", $conectar);
}
//PARA TOPE MINIMO
if ($campo == 'tope_min') {
$tope_min = $valor_intro;
mysql_query("UPDATE cargar_factura_temporal SET tope_min = '$tope_min' WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'", $conectar);
}
//PARA DETALLES
if ($campo == 'detalles') {
$detalles = $valor_intro;
mysql_query("UPDATE cargar_factura_temporal SET detalles = '$detalles' WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'", $conectar);
}
//PARA IVA VALOR
if ($campo == 'iva_v') {
$iva_v = $valor_intro;
mysql_query("UPDATE cargar_factura_temporal SET iva_v = '$iva_v' WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'", $conectar);
}
//PARA NOMRE PRODUCTOS
if ($campo == 'nombre_productos') {
$nombre_productos = $valor_intro;
mysql_query("UPDATE cargar_factura_temporal SET nombre_productos = '$nombre_productos' WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'", $conectar);
}
//PARA CODIGO ORIGINAL
if ($campo == 'cod_original') {
$cod_original = $valor_intro;
mysql_query("UPDATE cargar_factura_temporal SET cod_original = '$cod_original' WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'", $conectar);
}
//PARA CODIFICACION
if ($campo == 'codificacion') {
$codificacion = $valor_intro;
mysql_query("UPDATE cargar_factura_temporal SET codificacion = '$codificacion' WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'", $conectar);
}
}
?>