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
date_default_timezone_set("America/Bogota");
require("funcion_verificar.php");
$requerir_funcion = new bloquear_multiple_intento;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<br>
<body>
<?php
$cod_plan_separe                   = intval($_POST['numero_factura']);
$descuento_factura             = 0;
$vlr_cancelado                 = addslashes($_POST['vlr_cancelado']);
$abono_plan_separe             = $vlr_cancelado;
$cod_clientes                  = intval($_POST['cod_clientes']);
$pagina                        = $_POST['pagina'];
$total_datos                   = intval($_POST['total_datos']);
$fecha_ini_plan_separe         = addslashes($_POST['fecha_ini_plan_separe']);
$fecha_fin_plan_separe         = addslashes($_POST['fecha_fin_plan_separe']);
$fecha_anyo                    = $fecha_ini_plan_separe;
$fecha_pago                    = $fecha_ini_plan_separe;

$suma_plan_separe_temporal = "SELECT Sum(vlr_total_venta -(vlr_total_venta*($descuento_factura/100))) As total_venta, 
Sum((vlr_total_venta - (($descuento_factura/100)*vlr_total_venta))/((iva/100)+(100/100))) As subtotal_base, 
Sum(((vlr_total_venta - (($descuento_factura/100)*vlr_total_venta))/((iva/100)+(100/100)))*(iva/100)) As total_iva, 
Sum(vlr_total_venta*($descuento_factura/100)) AS total_desc, Sum(vlr_total_compra) AS vlr_total_compra FROM plan_separe_temporal WHERE vendedor = '$cuenta_actual'";
$consulta_plan_separe_temporal = mysql_query($suma_plan_separe_temporal, $conectar) or die(mysql_error());
$suma_datos = mysql_fetch_assoc($consulta_plan_separe_temporal);

$subtotal_base                 = $suma_datos['subtotal_base'];
$total_desc                    = $suma_datos['total_desc'];
$total_iva                     = $suma_datos['total_iva'];
$total_venta_temp              = ($suma_datos['total_venta']);
$vlr_total_compra              = ($suma_datos['vlr_total_compra']);
$monto_deuda                   =  $total_venta_temp;

$flete                         = '0';
$vlr_vuelto                    = $vlr_cancelado - $total_venta_temp;
$estado                        = 'cerrado';

$venta_total                   = $total_venta_temp;
$subtotal                      = '0';
$vendedor                      = $cuenta_actual;
$ip                            = $_SERVER['REMOTE_ADDR'];
$devoluciones                  = 0;

$dato_fecha                    = explode('/', $fecha_anyo);
$dia                           = $dato_fecha[0];
$mes                           = $dato_fecha[1];
$anyo                          = $dato_fecha[2];
$fecha_dia                     = $anyo.'/'.$mes.'/'.$dia;
$fecha                         = strtotime($fecha_dia);
$fecha_mes                     = $mes.'/'.$anyo;
$fecha_hora                    = date("H:i:s");
$hora                          = date("H:i:s");

$fecha_actual_hoy              = date("Y/m/d");
$fechas_agotado                = date("d/m/Y");
$fechas_agotado_seg            = strtotime($fecha_actual_hoy);

$obtener_cliente = "SELECT * FROM clientes WHERE cod_clientes = '$cod_clientes'";
$resultado_cliente = mysql_query($obtener_cliente, $conectar) or die(mysql_error());
$matriz_cliente = mysql_fetch_assoc($resultado_cliente);

$nombre_cliente                = $matriz_cliente['nombres'].' '.$matriz_cliente['apellidos'];

$sql_info_imp_factura = "SELECT cod_plan_separe FROM plan_separe_info_impuesto WHERE cod_plan_separe = '$cod_plan_separe'";
$modificar_info_imp_factura = mysql_query($sql_info_imp_factura, $conectar) or die(mysql_error());
$total_encontrado_info_imp_factura = mysql_num_rows($modificar_info_imp_factura);

$sql_autoincremento_plan_separe = "SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$base_datos' AND TABLE_NAME = 'plan_separe'";
$exec_autoincremento_plan_separe = mysql_query($sql_autoincremento_plan_separe, $conectar) or die(mysql_error());
$datos_autoincremento_plan_separe = mysql_fetch_assoc($exec_autoincremento_plan_separe);
$cod_plan_separe                  = $datos_autoincremento_plan_separe['AUTO_INCREMENT'];

$sql_info_plan_separe = "SELECT cod_plan_separe FROM plan_separe WHERE cod_plan_separe = '$cod_plan_separe'";
$modificar_info_plan_separe = mysql_query($sql_info_plan_separe, $conectar) or die(mysql_error());
$total_encontrado_info_plan_separe = mysql_num_rows($modificar_info_plan_separe);

$sql_admin = "SELECT cod_base_caja, cuenta FROM administrador WHERE cuenta = '$cuenta_actual'";
$consulta_admin = mysql_query($sql_admin, $conectar) or die(mysql_error());
$matriz_admin = mysql_fetch_assoc($consulta_admin);

$cod_base_caja = $matriz_admin['cod_base_caja'];
//-------------------------------------- LLAVE DE ENTRADA DEL CONDICIONAL VENDER POR CREDITO ------------------------------------------//
if (isset($_POST['vlr_cancelado'])) {

for ($i=0; $i < $total_datos; $i++) {

$cod_plan_separe_temporal = $_POST['cod_plan_separe_temporal'][$i];

$sql_mconsulta = "SELECT * FROM plan_separe_temporal WHERE cod_plan_separe_temporal = '$cod_plan_separe_temporal' AND vendedor = '$cuenta_actual' ORDER BY cod_plan_separe_temporal DESC";
$mconsulta = mysql_query($sql_mconsulta, $conectar) or die(mysql_error());
$datos_temp = mysql_fetch_assoc($mconsulta);

$cod_productos                 = $datos_temp['cod_productos'];
$nombre_peso                   = $datos_temp['nombre_peso'];

$sqlr_consulta = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos'";
$modificar_consulta = mysql_query($sqlr_consulta, $conectar) or die(mysql_error());
$datos_prod = mysql_fetch_assoc($modificar_consulta);

$tipo                          = $datos_temp['detalles'];

if ($tipo == 'UND') {
$unidades_vendidas             = $datos_temp['unidades_vendidas'] * $datos_prod['unidades'];
$precio_venta                  = $datos_temp['precio_venta'] / $datos_prod['unidades'];
} else {
$unidades_vendidas             = $datos_temp['unidades_vendidas'];
$precio_venta                  = $datos_temp['precio_venta'];
}

$unidades_faltantes            = $datos_prod['unidades_faltantes'] - $unidades_vendidas;
$unidades_vendidas_inv         = $datos_prod['unidades_vendidas'] + $datos_temp['unidades_vendidas'];
$total_util1                   = $datos_prod['unidades_vendidas'] * $datos_prod['precio_venta'];
$total_util2                   = $datos_prod['unidades_vendidas'] * $datos_prod['precio_costo'];
$total_utilidad                = $total_util1 - $total_util2;
$total_mercancia               = $datos_prod['unidades_faltantes'] * $datos_prod['precio_costo'];
$total_venta                   = $datos_prod['unidades_vendidas'] * $datos_prod['precio_costo'];

$cod_proveedores               = $datos_prod['cod_proveedores'];
$cod_marcas                    = $datos_prod['cod_marcas'];
//------------------------------------------------------------------------------------------------------------------------------//
//----------------- CAMPO NUEVO PARA CONTROLAR LA CANTIDAD QUE QUEDA EN EL INVENTARIO DESPUES DE LA VENTA -----------------------//
$unidades_faltantes_inv        = $datos_prod['unidades_faltantes'];
//------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------//
$nombre_productos              = $datos_temp['nombre_productos'];
$und_vend_orig                 = $datos_temp['unidades_vendidas'];
$precio_compra                 = $datos_temp['precio_compra'];
$precio_costo                  = $datos_temp['precio_costo'];

$vlr_total_venta               = $datos_temp['vlr_total_venta'];
$vlr_total_compra_temp         = $datos_temp['vlr_total_compra'];
$iva                           = $datos_temp['iva'];
$descuento_ptj                 = $descuento_factura;
$descuento                     = $datos_temp['descuento'];
$precio_compra_con_descuento   = $datos_temp['precio_compra_con_descuento'];
$vendedor                      = $datos_temp['vendedor'];
$porcentaje_vendedor           = $datos_temp['porcentaje_vendedor'];
$detalles                      = $datos_prod['detalles'];
$nombre_lineas                 = $datos_temp['nombre_lineas'];
$nombre_ccosto                 = $datos_temp['nombre_ccosto'];
$tipo_pago                     = '2';

$cod_uniq_credito              = time() + $i;
//----------------------------- INSERTAR PRODUCTOS A LAS VENTAS -----------------------------//
$agregar_reg_plan_separe_producto = "INSERT INTO plan_separe_producto (cod_productos, cod_plan_separe, cod_clientes, cod_proveedores, cod_marcas, cod_base_caja, nombre_productos, unidades_vendidas, 
und_vend_orig, devoluciones, precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, iva, descuento_ptj, descuento, 
precio_compra_con_descuento, vendedor, ip, fecha, fecha_mes, fecha_anyo, anyo, fecha_hora, porcentaje_vendedor, detalles, tipo_pago, fecha_orig, 
nombre_lineas, nombre_ccosto, unidades_faltantes_inv, nombre_peso)
VALUES ('$cod_productos', '$cod_plan_separe', '$cod_clientes', '$cod_proveedores', '$cod_marcas', '$cod_base_caja', '$nombre_productos', '$unidades_vendidas', '$und_vend_orig', 
'$devoluciones', '$precio_compra', '$precio_costo', '$precio_venta', '$vlr_total_venta', '$vlr_total_compra_temp', '$iva', '$descuento_ptj', '$descuento', 
'$precio_compra_con_descuento', '$vendedor', '$ip', '$fecha', '$fecha_mes', '$fecha_anyo', '$anyo', '$fecha_hora', '$porcentaje_vendedor', 
'$detalles', '$tipo_pago', '$fecha_anyo', '$nombre_lineas', '$nombre_ccosto', '$unidades_faltantes_inv', '$nombre_peso')";
$resultado_plan_separe_producto = mysql_query($agregar_reg_plan_separe_producto, $conectar) or die(mysql_error());
//----------------------------- ACTUALIZAR INVENTARIO DE PRODUCTOS OPERACIONES -----------------------------//
//--------------------------------------------FIN CICLO FOR QUE RECORRE LOS DATOS------------------------------- -----------------------------//
}
//----------------------------- ALERTA CUENTAS POR COBRAR APUNTO DE VENCER -----------------------------//
$separador_fecha               = explode('/', $fecha_pago);
$dias                          = $separador_fecha[0];
$meses                         = $separador_fecha[1];
$anyos                         = $separador_fecha[2];
$fecha_invert                  = $anyos.'/'.$meses.'/'.$dias;
$fecha_seg                     = strtotime($fecha_invert);
//----------------------------- CUENTAS POR COBRAR OPERACIONES -----------------------------//
$sql_productos_fiados = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) as monto_deuda FROM plan_separe_producto WHERE cod_plan_separe = '$cod_plan_separe'";
$modificar_productos_fiados = mysql_query($sql_productos_fiados, $conectar) or die(mysql_error());
$datos_productos_fiados = mysql_fetch_assoc($modificar_productos_fiados);

$agregar_reg_cuentas_cobrar = "INSERT INTO plan_separe_abono (cod_plan_separe, cod_clientes, abono_plan_separe, 
fecha_pago, fecha_invert, fecha_seg, hora, mensaje, vendedor, cuenta, fecha_anyo, fecha_mes, anyo) 
VALUES ('$cod_plan_separe', '$cod_clientes', '$abono_plan_separe', 
'$fecha_pago', '$fecha_seg', '$fecha_seg', '$hora', '$mensaje', '$vendedor', '$vendedor', '$fecha_anyo', '$fecha_mes', '$anyo')";
$resultado_cuentas_cobrar = mysql_query($agregar_reg_cuentas_cobrar, $conectar) or die(mysql_error());

$sum_abonos_valor = "SELECT Sum(abono_plan_separe) AS total_abono_plan_separe FROM plan_separe_abono WHERE cod_plan_separe = '$cod_plan_separe'";
$consulta_sum_abonos = mysql_query($sum_abonos_valor, $conectar) or die(mysql_error());
$sum_abonos = mysql_fetch_assoc($consulta_sum_abonos);
//------------------------------------------------------------------------------------------------------------------------------//
$monto_deuda                   = $datos_productos_fiados['monto_deuda'];
$total_plan_separe             = $datos_productos_fiados['monto_deuda'];
$total_abono_plan_separe       = $sum_abonos['total_abono_plan_separe'];
$total_saldo_plan_separe       = $total_plan_separe - $total_abono_plan_separe;
//----------------------------- INFO INPUESTO FACTURAS OPERACIONES -----------------------------//
if ($total_encontrado_info_imp_factura == 0) {
$agregar_regis = sprintf("UPDATE plan_separe_info_impuesto SET cod_plan_separe = '$cod_plan_separe', cod_clientes = '$cod_clientes', cod_plan_separe = '$cod_plan_separe', 
descuento = '$descuento_factura', estado = '$estado', vendedor = '$cuenta_actual', fecha_dia = '$fecha', fecha_anyo = '$fecha_anyo', 
fecha_mes = '$fecha_mes', fecha_hora = '$fecha_hora', vlr_cancelado = '$vlr_cancelado' 
WHERE vendedor = '$cuenta_actual' AND cod_plan_separe = '$cod_plan_separe'");
$resultado_regis = mysql_query($agregar_regis, $conectar) or die(mysql_error());
} else {
//------------------------------------------------------------------------------------------------------------------------------//
$agregar_regis = sprintf("UPDATE plan_separe_info_impuesto SET cod_plan_separe = '$cod_plan_separe', cod_clientes = '$cod_clientes', vendedor = '$cuenta_actual', fecha_dia = '$fecha', fecha_anyo = '$fecha_anyo', 
fecha_mes = '$fecha_mes', fecha_hora = '$fecha_hora', vlr_cancelado = '$vlr_cancelado', estado = '$estado', descuento = '$descuento_factura' 
WHERE vendedor = '$cuenta_actual' AND cod_plan_separe = '$cod_plan_separe'");
$resultado_regis = mysql_query($agregar_regis, $conectar) or die(mysql_error());
//------------------------------------------------------------------------------------------------------------------------------//
}
//------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------//
if ($total_encontrado_info_plan_separe == 0) {

$agregar_reg_plan_separe_producto = "INSERT INTO plan_separe (cod_plan_separe, cod_clientes, vlr_cancelado, vendedor,  
fecha_dia, fecha_mes, fecha_anyo, anyo, fecha_hora, fecha_ini_plan_separe, fecha_fin_plan_separe, total_plan_separe, 
total_abono_plan_separe, total_saldo_plan_separe)
VALUES ('$cod_plan_separe', '$cod_clientes', '$vlr_cancelado', '$cuenta_actual',  
'$fecha', '$fecha_mes', '$fecha_anyo', '$anyo', '$fecha_hora', '$fecha_ini_plan_separe', '$fecha_fin_plan_separe', 
'$total_plan_separe', '$total_abono_plan_separe', '$total_saldo_plan_separe')";
$resultado_plan_separe_producto = mysql_query($agregar_reg_plan_separe_producto, $conectar) or die(mysql_error());
} else {
//------------------------------------------------------------------------------------------------------------------------------//
$actualizar_plan_separe = sprintf("UPDATE plan_separe SET cod_clientes = '$cod_clientes', vendedor = '$cuenta_actual', vlr_cancelado = '$vlr_cancelado', 
total_plan_separe = '$total_plan_separe', total_abono_plan_separe = '$total_abono_plan_separe', total_saldo_plan_separe = '$total_saldo_plan_separe' 
WHERE cod_plan_separe = '$cod_plan_separe'");
$resultado_plan_separe = mysql_query($actualizar_plan_separe, $conectar) or die(mysql_error());
}

//----------------------------- BORRAR DE TEMPORAL PRODUCTOS VENDIDOS -----------------------------//
$borrar_sql = sprintf("DELETE FROM plan_separe_temporal WHERE vendedor = '$cuenta_actual'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0; ../admin/venta_plan_separe_productos_opcion_imprimir.php?cod_plan_separe=<?php echo $cod_plan_separe ?>&pagina=<?php echo $pagina ?>">
<?php
//----------------------------- LLAVE DE CIERRE DEL CONDICIONAL VENDER POR CONTADO -----------------------------//
}
?>
</body>
</html>