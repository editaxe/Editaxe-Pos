<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
date_default_timezone_set("America/Bogota");
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar);
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);

$cod_plan_separe_producto       = intval($_GET['id']);
$valor_intro      = addslashes($_GET['valor']);
$campo            = addslashes($_GET['campo']);

$sql_modificar_consulta = "SELECT * FROM plan_separe_producto WHERE cod_plan_separe_producto = '$cod_plan_separe_producto'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);

$unidades_actuales     = $datos['unidades_vendidas'];
$und_vend_orig         = $unidades_actuales;
$unidades_devueltas    = $unidades_actuales - $valor_intro;
$cod_productos         =  $datos['cod_productos'];
$cod_plan_separe           =  $datos['cod_plan_separe'];
$tipo_pago             =  $datos['tipo_pago'];

$fecha_devolucion      = date("d/m/Y");
$hora_devolucion       = date("H:i:s");

$sql = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos'";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$data = mysql_fetch_assoc($consulta);

$unidades_faltantes    = $data['unidades_faltantes'] + $unidades_devueltas;
$unidades_vendidas     = $data['unidades_vendidas'] - $unidades_devueltas;
$vlr_total_venta       = $datos['precio_venta'] * $valor_intro;
$vlr_total_compra      = $datos['precio_compra'] * $valor_intro;
$total_mercancia       = $vlr_total_compra;
$total_venta           = $vlr_total_venta;
$cod_clientes          = $datos['cod_clientes'];
$fecha                 = $datos['fecha'];
$cod_uniq_credito      = $datos['cod_uniq_credito'];
$comentario            = $datos['comentario'];
$origen_operacion      = 'plan_separe_producto';
$fecha_orig            = $datos['fecha_orig'];
$fecha                 = strtotime(date("Y/m/d"));
$fecha_mes             = $datos['fecha_mes'];
$fecha_anyo            = $datos['fecha_anyo'];
$anyo                  = $datos['anyo'];
$fecha_hora            = $datos['fecha_hora'];
$ip                    = $_SERVER['REMOTE_ADDR'];
$cuenta                = $cuenta_actual;
$fecha_time            = time();
$nombre_productos      = $datos['nombre_productos'];
$cod_marcas            = $datos['cod_marcas'];
$cod_proveedores       = $datos['cod_proveedores'];
$nombre_ccosto         = $datos['nombre_ccosto'];
$precio_compra         = $datos['precio_compra'];
$precio_costo          = $datos['precio_costo'];
$precio_venta          = $datos['precio_venta'];
$detalles              = $datos['detalles'];
$descuento_ptj         = $datos['descuento_ptj'];
$iva                   = $datos['iva'];
$vendedor              = $datos['vendedor'];
//------------------------------------------------------------ unidades_vendidas ------------------------------------------------------------//
//------------------------------------------------------------ unidades_vendidas ------------------------------------------------------------//
if ($campo == 'unidades_vendidas') {
$unidades_vendidas_vent = $valor_intro;

$actualizar_operacion_plan_separe_producto = "UPDATE plan_separe_producto SET unidades_vendidas = '$unidades_vendidas_vent', vlr_total_venta = '$vlr_total_venta', vlr_total_compra = '$vlr_total_compra', 
und_vend_orig = '$und_vend_orig', devoluciones = '$unidades_devueltas', fecha_devolucion = '$fecha_devolucion', hora_devolucion = '$hora_devolucion', 
cuenta = '$cuenta_actual' WHERE cod_plan_separe_producto = '$cod_plan_separe_producto'";
$resultado_plan_separe_producto = mysql_query($actualizar_operacion_plan_separe_producto, $conectar) or die(mysql_error());
//------------------------------------------------------------------------------------------------------------------------------------ //
$actualizar_operacion_productos = "UPDATE productos SET unidades_faltantes = '$unidades_faltantes' WHERE cod_productos_var = '$cod_productos'";
$resultado_productos = mysql_query($actualizar_operacion_productos, $conectar) or die(mysql_error());
//------------------------------------------------------------------------------------------------------------------------------------
if ($tipo_pago == 2) {
$sql_prod_fiados = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As vlr_total_venta FROM plan_separe_producto WHERE cod_plan_separe = '$cod_plan_separe'";
$modificar_prod_fiados = mysql_query($sql_prod_fiados, $conectar) or die(mysql_error());
$datos_fiad = mysql_fetch_assoc($modificar_prod_fiados);

$sum_abonos_valor = "SELECT Sum(abonado) AS abonado FROM cuentas_cobrar_abonos WHERE cod_plan_separe = '$cod_plan_separe'";
$consulta_sum_abonos = mysql_query($sum_abonos_valor, $conectar) or die(mysql_error());
$sum_abonos = mysql_fetch_assoc($consulta_sum_abonos);

$monto_deuda      = $datos_fiad['vlr_total_venta'];
$abonado          = $sum_abonos['abonado'];
$subtotal         = $monto_deuda - $abonado;
//------------------------------------------------------------------------------------------------------------------------------------
$actualizar_cuentas_cobrar = "UPDATE plan_separe SET monto_deuda = '$monto_deuda', abonado = '$abonado', subtotal = '$subtotal' WHERE cod_plan_separe = '$cod_plan_separe'";
$resultado_cuentas_cobrar = mysql_query($actualizar_cuentas_cobrar, $conectar) or die(mysql_error());
}
//------------------------------------------------------------ precio_venta ------------------------------------------------------------//
//------------------------------------------------------------ precio_venta ------------------------------------------------------------//
if ($campo == 'precio_venta') {
$precio_venta = $valor_intro;
$vlr_total_venta = $datos['unidades_vendidas'] * $precio_venta;

$actualizar_datos = "UPDATE plan_separe_producto SET precio_venta = '$precio_venta', vlr_total_venta = '$vlr_total_venta' WHERE cod_plan_separe_producto = '$cod_plan_separe_producto'";
$resultado_datos = mysql_query($actualizar_datos, $conectar) or die(mysql_error());

if ($tipo_pago == 2) {
$sql_prod_fiados = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As vlr_total_venta FROM plan_separe_producto WHERE cod_plan_separe = '$cod_plan_separe'";
$modificar_prod_fiados = mysql_query($sql_prod_fiados, $conectar) or die(mysql_error());
$datos_fiad = mysql_fetch_assoc($modificar_prod_fiados);

$sum_abonos_valor = "SELECT Sum(abono_plan_separe) AS abono_plan_separe FROM plan_separe_abono WHERE cod_plan_separe = '$cod_plan_separe'";
$consulta_sum_abonos = mysql_query($sum_abonos_valor, $conectar) or die(mysql_error());
$sum_abonos = mysql_fetch_assoc($consulta_sum_abonos);

$total_plan_separe      = $datos_fiad['vlr_total_venta'];
$total_abono_plan_separe          = $sum_abonos['abono_plan_separe'];
$total_saldo_plan_separe          = $total_plan_separe - $total_abono_plan_separe;

$actualizar_cuentas_cobrar = "UPDATE plan_separe SET total_plan_separe = '$total_plan_separe', total_abono_plan_separe = '$total_abono_plan_separe', total_saldo_plan_separe = '$total_saldo_plan_separe' WHERE cod_plan_separe = '$cod_plan_separe'";
$resultado_cuentas_cobrar = mysql_query($actualizar_cuentas_cobrar, $conectar) or die(mysql_error());
//----------------------------------------------------------------------- ----------------------------------------------------------//
}
}
//------------------------------------------------------------ cod_productos ------------------------------------------------------------//
//------------------------------------------------------------ cod_productos ------------------------------------------------------------//
if ($campo == 'cod_productos') {
$cod_productos = $valor_intro;

$actualizar_datos = "UPDATE plan_separe_producto SET cod_productos = '$cod_productos' WHERE cod_plan_separe_producto = '$cod_plan_separe_producto'";
$resultado_datos = mysql_query($actualizar_datos, $conectar) or die(mysql_error());
}
//------------------------------------------------------------ nombre_productos ------------------------------------------------------------//
//------------------------------------------------------------ nombre_productos ------------------------------------------------------------//
if ($campo == 'nombre_productos') {
$nombre_productos = strtoupper($valor_intro);

$actualizar_datos = "UPDATE plan_separe_producto SET nombre_productos = '$nombre_productos' WHERE cod_plan_separe_producto = '$cod_plan_separe_producto'";
$resultado_datos = mysql_query($actualizar_datos, $conectar) or die(mysql_error());
}
//------------------------------------------------------------ fecha_anyo ------------------------------------------------------------//
//------------------------------------------------------------ fecha_anyo ------------------------------------------------------------//
if ($campo == 'fecha_anyo') {
$fecha_anyo          = $valor_intro;
$fecha_anyo_vect     = explode('/', $fecha_anyo);
$dia                 = $fecha_anyo_vect[0];
$mes                 = $fecha_anyo_vect[1];
$anyos               = $fecha_anyo_vect[2];
$invert              = $anyos.'/'.$mes.'/'.$dia;
$fecha               = strtotime($invert);
$fecha_mes           = $mes.'/'.$anyos;
$anyo                = $anyos;

$actualizar_datos = "UPDATE plan_separe_producto SET fecha_anyo = '$fecha_anyo', fecha = '$fecha', fecha_mes = '$fecha_mes', anyo = '$anyo' WHERE cod_plan_separe_producto = '$cod_plan_separe_producto'";
$resultado_datos = mysql_query($actualizar_datos, $conectar) or die(mysql_error());
}
//------------------------------------------------------------ comentario ------------------------------------------------------------//
//------------------------------------------------------------ comentario ------------------------------------------------------------//
if ($campo == 'comentario') {
$comentario = strtoupper($valor_intro);

$actualizar_datos = "UPDATE plan_separe_producto SET comentario = '$comentario' WHERE cod_plan_separe_producto = '$cod_plan_separe_producto'";
$resultado_datos = mysql_query($actualizar_datos, $conectar) or die(mysql_error());
}
}
?>