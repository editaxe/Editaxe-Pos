<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 

$valor_intro         = addslashes($_GET['valor']);
$cod_temporal        = intval($_GET['id']);
$campo               = addslashes($_GET['campo']);

$sql_modificar_consulta = "SELECT * FROM temporal WHERE cod_temporal = '$cod_temporal'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);

$precio_venta_antes_del_descuento = $datos['precio_compra_con_descuento'];

if ($campo == 'unidades_vendidas') {
$unidades_vendidas   = $valor_intro;
$vlr_total_venta     = $datos['precio_venta'] * $unidades_vendidas;
$vlr_total_compra    = $datos['precio_costo'] * $unidades_vendidas;
$descuento           = ($precio_venta_antes_del_descuento - $datos['precio_venta']) * $unidades_vendidas;
$cajas               = '0';

$data_sql = "UPDATE temporal SET unidades_vendidas = '$valor_intro', vlr_total_venta = '$vlr_total_venta', vlr_total_compra = '$vlr_total_compra', 
cajas = '$cajas', descuento = '$descuento' WHERE cod_temporal = '$cod_temporal'";
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());

$data_sql = "UPDATE temporal_copia SET unidades_vendidas = '$valor_intro', vlr_total_venta = '$vlr_total_venta', vlr_total_compra = '$vlr_total_compra', 
cajas = '$cajas', descuento = '$descuento' WHERE cod_temporal = '$cod_temporal'";
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());
} 
if ($campo == 'precio_venta') {
$precio_venta = $valor_intro + ($valor_intro * ($datos['iva_v'] / 100));
$descuento = ($precio_venta_antes_del_descuento - $precio_venta) * $datos['unidades_vendidas'];
$vlr_total_venta = $datos['unidades_vendidas'] * $precio_venta;

$data_sql = "UPDATE temporal SET precio_venta = '$precio_venta', vlr_total_venta = '$vlr_total_venta', descuento = '$descuento' 
WHERE cod_temporal = '$cod_temporal'";
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());

$data_sql = "UPDATE temporal_copia SET precio_venta = '$precio_venta', vlr_total_venta = '$vlr_total_venta', descuento = '$descuento' 
WHERE cod_temporal = '$cod_temporal'";
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());
}
if ($campo == 'iva_v') {
$increment_p_venta = $valor_intro;
$precio_venta = $datos['precio_compra_con_descuento'] + ($datos['precio_compra_con_descuento'] * ($increment_p_venta / 100));
$descuento = ($precio_venta_antes_del_descuento - $precio_venta) * $datos['unidades_vendidas'];
$vlr_total_venta = $datos['unidades_vendidas'] * $precio_venta;

$data_sql = "UPDATE temporal SET iva_v = '$increment_p_venta', precio_venta = '$precio_venta', vlr_total_venta = '$vlr_total_venta', descuento = '$descuento' 
WHERE cod_temporal = '$cod_temporal'";
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());

$data_sql = "UPDATE temporal_copia SET iva_v = '$increment_p_venta', precio_venta = '$precio_venta', vlr_total_venta = '$vlr_total_venta', descuento = '$descuento' 
WHERE cod_temporal = '$cod_temporal'";
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());
}

if($campo == 'numero_factura') {
$cod_factura = intval($valor_intro);
$cod_info_impuesto_facturas = $cod_temporal;

$data_sql = "UPDATE info_impuesto_facturas SET cod_factura = '$cod_factura' WHERE cod_info_impuesto_facturas = '$cod_info_impuesto_facturas'";
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());
}
if($campo == 'fecha_anyo') {
$fecha_anyo = addslashes($valor_intro);
$cod_info_impuesto_facturas = $cod_temporal;

$frag = explode('/', $fecha_anyo);
$dia = $frag[0];
$mes = $frag[1];
$anyo = $frag[2];
$fecha_dia = $anyo.'/'.$mes.'/'.$dia;
$fecha_mes = $mes.'/'.$anyo;
$fecha_hora = date("H:i:s");

$data_sql = "UPDATE info_impuesto_facturas SET fecha_anyo = '$fecha_anyo', fecha_dia = '$fecha_dia', fecha_mes = '$fecha_mes', 
anyo = '$anyo', fecha_hora = '$fecha_hora' WHERE cod_info_impuesto_facturas = '$cod_info_impuesto_facturas'";
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());
}
if($campo == 'nombre_ccosto') {
$nombre_ccosto = strtoupper(addslashes($valor_intro));
$cod_info_impuesto_facturas = $cod_temporal;

$data_sql = "UPDATE info_impuesto_facturas SET nombre_ccosto = '$nombre_ccosto' WHERE cod_info_impuesto_facturas = '$cod_info_impuesto_facturas'";
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());
}
if($campo == 'garantia_meses') {
$garantia_meses = strtoupper(addslashes($valor_intro));
$cod_info_impuesto_facturas = $cod_temporal;

$data_sql = "UPDATE info_impuesto_facturas SET garantia_meses = '$garantia_meses' WHERE cod_info_impuesto_facturas = '$cod_info_impuesto_facturas'";
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());
}
if($campo == 'observacion') {
$observacion = (addslashes($valor_intro));
$cod_info_impuesto_facturas = $cod_temporal;

$data_sql = "UPDATE info_impuesto_facturas SET observacion = '$observacion' WHERE cod_info_impuesto_facturas = '$cod_info_impuesto_facturas'";
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());
}
if($campo == 'fecha_remision') {
$fecha_remision = (addslashes($valor_intro));
$cod_info_impuesto_facturas = $cod_temporal;

$data_sql = "UPDATE info_impuesto_facturas SET fecha_remision = '$fecha_remision' WHERE cod_info_impuesto_facturas = '$cod_info_impuesto_facturas'";
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());
}
if($campo == 'vlr_cancelado') {
$vlr_cancelado = (addslashes($valor_intro));
$cod_info_impuesto_facturas = $cod_temporal;

$data_sql = "UPDATE info_impuesto_facturas SET vlr_cancelado = '$vlr_cancelado' WHERE cod_info_impuesto_facturas = '$cod_info_impuesto_facturas'";
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());
}

if($campo == 'direccion_cli') {
$direccion_cli = strtoupper(addslashes($valor_intro));
$cod_info_impuesto_facturas = $cod_temporal;

$datos_info_factura = "SELECT cod_clientes FROM info_impuesto_facturas WHERE cod_info_impuesto_facturas = '$cod_info_impuesto_facturas'";
$consulta_info_factura = mysqli_query($conectar, $datos_info_factura) or die(mysql_error());
$info_factura = mysqli_fetch_assoc($consulta_info_factura);

$cod_clientes = $info_factura['cod_clientes'];

$data_sql = "UPDATE clientes SET direccion = '$direccion_cli' WHERE cod_clientes = '$cod_clientes'";
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());
}
if($campo == 'ciudad_cli') {
$ciudad_cli = strtoupper(addslashes($valor_intro));
$cod_info_impuesto_facturas = $cod_temporal;

$datos_info_factura = "SELECT cod_clientes FROM info_impuesto_facturas WHERE cod_info_impuesto_facturas = '$cod_info_impuesto_facturas'";
$consulta_info_factura = mysqli_query($conectar, $datos_info_factura) or die(mysql_error());
$info_factura = mysqli_fetch_assoc($consulta_info_factura);

$cod_clientes = $info_factura['cod_clientes'];

$data_sql = "UPDATE clientes SET ciudad = '$ciudad_cli' WHERE cod_clientes = '$cod_clientes'";
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());
}
if($campo == 'telefono_cli') {
$telefono_cli = strtoupper(addslashes($valor_intro));
$cod_info_impuesto_facturas = $cod_temporal;

$datos_info_factura = "SELECT cod_clientes FROM info_impuesto_facturas WHERE cod_info_impuesto_facturas = '$cod_info_impuesto_facturas'";
$consulta_info_factura = mysqli_query($conectar, $datos_info_factura) or die(mysql_error());
$info_factura = mysqli_fetch_assoc($consulta_info_factura);

$cod_clientes = $info_factura['cod_clientes'];

$data_sql = "UPDATE clientes SET telefono = '$telefono_cli' WHERE cod_clientes = '$cod_clientes'";
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());
}

}
?>