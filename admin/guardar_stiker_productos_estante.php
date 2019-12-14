<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
$cuenta_actual = addslashes($_SESSION['usuario']);
include("../session/funciones_admin.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
  } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");

$total_datos = intval($_POST['total_datos']);

$suma_factura = "SELECT MAX(cod_factura) as cod_factura FROM stiker_productos_estante";
$consulta_suma = mysql_query($suma_factura, $conectar) or die(mysql_error());
$suma = mysql_fetch_assoc($consulta_suma);

if (($_POST['cod_factura'] == "") || ($_POST['cod_factura'] == 0)) { $cod_factura = $suma['cod_factura'] + 1; } else { $cod_factura = intval($_POST['cod_factura']); }

if (isset($_POST['cod_stiker_productos_estante_temporal'])) {

for ($i=0; $i < $total_datos; $i++) {
$cod_stiker_productos_estante_temporal = $_POST['cod_stiker_productos_estante_temporal'][$i];

$agregar_facturas_cargadas_inv = "INSERT INTO stiker_productos_estante (cod_productos, nombre_productos, unidades, cajas, unidades_total, unidades_vendidas, precio_compra, 
precio_costo, precio_venta, precio_venta2, precio_venta3, precio_venta4, precio_venta5, 
vlr_total_venta, vlr_total_compra, precio_compra_con_descuento, detalles, 
dto1, dto2, iva, iva_v, cod_factura, cod_original, codificacion, porcentaje_vendedor, vendedor, fecha, 
fecha_mes, fecha_anyo, fecha_hora, ip, cod_dependencia)
SELECT cod_productos, nombre_productos, unidades, cajas, unidades_total, unidades_vendidas, precio_compra, 
precio_costo, precio_venta, precio_venta2, precio_venta3, precio_venta4, precio_venta5, 
vlr_total_venta, vlr_total_compra, precio_compra_con_descuento, detalles, 
dto1, dto2, iva, iva_v, '$cod_factura', cod_original, codificacion, porcentaje_vendedor, vendedor, fecha, 
fecha_mes, fecha_anyo, fecha_hora, ip, cod_dependencia FROM stiker_productos_estante_temporal";
$resultado_facturas_cargadas_inv = mysql_query($agregar_facturas_cargadas_inv, $conectar) or die(mysql_error());

$borrar_sql = sprintf("DELETE FROM stiker_productos_estante_temporal WHERE vendedor = '$cuenta_actual'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());
}
echo "<br><br><center><font size='6' color='yellow'>LA FACTURA NO: ".$cod_factura." SE HA INTRODUCIDO EXITOSAMENTE</font><center>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; ../admin/stiker_productos_estante_por_factura.php">';
} else {
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; ../admin/cargar_factura_temporal.php">';
}
?>