<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");

$cod_ventas           = intval($_GET['cod_ventas']);
$pagina               = $_GET['pagina'];

$datos_producto = "SELECT * FROM ventas WHERE cod_ventas = '$cod_ventas'";
$consulta_toten = mysql_query($datos_producto, $conectar) or die(mysql_error());
$producto_factura = mysql_fetch_assoc($consulta_toten);

$cantidad            = $producto_factura['unidades_vendidas'];
$cod_productos       = $producto_factura['cod_productos'];
$cod_factura         = $producto_factura['cod_factura'];
$cod_clientes        = $producto_factura['cod_clientes'];
$nombre_productos    = $producto_factura['nombre_productos'];
$precio_venta        = $producto_factura['precio_venta'];
$vlr_total_venta     = $producto_factura['vlr_total_venta'];
$fecha               = date("d/m/Y - H:i:s");

$buscar_producto_actual = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos'";
$consulta_total = mysql_query($buscar_producto_actual, $conectar) or die(mysql_error());
$producto_actual = mysql_fetch_assoc($consulta_total);

$unidades_faltantes  = $producto_actual['unidades_faltantes'] + $cantidad;
$unidades_vendidas   = $producto_actual['unidades_vendidas'] - $cantidad;

if ((isset($_GET['cod_ventas'])) && ($_GET['cod_ventas'] != "")) {

$borrar_de_ventas = sprintf("DELETE FROM ventas WHERE cod_ventas = '$cod_ventas'");
$Resultado2 = mysql_query($borrar_de_ventas, $conectar) or die(mysql_error());

$productos_regresados = sprintf("UPDATE productos SET unidades_faltantes = '$unidades_faltantes' WHERE cod_productos_var = '$cod_productos'");
$Resultado3 = mysql_query($productos_regresados, $conectar) or die(mysql_error());

$factura_producto_cancelado = "INSERT INTO factura_producto_cancelado (vendedor, cliente, cod_productos, cod_factura, nombre_productos, 
unidades_vendidas, vlr_unitario, vlr_total, fecha)
VALUES ('$cuenta_actual', '$cod_clientes', '$cod_productos', '$cod_factura', '$nombre_productos', 
'$cantidad', '$precio_venta', '$vlr_total_venta', '$fecha')";
$Resultado4 = mysql_query($factura_producto_cancelado, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV='REFRESH' CONTENT='0.2; ../admin/buscar_facturas_listado.php?cod_factura=<?php echo $cod_factura ?>'>
<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
</body>
</html>
