<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
      } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
$cod_base_caja = intval($_SESSION['cod_base_caja']);

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
$sql_factura_max_venta = "SELECT MAX(cod_factura) AS cod_factura FROM ventas";
$consultar_factura_max_venta = mysql_query($sql_factura_max_venta, $conectar);
$info_factura_max_venta = mysql_fetch_assoc($consultar_factura_max_venta);

if (($_POST['numero_factura'] == 0) || ($_POST['numero_factura'] == '')) { $cod_factura = $info_factura_max_venta['cod_factura'] +1; } else { $cod_factura = intval($_POST['numero_factura']); }
$cod_info_impuesto_facturas  = intval($_POST['cod_info_impuesto_facturas']);

$descuento_factura           = addslashes($_POST['descuento_factura']);
$vlr_cancelado               = addslashes($_POST['vlr_cancelado']);
$cod_clientes                = intval($_POST['cod_clientes']);
$tipo_pago                   = intval($_POST['tipo_pago']);
$fecha_pago                  = addslashes($_POST['fecha_pago']);
$pagina                      = $_POST['pagina'];
$total_datos                 = intval($_POST['total_datos']);
$fecha_anyo                  = addslashes($_POST['fecha_anyo']);
$bolsa                       = intval($_POST['bolsa']);
$nombre_peso                 = $vlr_cancelado;
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$obtener_informacion = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consultar_informacion = mysql_query($obtener_informacion, $conectar);
$info_emp = mysql_fetch_assoc($consultar_informacion);

$nombre_bolsa_emp            = $info_emp['nombre_bolsa'];
$precio_bolsa_emp            = $info_emp['precio_bolsa'];
$ptj_bolsa_emp               = $info_emp['ptj_bolsa'];
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$suma_temporal = "SELECT Sum(vlr_total_venta -(vlr_total_venta*($descuento_factura/100))) As total_venta, 
Sum((vlr_total_venta - (($descuento_factura/100)*vlr_total_venta))/((iva/100)+(100/100))) As subtotal_base, 
Sum(((vlr_total_venta - (($descuento_factura/100)*vlr_total_venta))/((iva/100)+(100/100)))*(iva/100)) As total_iva, 
Sum(vlr_total_venta*($descuento_factura/100)) AS total_desc, Sum(vlr_total_compra) AS vlr_total_compra FROM temporal WHERE (vendedor = '$cuenta_actual') AND (cod_base_caja = '$cod_base_caja')";
$consulta_temporal = mysql_query($suma_temporal, $conectar) or die(mysql_error());
$suma_datos = mysql_fetch_assoc($consulta_temporal);

$subtotal_base               = $suma_datos['subtotal_base'];
$total_desc                  = $suma_datos['total_desc'];
$total_iva                   = $suma_datos['total_iva'];
$total_venta_temp            = ($suma_datos['total_venta']);
$vlr_total_compra            = ($suma_datos['vlr_total_compra']);
$monto_deuda                 =  $total_venta_temp;

$flete                       = '0';
$vlr_vuelto                  = $vlr_cancelado - $total_venta_temp;
$estado                      = 'cerrado';
$venta_total                 = $total_venta_temp;
//$subtotal                  = '0';
$vendedor                    = $cuenta_actual;
$ip                          = $_SERVER['REMOTE_ADDR'];
$devoluciones                = 0;
$dato_fecha                  = explode('/', $fecha_anyo);
$dia                         = $dato_fecha[0];
$mes                         = $dato_fecha[1];
$anyo                        = $dato_fecha[2];
$fecha_dia                   = $anyo.'/'.$mes.'/'.$dia;
$fecha                       = strtotime($fecha_dia);
$fecha_mes                   = $mes.'/'.$anyo;
$fecha_hora                  = date("H:i:s");

$fecha_actual_hoy            = date("Y/m/d");
$fechas_agotado              = date("d/m/Y");
$fechas_agotado_seg          = strtotime($fecha_actual_hoy);
$cod_original                = $fecha_actual_hoy.'-'.$fecha_hora;

$obtener_cliente = "SELECT nombres, apellidos FROM clientes WHERE cod_clientes = '$cod_clientes'";
$resultado_cliente = mysql_query($obtener_cliente, $conectar) or die(mysql_error());
$matriz_cliente = mysql_fetch_assoc($resultado_cliente);

$nombre_cliente              = $matriz_cliente['nombres'].' '.$matriz_cliente['apellidos'];

$sql_cuentas_cobrar = "SELECT cod_cuentas_cobrar FROM cuentas_cobrar WHERE cod_clientes = '$cod_clientes'";
$modificar_cuentas_cobrar = mysql_query($sql_cuentas_cobrar, $conectar) or die(mysql_error());
$total_encontrado_cobrar = mysql_num_rows($modificar_cuentas_cobrar);

$sql_info_imp_factura = "SELECT cod_factura FROM info_impuesto_facturas WHERE cod_factura = '$cod_factura'";
$modificar_info_imp_factura = mysql_query($sql_info_imp_factura, $conectar) or die(mysql_error());
$total_encontrado_info_imp_factura = mysql_num_rows($modificar_info_imp_factura);

$sql_admin = "SELECT cod_base_caja, cuenta FROM administrador WHERE cuenta = '$cuenta_actual'";
$consulta_admin = mysql_query($sql_admin, $conectar) or die(mysql_error());
$matriz_admin = mysql_fetch_assoc($consulta_admin);

$cod_base_caja                = $matriz_admin['cod_base_caja'];

//-------------------------------------- LLAVE DE ENTRADA DEL CONDICIONAL VENDER POR CONTADO ------------------------------------------//
if (isset($_POST['vlr_cancelado']) && ($vlr_cancelado >= $venta_total) && ($vlr_cancelado <= 99999999) && ($venta_total >= $vlr_total_compra) && ($tipo_pago == '1') && $requerir_funcion->bloquear($_POST['verificador'])) {

for ($i=0; $i < $total_datos; $i++) {

$cod_temporal                 = $_POST['cod_temporal'][$i];

$sql_mconsulta = "SELECT * FROM temporal WHERE (cod_temporal = '$cod_temporal') ORDER BY cod_temporal DESC";
$mconsulta = mysql_query($sql_mconsulta, $conectar) or die(mysql_error());
$datos_temp = mysql_fetch_assoc($mconsulta);

$cod_productos             = $datos_temp['cod_productos'];

$sqlr_consulta = "SELECT unidades_faltantes, unidades_vendidas, precio_venta, precio_costo, precio_costo, 
precio_costo, cod_proveedores, cod_marcas, detalles FROM productos WHERE cod_productos_var = '$cod_productos'";
$modificar_consulta = mysql_query($sqlr_consulta, $conectar) or die(mysql_error());
$datos_prod = mysql_fetch_assoc($modificar_consulta);
//-----------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------TIPO DE VENTA PUEDES SER MENUDIADO O POR UNIDADES----------------------------------------------------//
$tipo                            = $datos_temp['detalles'];
$detalles                        = $datos_temp['detalles'];
$unidades_vendidas               = $datos_temp['unidades_vendidas'];
$precio_venta                    = $datos_temp['precio_venta'];
//-----------------------------------------------------------------------------------------------------------------------------------------//
//-----------------------------------------------------------------------------------------------------------------------------------------//
$nombre_productos                = $datos_temp['nombre_productos'];
$unidades_faltantes              = $datos_prod['unidades_faltantes'] - $unidades_vendidas;
$unidades_vendidas_inv           = $datos_prod['unidades_vendidas'] + $datos_temp['unidades_vendidas'];
$cod_proveedores                 = $datos_prod['cod_proveedores'];
$cod_marcas                      = $datos_prod['cod_marcas'];
//------------------------------------------------------------------------------------------------------------------------------//
//----------------- CAMPO NUEVO PARA CONTROLAR LA CANTIDAD QUE QUEDA EN EL INVENTARIO DESPUES DE LA VENTA -----------------------//
$unidades_faltantes_inv          = $datos_prod['unidades_faltantes'];
//------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------//
$und_vend_orig                   = $datos_temp['unidades_vendidas'];
$precio_compra                   = $datos_temp['precio_compra'];
$precio_costo                    = $datos_temp['precio_costo'];
$vlr_total_venta                 = $datos_temp['vlr_total_venta'];
$vlr_total_compra_temp           = $datos_temp['vlr_total_compra'];
$iva                             = $datos_temp['iva'];
$descuento_ptj                   = $descuento_factura;
$descuento                       = $datos_temp['descuento'];
$precio_compra_con_descuento     = $datos_temp['precio_compra_con_descuento'];
$vendedor                        = $datos_temp['vendedor'];
$porcentaje_vendedor             = $datos_temp['porcentaje_vendedor'];
//$detalles                        = $datos_prod['detalles'];
$nombre_lineas                   = $datos_temp['nombre_lineas'];
$nombre_ccosto                   = $datos_temp['nombre_ccosto'];
$cod_dependencia                 = $datos_temp['cod_dependencia'];
//----------------------------- INSERTAR PRODUCTOS A LAS VENTAS -----------------------------//
$agregar_reg_ventas = "INSERT INTO ventas (cod_productos, cod_factura, cod_clientes, cod_proveedores, cod_marcas, cod_base_caja, nombre_productos, unidades_vendidas, 
und_vend_orig, devoluciones, precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, iva, descuento_ptj, descuento, 
precio_compra_con_descuento, vendedor, ip, fecha, fecha_mes, fecha_anyo, anyo, fecha_hora, porcentaje_vendedor, detalles, tipo_pago, fecha_orig, 
nombre_lineas, nombre_ccosto, unidades_faltantes_inv, cod_dependencia, nombre_peso)
VALUES ('$cod_productos', '$cod_factura', '$cod_clientes', '$cod_proveedores', '$cod_marcas', '$cod_base_caja', '$nombre_productos', '$unidades_vendidas', '$und_vend_orig', 
'$devoluciones', '$precio_compra', '$precio_costo', '$precio_venta', '$vlr_total_venta', '$vlr_total_compra_temp', '$iva', '$descuento_ptj', '$descuento', 
'$precio_compra_con_descuento', '$vendedor', '$ip', '$fecha', '$fecha_mes', '$fecha_anyo', '$anyo', '$fecha_hora', '$porcentaje_vendedor', 
'$detalles', '$tipo_pago', '$fecha_anyo', '$nombre_lineas', '$nombre_ccosto', '$unidades_faltantes_inv', '$cod_dependencia', '$nombre_peso')";
$resultado_ventas = mysql_query($agregar_reg_ventas, $conectar) or die(mysql_error());
//---------------------------------------------------------------------------------------------------------------------------------------//
 	 	 	 	
$actualiza_temporal_copia_factura = sprintf("UPDATE temporal_copia SET cod_factura = '$cod_factura', ip = '$ip', fecha = '$fecha', 
fecha_mes = '$fecha_mes', fecha_anyo = '$fecha_anyo', fecha_hora = '$fecha_hora' WHERE cod_temporal = '$cod_temporal'");
$resultado_temporal_copia_factura = mysql_query($actualiza_temporal_copia_factura, $conectar) or die(mysql_error());
//----------------------------- BORRAR DE TEMPORAL PRODUCTOS VENDIDOS -----------------------------//
$borrar_sql = sprintf("DELETE FROM temporal WHERE cod_temporal = '$cod_temporal'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());
//----------------------------- ACTUALIZAR INVENTARIO DE PRODUCTOS OPERACIONES -----------------------------//
if ($unidades_faltantes <= '0') {
$actualiza_productos = sprintf("UPDATE productos SET unidades_faltantes = '$unidades_faltantes',
fechas_agotado_seg = '$fechas_agotado_seg', fechas_agotado = '$fechas_agotado', cod_original = '$cod_original' WHERE cod_productos_var = '$cod_productos'");
$resultado_actualiza_productos = mysql_query($actualiza_productos, $conectar) or die(mysql_error());
//----------------------------- ALERTA UNIDADES AGOTADAS -----------------------------//
} 
else {
$actualiza_productos = sprintf("UPDATE productos SET unidades_faltantes = '$unidades_faltantes', cod_original = '$cod_original' WHERE cod_productos_var = '$cod_productos'");
$resultado_actualiza_productos = mysql_query($actualiza_productos, $conectar) or die(mysql_error());
}
//-------------------------------------------------------------------------------------------------------------------------------------//
//----------------------------- INICIO ACTUALIZAR KARDEX VENTA - COMPRA E INVENTARIO -----------------------------//
//-------------------------------------------------------------------------------------------------------------------------------------//
$fecha_dmy           = $fecha_anyo;
$fecha_seg_ymd       = $fecha;
$fecha_time          = time();

$sql_kardex = "SELECT cod_productos, fecha_mes FROM kardex_venta_compra_invent WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex = mysql_query($sql_kardex, $conectar) or die(mysql_error());
$datos_kardex = mysql_fetch_assoc($consulta_kardex);
$total_k = mysql_num_rows($consulta_kardex);
//-------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------INSERTAR NUEVO KARDEX PARA PRODUCTO-----------------------------------------------//
if ($total_k == 0) {
//--------------------------------------------------------------CALCULO KARDEX VENTAS------------------------------------------------------------------------//
//--------------------------------------------------------------CALCULO KARDEX VENTAS------------------------------------------------------------------------//
$sql_kardex_ventas = "SELECT SUM(unidades_vendidas) AS und_venta FROM ventas WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_ventas = mysql_query($sql_kardex_ventas, $conectar) or die(mysql_error());
$datos_kardex_ventas = mysql_fetch_assoc($consulta_kardex_ventas);
//--------------------------------------------------------------CALCULO KARDEX COMPRAS------------------------------------------------------------------------//
$sql_kardex_compras = "SELECT SUM(unidades_total) AS und_compra FROM facturas_cargadas_inv WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_compras = mysql_query($sql_kardex_compras, $conectar) or die(mysql_error());
$datos_kardex_compras = mysql_fetch_assoc($consulta_kardex_compras);
//--------------------------------------------------------------CALCULO KARDEX TRANSFERENCIAS------------------------------------------------------------------------//
$sql_kardex_transf = "SELECT SUM(unidades_total) AS und_transf FROM transferencias WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_transf = mysql_query($sql_kardex_transf, $conectar) or die(mysql_error());
$datos_kardex_transf = mysql_fetch_assoc($consulta_kardex_transf);
//--------------------------------------------------------------CALCULO KARDEX TRANSFERENCIAS ENTRDAS------------------------------------------------------------------------//
$sql_kardex_transf_ent = "SELECT SUM(unidades_total) AS und_transf_ent FROM transferencias_entrada WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_transf_ent = mysql_query($sql_kardex_transf_ent, $conectar) or die(mysql_error());
$datos_kardex_transf_ent = mysql_fetch_assoc($consulta_kardex_transf_ent);
//---------------------------------------------------------------CALCULO KARDEX INVENTARIO-----------------------------------------------------------------------//
$sql_kardex_invent = "SELECT unidades_faltantes FROM productos WHERE cod_productos_var = '$cod_productos'";
$consulta_kardex_invent = mysql_query($sql_kardex_invent, $conectar) or die(mysql_error());
$datos_kardex_invent = mysql_fetch_assoc($consulta_kardex_invent);
//---------------------------------------------------------------TOTALES CALCULOS KARDEX-----------------------------------------------------------------------//
$und_venta           = $datos_kardex_ventas['und_venta'];
$und_compra          = $datos_kardex_compras['und_compra'];
$und_transf          = $datos_kardex_transf['und_transf'];
$und_transf_ent      = $datos_kardex_transf_ent['und_transf_ent'];
$und_invent          = $datos_kardex_invent['unidades_faltantes'];

$agregar_kardex = "INSERT INTO kardex_venta_compra_invent (cod_productos, nombre_productos, und_venta, und_compra, und_transf, 
und_transf_ent, und_invent, fecha_mes, fecha_dmy, anyo, fecha_seg_ymd, fecha_time)
VALUES ('$cod_productos', '$nombre_productos', '$und_venta', '$und_compra', '$und_transf', 
'$und_transf_ent', '$und_invent', '$fecha_mes', '$fecha_dmy', '$anyo', '$fecha_seg_ymd', '$fecha_time')";
$resultado_agregar_kardex = mysql_query($agregar_kardex, $conectar) or die(mysql_error());
//--------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------ACTUALIZAR REGISTRO SI YA EXISTE-----------------------------------------------//
} else {
//--------------------------------------------------------------CALCULO KARDEX VENTAS------------------------------------------------------------------------//
$sql_kardex_ventas = "SELECT SUM(unidades_vendidas) AS und_venta FROM ventas WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_ventas = mysql_query($sql_kardex_ventas, $conectar) or die(mysql_error());
$datos_kardex_ventas = mysql_fetch_assoc($consulta_kardex_ventas);
//--------------------------------------------------------------CALCULO KARDEX COMPRAS------------------------------------------------------------------------//
$sql_kardex_compras = "SELECT SUM(unidades_total) AS und_compra FROM facturas_cargadas_inv WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_compras = mysql_query($sql_kardex_compras, $conectar) or die(mysql_error());
$datos_kardex_compras = mysql_fetch_assoc($consulta_kardex_compras);
//--------------------------------------------------------------CALCULO KARDEX TRANSFERENCIAS------------------------------------------------------------------------//
$sql_kardex_transf = "SELECT SUM(unidades_total) AS und_transf FROM transferencias WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_transf = mysql_query($sql_kardex_transf, $conectar) or die(mysql_error());
$datos_kardex_transf = mysql_fetch_assoc($consulta_kardex_transf);
//--------------------------------------------------------------CALCULO KARDEX TRANSFERENCIAS ENTRDAS------------------------------------------------------------------------//
$sql_kardex_transf_ent = "SELECT SUM(unidades_total) AS und_transf_ent FROM transferencias_entrada WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_transf_ent = mysql_query($sql_kardex_transf_ent, $conectar) or die(mysql_error());
$datos_kardex_transf_ent = mysql_fetch_assoc($consulta_kardex_transf_ent);
//---------------------------------------------------------------CALCULO KARDEX INVENTARIO-----------------------------------------------------------------------//
$sql_kardex_invent = "SELECT unidades_faltantes FROM productos WHERE cod_productos_var = '$cod_productos'";
$consulta_kardex_invent = mysql_query($sql_kardex_invent, $conectar) or die(mysql_error());
$datos_kardex_invent = mysql_fetch_assoc($consulta_kardex_invent);
//---------------------------------------------------------------TOTALES CALCULOS KARDEX-----------------------------------------------------------------------//
$und_venta           = $datos_kardex_ventas['und_venta'];
$und_compra          = $datos_kardex_compras['und_compra'];
$und_transf          = $datos_kardex_transf['und_transf'];
$und_transf_ent      = $datos_kardex_transf_ent['und_transf_ent'];
$und_invent          = $datos_kardex_invent['unidades_faltantes'];
//---------------------------------------------------------------ACTUALIZACION REGISTRO KARDEX-----------------------------------------------------------------------//
$actualizar_kardex = sprintf("UPDATE kardex_venta_compra_invent SET und_venta = '$und_venta', und_compra = '$und_compra', und_transf = '$und_transf', 
und_transf_ent = '$und_transf_ent', und_invent = '$und_invent', fecha_dmy = '$fecha_dmy', anyo = '$anyo', fecha_seg_ymd = '$fecha_seg_ymd', fecha_time = '$fecha_time' 
WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'");
$resultado_actualiza_kardex = mysql_query($actualizar_kardex, $conectar) or die(mysql_error());
}
//require_once("../admin/kardex_venta_compra_invent_actualizar_valor.php"); 
//--------------------------------------------FIN DEL KARDEX-----------------------------------------------------------//
//----------------------------------------------------------------------- -----------------------------------------------------------------------------------------------//
} //--------------------------------------------FIN CICLO FOR QUE RECORRE LOS DATOS------------------------------- -----------------------------//
//----------------------------- INFO INPUESTO FACTURAS OPERACIONES -----------------------------//
if ($total_encontrado_info_imp_factura == 0) {
$agregar_regis = sprintf("UPDATE info_impuesto_facturas SET vlr_cancelado = '$vlr_cancelado', vlr_vuelto = '$vlr_vuelto', cod_clientes = '$cod_clientes', cod_factura = '$cod_factura', 
descuento = '$descuento_factura', estado = '$estado', vendedor = '$cuenta_actual', fecha_dia = '$fecha', fecha_anyo = '$fecha_anyo', 
fecha_mes = '$fecha_mes', fecha_hora = '$fecha_hora' WHERE cod_info_impuesto_facturas = '$cod_info_impuesto_facturas'");
$resultado_regis = mysql_query($agregar_regis, $conectar) or die(mysql_error());
} else {
$agregar_regis = sprintf("UPDATE info_impuesto_facturas SET vlr_cancelado = '$vlr_cancelado', vlr_vuelto = '$vlr_vuelto', cod_clientes = '$cod_clientes', vendedor = '$cuenta_actual', fecha_dia = '$fecha', 
fecha_anyo = '$fecha_anyo', fecha_mes = '$fecha_mes', fecha_hora = '$fecha_hora', estado = '$estado', descuento = '$descuento_factura' 
WHERE cod_info_impuesto_facturas = '$cod_info_impuesto_facturas'");
$resultado_regis = mysql_query($agregar_regis, $conectar) or die(mysql_error());
}
//-------------------------------------------------------------------------------------------------------------//
if ($bolsa <> '0') {
$agregar_reg_venta_bolsa = "INSERT INTO ventas (cod_productos, cod_factura, cod_clientes, cod_base_caja, nombre_productos, unidades_vendidas, 
und_vend_orig, precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, iva, descuento_ptj, descuento, 
precio_compra_con_descuento, vendedor, ip, fecha, fecha_mes, fecha_anyo, anyo, fecha_hora, detalles, tipo_pago, fecha_orig, nombre_ccosto, nombre_peso)
VALUES ('000', '$cod_factura', '$cod_clientes', '$cod_base_caja', '$nombre_bolsa_emp', '1', '1', 
'$precio_bolsa_emp', '$precio_bolsa_emp', '$precio_bolsa_emp', '$precio_bolsa_emp', '$precio_bolsa_emp', 
'$ptj_bolsa_emp', '$ptj_bolsa_emp', '$ptj_bolsa_emp', '$precio_bolsa_emp', '$vendedor', '$ip', '$fecha', '$fecha_mes', 
'$fecha_anyo', '$anyo', '$fecha_hora', 'UND', '$tipo_pago', '$fecha_anyo', '$nombre_ccosto', '$nombre_peso')";
$resultado_venta_bolsa = mysql_query($agregar_reg_venta_bolsa, $conectar) or die(mysql_error());
} else { }
//----------------------------- BORRAR DE TEMPORAL PRODUCTOS VENDIDOS -----------------------------//
$borrar_sql = sprintf("DELETE FROM temporal WHERE (vendedor = '$cuenta_actual') AND (cod_base_caja = '$cod_base_caja')");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());
//----------------------------- ACTUALIZAR LA CAJA -----------------------------//
require_once("../admin/caja_actualizar_valor.php"); 
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0; ../admin/venta_productos_opcion_imprimir.php?cod_factura=<?php echo $cod_factura ?>&pagina=<?php echo $pagina ?>">
<?php
} 
//-------------------------------------- LLAVE DE CIERRE DEL CONDICIONAL VENDER POR CONTADO ------------------------------------------//
//-------------------------------------- LLAVE DE ENTRADA DEL CONDICIONAL VENDER POR CREDITO ------------------------------------------//
elseif (($_POST['vlr_cancelado'] =='0') && ($tipo_pago == '2') && $requerir_funcion->bloquear($_POST['verificador'])) {
for ($i=0; $i < $total_datos; $i++) { 

$cod_temporal             = $_POST['cod_temporal'][$i];

$sql_mconsulta = "SELECT cod_productos, detalles, unidades_vendidas, precio_venta, nombre_productos, precio_compra, 
precio_costo, vlr_total_venta, vlr_total_compra, iva, descuento, precio_compra_con_descuento, vendedor, porcentaje_vendedor, 
nombre_lineas, nombre_ccosto, cod_base_caja 
FROM temporal WHERE (cod_temporal = '$cod_temporal') ORDER BY cod_temporal DESC";
$mconsulta = mysql_query($sql_mconsulta, $conectar) or die(mysql_error());
$datos_temp = mysql_fetch_assoc($mconsulta);

$cod_productos            = $datos_temp['cod_productos'];

$sqlr_consulta = "SELECT unidades_faltantes, unidades_vendidas, precio_venta, precio_costo, precio_costo, 
precio_costo, cod_proveedores, cod_marcas, detalles FROM productos WHERE cod_productos_var = '$cod_productos'";
$modificar_consulta = mysql_query($sqlr_consulta, $conectar) or die(mysql_error());
$datos_prod = mysql_fetch_assoc($modificar_consulta);
//-----------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------TIPO DE VENTA PUEDES SER MENUDIADO O POR UNIDADES----------------------------------------------------//
$tipo                            = $datos_temp['detalles'];
$detalles                        = $datos_temp['detalles'];
$unidades_vendidas               = $datos_temp['unidades_vendidas'];
$precio_venta                    = $datos_temp['precio_venta'];
//-----------------------------------------------------------------------------------------------------------------------------------------//
//-----------------------------------------------------------------------------------------------------------------------------------------//
$unidades_faltantes              = $datos_prod['unidades_faltantes'] - $unidades_vendidas;
$unidades_vendidas_inv           = $datos_prod['unidades_vendidas'] + $datos_temp['unidades_vendidas'];
$cod_proveedores                 = $datos_prod['cod_proveedores'];
$cod_marcas                      = $datos_prod['cod_marcas'];
//------------------------------------------------------------------------------------------------------------------------------//
//----------------- CAMPO NUEVO PARA CONTROLAR LA CANTIDAD QUE QUEDA EN EL INVENTARIO DESPUES DE LA VENTA -----------------------//
$unidades_faltantes_inv          = $datos_prod['unidades_faltantes'];
//------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------//
$nombre_productos                = $datos_temp['nombre_productos'];
$und_vend_orig                   = $datos_temp['unidades_vendidas'];
$precio_compra                   = $datos_temp['precio_compra'];
$precio_costo                    = $datos_temp['precio_costo'];
$vlr_total_venta                 = $datos_temp['vlr_total_venta'];
$vlr_total_compra_temp           = $datos_temp['vlr_total_compra'];
$iva                             = $datos_temp['iva'];
$descuento_ptj                   = $descuento_factura;
$descuento                       = $datos_temp['descuento'];
$precio_compra_con_descuento     = $datos_temp['precio_compra_con_descuento'];
$vendedor                        = $datos_temp['vendedor'];
$porcentaje_vendedor             = $datos_temp['porcentaje_vendedor'];
//$detalles                      = $datos_prod['detalles'];
$nombre_lineas                   = $datos_temp['nombre_lineas'];
$nombre_ccosto                   = $datos_temp['nombre_ccosto'];
$cod_dependencia                 = $datos_temp['cod_dependencia'];
$cod_uniq_credito                = time() + $i;
//----------------------------- INSERTAR PRODUCTOS A LAS VENTAS -----------------------------//
$agregar_reg_ventas = "INSERT INTO ventas (cod_productos, cod_factura, cod_clientes, cod_proveedores, cod_marcas, cod_base_caja, nombre_productos, unidades_vendidas, 
und_vend_orig, devoluciones, precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, iva, descuento_ptj, descuento, 
precio_compra_con_descuento, vendedor, ip, fecha, fecha_mes, fecha_anyo, anyo, fecha_hora, porcentaje_vendedor, detalles, tipo_pago, fecha_orig, 
nombre_lineas, nombre_ccosto, cod_uniq_credito, unidades_faltantes_inv, cod_dependencia, nombre_peso)
VALUES ('$cod_productos', '$cod_factura', '$cod_clientes', '$cod_proveedores', '$cod_marcas', '$cod_base_caja', '$nombre_productos', '$unidades_vendidas', '$und_vend_orig', 
'$devoluciones', '$precio_compra', '$precio_costo', '$precio_venta', '$vlr_total_venta', '$vlr_total_compra_temp', '$iva', '$descuento_ptj', '$descuento', 
'$precio_compra_con_descuento', '$vendedor', '$ip', '$fecha', '$fecha_mes', '$fecha_anyo', '$anyo', '$fecha_hora', '$porcentaje_vendedor', 
'$detalles', '$tipo_pago', '$fecha_anyo', '$nombre_lineas', '$nombre_ccosto', '$cod_uniq_credito', '$unidades_faltantes_inv', '$cod_dependencia', '$nombre_peso')";
$resultado_ventas = mysql_query($agregar_reg_ventas, $conectar) or die(mysql_error());
//---------------------------------------------------------------------------------------------------------------------------------------//
$actualiza_temporal_copia_factura = sprintf("UPDATE temporal_copia SET cod_factura = '$cod_factura' WHERE cod_temporal = '$cod_temporal'");
$resultado_temporal_copia_factura = mysql_query($actualiza_temporal_copia_factura, $conectar) or die(mysql_error());
//----------------------------- BORRAR DE TEMPORAL PRODUCTOS VENDIDOS -----------------------------//
$borrar_sql = sprintf("DELETE FROM temporal WHERE cod_temporal = '$cod_temporal'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());
//-------------------------------------------------------------------------------------------------------------------------------------//
if ($unidades_faltantes <= '0') {
$actualiza_productos = sprintf("UPDATE productos SET unidades_faltantes = '$unidades_faltantes', fechas_agotado_seg = '$fechas_agotado_seg',
fechas_agotado = '$fechas_agotado', cod_original = '$cod_original' WHERE cod_productos_var = '$cod_productos'");
$resultado_actualiza_productos = mysql_query($actualiza_productos, $conectar) or die(mysql_error());
} else {
$actualiza_productos = sprintf("UPDATE productos SET unidades_faltantes = '$unidades_faltantes', cod_original = '$cod_original' WHERE cod_productos_var = '$cod_productos'");
$resultado_actualiza_productos = mysql_query($actualiza_productos, $conectar) or die(mysql_error());
}
//-------------------------------------------------------------------------------------------------------------------------------------//
//----------------------------- INICIO ACTUALIZAR KARDEX VENTA - COMPRA E INVENTARIO -----------------------------//
//-------------------------------------------------------------------------------------------------------------------------------------//
$fecha_dmy               = $fecha_anyo;
$fecha_seg_ymd           = $fecha;
$fecha_time              = time();

$sql_kardex = "SELECT cod_productos, fecha_mes FROM kardex_venta_compra_invent WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex = mysql_query($sql_kardex, $conectar) or die(mysql_error());
$datos_kardex = mysql_fetch_assoc($consulta_kardex);
$total_k = mysql_num_rows($consulta_kardex);
//-------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------INSERTAR NUEVO KARDEX PARA PRODUCTO-----------------------------------------------//
if ($total_k == 0) {
//--------------------------------------------------------------CALCULO KARDEX VENTAS------------------------------------------------------------------------//
//--------------------------------------------------------------CALCULO KARDEX VENTAS------------------------------------------------------------------------//
$sql_kardex_ventas = "SELECT SUM(unidades_vendidas) AS und_venta FROM ventas WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_ventas = mysql_query($sql_kardex_ventas, $conectar) or die(mysql_error());
$datos_kardex_ventas = mysql_fetch_assoc($consulta_kardex_ventas);
//--------------------------------------------------------------CALCULO KARDEX COMPRAS------------------------------------------------------------------------//
$sql_kardex_compras = "SELECT SUM(unidades_total) AS und_compra FROM facturas_cargadas_inv WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_compras = mysql_query($sql_kardex_compras, $conectar) or die(mysql_error());
$datos_kardex_compras = mysql_fetch_assoc($consulta_kardex_compras);
//--------------------------------------------------------------CALCULO KARDEX TRANSFERENCIAS------------------------------------------------------------------------//
$sql_kardex_transf = "SELECT SUM(unidades_total) AS und_transf FROM transferencias WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_transf = mysql_query($sql_kardex_transf, $conectar) or die(mysql_error());
$datos_kardex_transf = mysql_fetch_assoc($consulta_kardex_transf);
//--------------------------------------------------------------CALCULO KARDEX TRANSFERENCIAS ENTRDAS------------------------------------------------------------------------//
$sql_kardex_transf_ent = "SELECT SUM(unidades_total) AS und_transf_ent FROM transferencias_entrada WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_transf_ent = mysql_query($sql_kardex_transf_ent, $conectar) or die(mysql_error());
$datos_kardex_transf_ent = mysql_fetch_assoc($consulta_kardex_transf_ent);
//---------------------------------------------------------------CALCULO KARDEX INVENTARIO-----------------------------------------------------------------------//
$sql_kardex_invent = "SELECT unidades_faltantes FROM productos WHERE cod_productos_var = '$cod_productos'";
$consulta_kardex_invent = mysql_query($sql_kardex_invent, $conectar) or die(mysql_error());
$datos_kardex_invent = mysql_fetch_assoc($consulta_kardex_invent);
//---------------------------------------------------------------TOTALES CALCULOS KARDEX-----------------------------------------------------------------------//
$und_venta               = $datos_kardex_ventas['und_venta'];
$und_compra              = $datos_kardex_compras['und_compra'];
$und_transf              = $datos_kardex_transf['und_transf'];
$und_transf_ent          = $datos_kardex_transf_ent['und_transf_ent'];
$und_invent              = $datos_kardex_invent['unidades_faltantes'];

$agregar_kardex = "INSERT INTO kardex_venta_compra_invent (cod_productos, nombre_productos, und_venta, und_compra, und_transf, und_transf_ent, und_invent, fecha_mes, fecha_dmy, anyo, fecha_seg_ymd, fecha_time)
VALUES ('$cod_productos', '$nombre_productos', '$und_venta', '$und_compra', '$und_transf', '$und_transf_ent', '$und_invent', '$fecha_mes', '$fecha_dmy', '$anyo', '$fecha_seg_ymd', '$fecha_time')";
$resultado_agregar_kardex = mysql_query($agregar_kardex, $conectar) or die(mysql_error());
//--------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------ACTUALIZAR REGISTRO SI YA EXISTE-----------------------------------------------//
} else {
//--------------------------------------------------------------CALCULO KARDEX VENTAS------------------------------------------------------------------------//
$sql_kardex_ventas = "SELECT SUM(unidades_vendidas) AS und_venta FROM ventas WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_ventas = mysql_query($sql_kardex_ventas, $conectar) or die(mysql_error());
$datos_kardex_ventas = mysql_fetch_assoc($consulta_kardex_ventas);
//--------------------------------------------------------------CALCULO KARDEX COMPRAS------------------------------------------------------------------------//
$sql_kardex_compras = "SELECT SUM(unidades_total) AS und_compra FROM facturas_cargadas_inv WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_compras = mysql_query($sql_kardex_compras, $conectar) or die(mysql_error());
$datos_kardex_compras = mysql_fetch_assoc($consulta_kardex_compras);
//--------------------------------------------------------------CALCULO KARDEX TRANSFERENCIAS------------------------------------------------------------------------//
$sql_kardex_transf = "SELECT SUM(unidades_total) AS und_transf FROM transferencias WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_transf = mysql_query($sql_kardex_transf, $conectar) or die(mysql_error());
$datos_kardex_transf = mysql_fetch_assoc($consulta_kardex_transf);
//--------------------------------------------------------------CALCULO KARDEX TRANSFERENCIAS ENTRDAS------------------------------------------------------------------------//
$sql_kardex_transf_ent = "SELECT SUM(unidades_total) AS und_transf_ent FROM transferencias_entrada WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_transf_ent = mysql_query($sql_kardex_transf_ent, $conectar) or die(mysql_error());
$datos_kardex_transf_ent = mysql_fetch_assoc($consulta_kardex_transf_ent);
//---------------------------------------------------------------CALCULO KARDEX INVENTARIO-----------------------------------------------------------------------//
$sql_kardex_invent = "SELECT unidades_faltantes FROM productos WHERE cod_productos_var = '$cod_productos'";
$consulta_kardex_invent = mysql_query($sql_kardex_invent, $conectar) or die(mysql_error());
$datos_kardex_invent = mysql_fetch_assoc($consulta_kardex_invent);
//---------------------------------------------------------------TOTALES CALCULOS KARDEX-----------------------------------------------------------------------//
$und_venta               = $datos_kardex_ventas['und_venta'];
$und_compra              = $datos_kardex_compras['und_compra'];
$und_transf              = $datos_kardex_transf['und_transf'];
$und_transf_ent          = $datos_kardex_transf_ent['und_transf_ent'];
$und_invent              = $datos_kardex_invent['unidades_faltantes'];
//---------------------------------------------------------------ACTUALIZACION REGISTRO KARDEX-----------------------------------------------------------------------//
$actualizar_kardex = sprintf("UPDATE kardex_venta_compra_invent SET und_venta = '$und_venta', und_compra = '$und_compra', und_transf = '$und_transf', 
und_transf_ent = '$und_transf_ent', und_invent = '$und_invent', fecha_dmy = '$fecha_dmy', anyo = '$anyo', fecha_seg_ymd = '$fecha_seg_ymd', fecha_time = '$fecha_time' 
WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'");
$resultado_actualiza_kardex = mysql_query($actualizar_kardex, $conectar) or die(mysql_error());
}
//--------------------------------------------FIN DEL KARDEX-----------------------------------------------------------//
}
//--------------------------------------------FIN CICLO FOR QUE RECORRE LOS DATOS---------------------------------------//
$sql_prod_fiados = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As vlr_total_venta FROM ventas WHERE cod_factura = '$cod_factura'";
$modificar_prod_fiados = mysql_query($sql_prod_fiados, $conectar) or die(mysql_error());
$datos_fiad = mysql_fetch_assoc($modificar_prod_fiados);

$sum_abonos_valor = "SELECT Sum(abonado) AS abonado FROM cuentas_cobrar_abonos WHERE cod_factura = '$cod_factura'";
$consulta_sum_abonos = mysql_query($sum_abonos_valor, $conectar) or die(mysql_error());
$sum_abonos = mysql_fetch_assoc($consulta_sum_abonos);

$monto_deuda             = $datos_fiad['vlr_total_venta'];
$abonado                 = $sum_abonos['abonado'];
$subtotal                = $monto_deuda - $abonado;

$separador_fecha         = explode('/', $fecha_pago);
$dias                    = $separador_fecha[0];
$meses                   = $separador_fecha[1];
$anyos                   = $separador_fecha[2];
$fecha_invert            = $anyos.'/'.$meses.'/'.$dias;
$fecha_seg               = strtotime($fecha_invert);
//----------------------------- CUENTAS POR COBRAR OPERACIONES -----------------------------//
$sql_verif_cuenta_cobrar = "SELECT cod_factura FROM cuentas_cobrar WHERE cod_factura = '$cod_factura'";
$consulta_verif_cuenta_cobrar = mysql_query($sql_verif_cuenta_cobrar, $conectar) or die(mysql_error());
$total_verif_cuenta_cobrar = mysql_num_rows($consulta_verif_cuenta_cobrar);

$agregar_reg_cuentas_cobrar = "INSERT INTO cuentas_cobrar (cod_clientes, cod_factura, monto_deuda, subtotal, descuento, vendedor, fecha_pago, 
fecha, fecha_invert) VALUES ('$cod_clientes', '$cod_factura', '$monto_deuda', '$subtotal', '$descuento_factura', '$vendedor', 
'$fecha_pago', '$fecha_anyo', '$fecha_invert')";
$resultado_cuentas_cobrar = mysql_query($agregar_reg_cuentas_cobrar, $conectar) or die(mysql_error());
//----------------------------- INFO INPUESTO FACTURAS OPERACIONES -----------------------------//
if ($total_encontrado_info_imp_factura == 0) {
$agregar_regis = sprintf("UPDATE info_impuesto_facturas SET vlr_cancelado = '$vlr_cancelado', cod_clientes = '$cod_clientes', cod_factura = '$cod_factura', 
descuento = '$descuento_factura', estado = '$estado', vendedor = '$cuenta_actual', fecha_dia = '$fecha', fecha_anyo = '$fecha_anyo', 
fecha_mes = '$fecha_mes', fecha_hora = '$fecha_hora' WHERE cod_info_impuesto_facturas = '$cod_info_impuesto_facturas'");
$resultado_regis = mysql_query($agregar_regis, $conectar) or die(mysql_error());
} else {
$agregar_regis = sprintf("UPDATE info_impuesto_facturas SET vlr_cancelado = '$vlr_cancelado', cod_clientes = '$cod_clientes', vendedor = '$cuenta_actual', fecha_dia = '$fecha', fecha_anyo = '$fecha_anyo', 
fecha_mes = '$fecha_mes', fecha_hora = '$fecha_hora', estado = '$estado', descuento = '$descuento_factura' 
WHERE cod_info_impuesto_facturas = '$cod_info_impuesto_facturas'");
$resultado_regis = mysql_query($agregar_regis, $conectar) or die(mysql_error());
}
//-----------------------------------------------------------------------------------------------------------------------//
if ($bolsa <> '0') {
$agregar_reg_venta_bolsa = "INSERT INTO ventas (cod_productos, cod_factura, cod_clientes, cod_base_caja, nombre_productos, unidades_vendidas, 
und_vend_orig, precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, iva, descuento_ptj, descuento, 
precio_compra_con_descuento, vendedor, ip, fecha, fecha_mes, fecha_anyo, anyo, fecha_hora, detalles, tipo_pago, fecha_orig, nombre_ccosto, nombre_peso)
VALUES ('000', '$cod_factura', '$cod_clientes', '$cod_base_caja', '$nombre_bolsa_emp', '1', '1', 
'$precio_bolsa_emp', '$precio_bolsa_emp', '$precio_bolsa_emp', '$precio_bolsa_emp', '$precio_bolsa_emp', 
'$ptj_bolsa_emp', '$ptj_bolsa_emp', '$ptj_bolsa_emp', '$precio_bolsa_emp', '$vendedor', '$ip', '$fecha', '$fecha_mes', 
'$fecha_anyo', '$anyo', '$fecha_hora', 'UND', '$tipo_pago', '$fecha_anyo', '$nombre_ccosto', '$nombre_peso')";
$resultado_venta_bolsa = mysql_query($agregar_reg_venta_bolsa, $conectar) or die(mysql_error());
} else { }
//require_once("../admin/caja_actualizar_valor.php"); 
//----------------------------- LLAVE DE CIERRE DEL CONDICIONAL VENDER POR CONTADO -----------------------------//
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0; ../admin/venta_productos_opcion_imprimir.php?cod_factura=<?php echo $cod_factura ?>&pagina=<?php echo $pagina ?>">
<?php }
//----------------------------- SI EL VALOR CANCELADO ES MENOR QUE EL TOTAL MOSTRAR ERROR -----------------------------//
elseif ($venta_total > $vlr_cancelado) {
echo "<center><font color='yellow' size= '+3'><br><img src=../imagenes/advertencia.gif alt='Advertencia'><strong> 
EL VALOR RECIBIDO ES MENOR QUE EL VALOR TOTAL DE LA VENTA. </strong>
 <img src=../imagenes/advertencia.gif alt='Advertencia'></font><center><br>";
?>
<table>
<td><font color='green' size= "+2">DESCUENTO: </font></td><td><font color='green' size= "+2"><?php echo $descuento_factura.'%'; ?></td>
<tr></tr>
<td><font color='yellow' size= "+3">TOTAL VENTA: </font></td><td><font color='yellow' size= "+3"><?php echo number_format($venta_total - $descuento_factura, 0, ",", "."); ?></td>
<tr></tr>
<td><font color='yellow' size= "+3">RECIBIDO: </font></td><td><font color='yellow' size= "+3"><?php echo number_format($vlr_cancelado, 0, ",", "."); ?></td>
</table>
<br>
<form method="post" action="../admin/<?php echo $pagina;?>">
<td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<input type="image" id ="foco" src="../imagenes/regresar.png" name="listo" value="listo" />
</td>
</form>
</center>
<?php }
//----------------------------- SI EL TOTAL DE VENTA ES MENOR QUE EL TOTAL DE COMPRA MOSTRAR ERROR -----------------------------//
elseif ($venta_total < $vlr_total_compra) {
echo "<center><font color='yellow' size= '+3'><br><img src=../imagenes/advertencia.gif alt='Advertencia'><strong> 
EL TOTAL DE COMPRA ES MAYOR QUE EL VALOR TOTAL DE VENTA. </strong>
<img src=../imagenes/advertencia.gif alt='Advertencia'></font><center><br>";
?>
<table>
<td><font color='green' size= "+2">DESCUENTO: </font></td><td><font color='green' size= "+2"><?php echo $descuento_factura.'%'; ?></td>
<tr></tr>
<td><font color='yellow' size= "+3">TOTAL COMPRA: </font></td><td><font color='yellow' size= "+3"><?php echo number_format($vlr_total_compra, 0, ",", "."); ?></td>
<tr></tr>
<td><font color='yellow' size= "+3">TOTAL VENTA: </font></td><td><font color='yellow' size= "+3"><?php echo number_format($venta_total, 0, ",", "."); ?></td>
</table>
<br>
<form method="post" action="../admin/<?php echo $pagina;?>">
<td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<input type="image" id ="foco" src="../imagenes/regresar.png" name="listo" value="listo" />
</td>
</form>
</center>
<?php } 
//----------------------------- SI EL TOTAL DE VENTA ES MENOR QUE EL TOTAL DE COMPRA MOSTRAR ERROR -----------------------------//
elseif ($vlr_cancelado > 99999999) {
echo "<center><font color='yellow' size= '+3'><br><img src=../imagenes/advertencia.gif alt='Advertencia'><strong> 
EL VALOR CANCELADO ES DEMAIADO ALTO VERIFIQUE. </strong>
<img src=../imagenes/advertencia.gif alt='Advertencia'></font><center><br>";
?>
<table>
<tr>
<td><font color='yellow' size= "+3">TOTAL CANCELADO: </font></td><td><font color='yellow' size= "+3"><?php echo number_format($vlr_cancelado, 0, ",", "."); ?></td>
</tr>
</table>
<br>
<form method="post" action="../admin/<?php echo $pagina;?>">
<td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<input type="image" id ="foco" src="../imagenes/regresar.png" name="listo" value="listo" />
</td>
</form>
</center>
<?php }
else { ?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; <?php echo $pagina;?>">
<?php } ?>
</body>
</html>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>