<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../evitar_mensaje_error/error.php');
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual     = addslashes($_SESSION['usuario']);
$cuenta            = $cuenta_actual;

$cod_ventas        = intval($_GET['id']);
$valor_intro       = addslashes($_GET['valor']);
$campo             = addslashes($_GET['campo']);

$sql_vent = "SELECT * FROM ventas WHERE cod_ventas = '$cod_ventas'";
$consulta_vent = mysql_query($sql_vent, $conectar) or die(mysql_error());
$datos_vent = mysql_fetch_assoc($consulta_vent);

$cod_productos_var = $datos_vent['cod_productos'];
$cod_factura       = $datos_vent['cod_factura'];
$cod_marcas        = $datos_vent['cod_marcas'];
$cod_proveedores   = $datos_vent['cod_proveedores'];
$nombre_productos  = $datos_vent['nombre_productos'];
$nombre_ccosto     = $datos_vent['nombre_ccosto'];
$precio_compra     = $datos_vent['precio_compra'];
$precio_costo      = $datos_vent['precio_costo'];
$precio_venta      = $datos_vent['precio_venta'];
$vlr_total_compra  = $datos_vent['vlr_total_compra'];
$comentario        = $datos_vent['comentario'];
$cod_clientes      = $datos_vent['cod_clientes'];
$detalles          = $datos_vent['detalles'];
$descuento_ptj     = $datos_vent['descuento_ptj'];
$iva               = $datos_vent['iva'];
$fecha_orig        = $datos_vent['fecha_orig'];
$fecha_mes         = $datos_vent['fecha_mes'];
$fecha_anyo        = $datos_vent['fecha_anyo'];
$anyo              = $datos_vent['anyo'];
$fecha_hora        = $datos_vent['fecha_hora'];
$vendedor          = $datos_vent['vendedor'];
$origen_operacion  = 'ventas';
$fecha             = strtotime(date("Y/m/d"));
$ip                = $_SERVER['REMOTE_ADDR'];
$fecha_time        = time();
$fecha_devolucion  = date("d/m/Y");
$hora_devolucion   = date("H:i:s");
//////////////////////////////////////////////////////////////////////////////////
$sql = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos_var'";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$data = mysql_fetch_assoc($consulta);

//------------------------------------------------ CONDICIONAL PARA EDICION DE LAS UNIDADES VENDIDAS ------------------------------------------------//
if ($campo == 'unidades_vendidas') {
$unidades_vendidas    = $valor_intro;
$vlr_total_venta      = $precio_venta * $unidades_vendidas;

$unidades_actuales    = $datos_vent['unidades_vendidas'];
$und_vend_orig        = $datos_vent['unidades_vendidas'];
$unidades_devueltas   = $unidades_actuales - $valor_intro;
$unidades_faltantes   = $data['unidades_faltantes'] + $unidades_devueltas;

$actualizar_datos_prod = "UPDATE productos SET unidades_faltantes = '$unidades_faltantes' WHERE cod_productos_var = '$cod_productos_var'";
$resultado_datos_prod = mysql_query($actualizar_datos_prod, $conectar) or die(mysql_error());

$actualizar_ventas = "UPDATE ventas SET unidades_vendidas = '$unidades_vendidas', vlr_total_venta = '$vlr_total_venta' 
WHERE cod_productos = '$cod_productos_var' AND cod_factura = '$cod_factura' AND cod_uniq_credito = '$cod_uniq_credito'";
$resultado_ventas = mysql_query($actualizar_ventas, $conectar) or die(mysql_error());

$agregar_operacion = "INSERT INTO operacion (cod_ventas, cod_productos, nombre_productos, origen_operacion, cod_factura, cod_marcas, cod_proveedores, 
nombre_ccosto, unidades_vendidas, und_vend_orig, devoluciones, precio_compra, precio_costo, precio_venta, vlr_total_compra, vlr_total_venta, 
comentario, cod_clientes, detalles, descuento_ptj, iva, fecha_devolucion, hora_devolucion, fecha_orig, fecha, fecha_mes, fecha_anyo, anyo, 
fecha_hora, ip, vendedor, cuenta, fecha_time) 
VALUES ('$cod_ventas', '$cod_productos_var', '$nombre_productos', '$origen_operacion', '$cod_factura', '$cod_marcas', '$cod_proveedores', 
'$nombre_ccosto', '$unidades_vendidas', '$und_vend_orig', '$unidades_devueltas', '$precio_compra', '$precio_costo', '$precio_venta', 
'$vlr_total_compra', '$vlr_total_venta', '$comentario', '$cod_clientes', '$detalles', '$descuento_ptj', '$iva', '$fecha_devolucion', 
'$hora_devolucion', '$fecha_orig', '$fecha', '$fecha_mes', '$fecha_anyo', '$anyo', '$fecha_hora', '$ip', '$vendedor', 
'$cuenta', '$fecha_time')";
$resultado_operacion = mysql_query($agregar_operacion, $conectar) or die(mysql_error());

//------------------------------------------------ CALCULOS PARA EL TOTAL DE DEUDA Y LOS ABONOS ------------------------------------------------//
//------------------------------------------------ CALCULOS PARA EL TOTAL DE DEUDA Y LOS ABONOS ------------------------------------------------//
$sql_prod_fiados = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As vlr_total_venta FROM ventas WHERE cod_factura = '$cod_factura'";
$modificar_prod_fiados = mysql_query($sql_prod_fiados, $conectar) or die(mysql_error());
$datos_fiad = mysql_fetch_assoc($modificar_prod_fiados);

$sum_abonos_valor = "SELECT Sum(abonado) AS abonado FROM cuentas_cobrar_abonos WHERE cod_factura = '$cod_factura'";
$consulta_sum_abonos = mysql_query($sum_abonos_valor, $conectar) or die(mysql_error());
$sum_abonos = mysql_fetch_assoc($consulta_sum_abonos);

$monto_deuda = $datos_fiad['vlr_total_venta'];
$abonado = $sum_abonos['abonado'];

$actualizar_cuentas_cobrar = "UPDATE cuentas_cobrar SET monto_deuda = '$monto_deuda', abonado = '$abonado' WHERE cod_factura = '$cod_factura'";
$resultado_cuentas_cobrar = mysql_query($actualizar_cuentas_cobrar, $conectar) or die(mysql_error());
}
//-------------------------------------- CONDICIONAL PARA LA EDICION DEL PRECIO DE VENTA Y EL TOTAL DE DEUDA Y LOS ABONOS ---------------------------//
//-------------------------------------- CONDICIONAL PARA LA EDICION DEL PRECIO DE VENTA Y EL TOTAL DE DEUDA Y LOS ABONOS ---------------------------//
if ($campo == 'precio_venta') {
$precio_venta      = $valor_intro;
$vlr_total_venta   = $datos_fiados['unidades_vendidas'] * $precio_venta;

$actualizar_ventas = "UPDATE ventas SET precio_venta = '$precio_venta', vlr_total_venta = '$vlr_total_venta' WHERE cod_ventas = '$cod_ventas'";
$resultado_ventas = mysql_query($actualizar_ventas, $conectar) or die(mysql_error());

//------------------------------------------------ CALCULOS PARA EL TOTAL DE DEUDA Y LOS ABONOS ------------------------------------------------//
$sql_prod_fiados = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As vlr_total_venta FROM ventas WHERE cod_factura = '$cod_factura'";
$modificar_prod_fiados = mysql_query($sql_prod_fiados, $conectar) or die(mysql_error());
$datos_fiad = mysql_fetch_assoc($modificar_prod_fiados);

$sum_abonos_valor = "SELECT Sum(abonado) AS abonado FROM cuentas_cobrar_abonos WHERE cod_factura = '$cod_factura'";
$consulta_sum_abonos = mysql_query($sum_abonos_valor, $conectar) or die(mysql_error());
$sum_abonos = mysql_fetch_assoc($consulta_sum_abonos);

$monto_deuda     = $datos_fiad['vlr_total_venta'];
$abonado         = $sum_abonos['abonado'];

$actualizar_cuentas_cobrar = "UPDATE cuentas_cobrar SET monto_deuda = '$monto_deuda', abonado = '$abonado' WHERE cod_factura = '$cod_factura'";
$resultado_cuentas_cobrar = mysql_query($actualizar_cuentas_cobrar, $conectar) or die(mysql_error());
}
}
?>