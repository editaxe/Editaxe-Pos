<?
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 

$unidades_vendidas = addslashes($_GET['valor']);
$cod_productos = intval($_GET['id']);

$sql_modificar_consulta = "SELECT * FROM cargar_factura_temporal2 where cod_productos = '$cod_productos'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);

$vlr_total_venta = $datos['precio_venta'] * $unidades_vendidas;
$vlr_total_compra = $datos['precio_compra'] * $unidades_vendidas;

mysql_query("UPDATE cargar_factura_temporal2 SET unidades_vendidas = '$unidades_vendidas', vlr_total_venta = '$vlr_total_venta', vlr_total_compra = '$vlr_total_compra' 
	WHERE cod_productos='$cod_productos'", $conectar);
}
?>