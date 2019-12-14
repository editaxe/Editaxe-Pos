<?php
include_once('../conexiones/conexione.php'); 
include_once('../evitar_mensaje_error/error.php');
include ("../session/funciones_admin.php");
//include_once('../admin/numeros_a_letras_funcion.php');
date_default_timezone_set("America/Bogota");

if (verificar_usuario()){
//print "Bienvenido (a), ".$_SESSION['usuario'].", al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual  = addslashes($_SESSION['usuario']);
$cod_factura    = intval($_POST['cod_factura']);
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$obtener_info_mquina_impr = "SELECT nombre_maquina, nombre_impresora1 FROM administrador WHERE cuenta = '$cuenta_actual'";
$resultado_info_mquina_impr = mysql_query($obtener_info_mquina_impr, $conectar);
$info_mquina_impr = mysql_fetch_assoc($resultado_info_mquina_impr);

$nombre_maquina_usuario     = $info_mquina_impr['nombre_maquina'];
$nombre_impresora1_usuario  = $info_mquina_impr['nombre_impresora1'];


$obtener_info_fact = "SELECT * FROM info_impuesto_facturas WHERE cod_factura = '$cod_factura'";
$resultado_info_fact = mysql_query($obtener_info_fact, $conectar);
$info_fact = mysql_fetch_assoc($resultado_info_fact);

$cod_info_impuesto_facturas = $info_fact['cod_info_impuesto_facturas'];
$fecha_anyo                 = $info_fact['fecha_anyo'];
$fecha_hora                 = $info_fact['fecha_hora'];
$vendedor                   = $info_fact['vendedor'];
$descuento                  = $info_fact['descuento'];
$iva                        = $info_fact['iva'];
$flete                      = $info_fact['flete'];
$cod_clientes               = $info_fact['cod_clientes'];
$vlr_cancelado              = $info_fact['vlr_cancelado'];
$estado                     = $info_fact['estado'];
$bolsa                      = $info_fact['bolsa'];

$frag_fecha_venta           = explode('/', $fecha_anyo);
$frag_hora_venta            = explode(':', $fecha_hora);
$dia_venta                  = $frag_fecha_venta[0];
$mes_venta                  = $frag_fecha_venta[1];
$anyo_venta                 = $frag_fecha_venta[2];
$fecha_venta_ymd            = $anyo_venta.$mes_venta.$dia_venta;

$hora_venta                 = $frag_hora_venta[0];
$min_venta                  = $frag_hora_venta[1];
$seg_venta                  = $frag_hora_venta[2];
$hora_venta_his             = $hora_venta.$min_venta.$seg_venta;

$resta                      = 1516399999;
$time_seg                   = time();
$time_date_ymd              = strtotime(date("Y/m/d"));
$fecha                      = date("Ymd");
$hora                       = date("His");
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$obtener_tipopagoventa = "SELECT tipo_pago, fecha, fecha_mes, fecha_anyo, anyo, fecha_hora FROM ventas WHERE cod_factura = '$cod_factura'";
$resultado_tipopagoventa = mysql_query($obtener_tipopagoventa, $conectar);
$dato_tipopagoventa = mysql_fetch_assoc($resultado_tipopagoventa);

$tipo_pago = $dato_tipopagoventa['tipo_pago'];
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$obtener_informacion = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consultar_informacion = mysql_query($obtener_informacion, $conectar);
$info_emp = mysql_fetch_assoc($consultar_informacion);

$cabecera_emp                = $info_emp['cabecera'];
$localidad_emp               = $info_emp['localidad'];
$res_emp                     = $info_emp['res'];
$res1_emp                    = $info_emp['res1'];
$res2_emp                    = $info_emp['res2'];
$vigencia_res_emp            = $info_emp['vigencia_res'];
$fecha_res_emp               = $info_emp['fecha_res'];
$desarrollador_emp           = $info_emp['desarrollador'];
$correo_desarrollador_emp    = $info_emp['correo_desarrollador'];
$tel_desarrollador_emp       = $info_emp['tel_desarrollador'];
$pag_desarrollador_emp       = $info_emp['pag_desarrollador'];
$anyo_emp                    = $info_emp['anyo'];
$direccion_emp               = $info_emp['direccion'];
$telefono_emp                = $info_emp['telefono'];
$nit_emp                     = $info_emp['nit'];
$regimen_emp                 = $info_emp['regimen'];
$nombre_impresora1_emp       = $info_emp['nombre_impresora1'];
$nombre_impresora2_emp       = $info_emp['nombre_impresora2'];
$iva_global_emp              = $info_emp['iva_global'];
$nombre_bolsa_emp            = $info_emp['nombre_bolsa'];
$precio_bolsa_emp            = $info_emp['precio_bolsa'];
$ptj_bolsa_emp               = $info_emp['ptj_bolsa'];
$prefijo_resolucion_emp      = $info_emp['prefijo_resolucion'];
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$obtener_cliente = "SELECT cod_clientes, nombres, apellidos, direccion, cedula FROM clientes WHERE cod_clientes = '$cod_clientes'";
$resultado_cliente = mysql_query($obtener_cliente, $conectar);
$matriz_cliente = mysql_fetch_assoc($resultado_cliente);

$nombre_cli                  = $matriz_cliente['nombres'].' '.$matriz_cliente['apellidos'];
$cedula_cli                  = $matriz_cliente['cedula'];
$direccion_cli               = $matriz_cliente['direccion'];
//----------------- CALCULOS PARA TIPOS DE PAGOS -----------------//
$obtener_calculo_fact = "SELECT sum(vlr_total_venta) as vlr_total_venta FROM ventas WHERE cod_factura = '$cod_factura'";
$resultado_calculo_fact = mysql_query($obtener_calculo_fact, $conectar);
$calculo_fact = mysql_fetch_assoc($resultado_calculo_fact);

$calculo_subtotal            = $calculo_fact['vlr_total_venta'] - ($calculo_fact['vlr_total_venta'] * ($descuento/100));

$suma_temporal = "SELECT Sum(vlr_total_venta -(vlr_total_venta*($descuento/100))) As total_venta, 
Sum((vlr_total_venta - (($descuento/100)*vlr_total_venta))/((iva/100)+(100/100))) As subtotal_base, 
Sum(((vlr_total_venta - (($descuento/100)*vlr_total_venta))/((iva/100)+(100/100)))*(iva/100)) As total_iva, 
Sum(vlr_total_venta*($descuento/100)) AS total_desc, Sum(vlr_total_venta) AS total_venta_neta FROM ventas WHERE cod_factura = '$cod_factura'";
$consulta_temporal = mysql_query($suma_temporal, $conectar);
$suma = mysql_fetch_assoc($consulta_temporal);

$total_venta_neta            = ($suma['total_venta_neta']);
$subtotal_base               = ($suma['subtotal_base']);
$total_desc                  = ($suma['total_desc']);
$total_iva                   = ($suma['total_iva']);
$total_venta_temp            = ($suma['total_venta']);
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
require __DIR__ . '/ticket/autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
//use Mike42\Escpos\CapabilityProfiles\EposTepCapabilityProfile;
//$connector = new WindowsPrintConnector($nombre_impresora1_emp);
$connector = new WindowsPrintConnector("smb://".$nombre_maquina_usuario."/".$nombre_impresora1_usuario);
//$printer = new Printer($connector, $profile);
$printer = new Printer($connector);
#Mando un numero de respuesta para saber que se conecto correctamente.
echo $nombre_impresora1_emp;
echo "<br>";
echo "smb://".$nombre_maquina_usuario.'/'.$nombre_impresora1_usuario;
# Vamos a alinear al centro lo próximo que imprimamos
$printer->setJustification(Printer::JUSTIFY_CENTER);
/* 	Intentaremos cargar e imprimir 	el logo */
try{
$logo = EscposImage::load("../imagenes/logo_empresa_factura_pos.png", false);
$printer->bitImage($logo);
}catch(Exception $e){ }
$printer->text("\n");
/* 	Ahora vamos a imprimir un encabezado */
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->setFont(Printer::FONT_A);
$printer->setTextSize(1, 2);
$printer->setEmphasis(true);
$printer->text($cabecera_emp."\n");
$printer->setEmphasis(false);
$printer->setTextSize(1, 1);
$printer->text($localidad_emp."\n");
$printer->text("NIT: ".$nit_emp."\n");
$printer->text("DIRECCION: ".$direccion_emp."\n");
$printer->text("TELEFONO: ".$telefono_emp."\n");
$printer->text("<<===========================================>>");
$printer->text("\n");

$printer->setJustification(Printer::JUSTIFY_LEFT);
$printer->setEmphasis(true);
$printer->text("FECHA: ");
$printer->setEmphasis(false);
$printer->text($fecha_anyo);
$printer->text("\n");
$printer->setEmphasis(true);
$printer->text("FACTURA DE VENTA: ");
$printer->setEmphasis(false);
$printer->text(str_pad($cod_factura, 5, "0", STR_PAD_LEFT));
$printer->text("\n");
$printer->setEmphasis(true);
$printer->text("CLIENTE: ");
$printer->setEmphasis(false);
$printer->text(utf8_decode($nombre_cli));
$printer->text("\n");
$printer->setEmphasis(true);
$printer->text("VENDEDOR (A): ");
$printer->setEmphasis(false);
$printer->text($vendedor);
$printer->text("\n");
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("<<===========================================>>");
$printer->text("\n");
//---------------------------------------------------------------------------------------------------------------------------------//
$printer->setJustification(Printer::JUSTIFY_LEFT);
$printer->setEmphasis(true);
$printer->text(str_pad("CANT", 6));
$printer->text(str_pad("ARTICULO", 22));
$printer->text(str_pad("P.UNIT", 10));
$printer->text(str_pad("P.TOTAL", 10));
$printer->setEmphasis(false);
//---------------------------------------------------------------------------------------------------------------------------------//
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("\n");
$printer->text("<<===========================================>>");
$printer->text("\n");
/* 	Ahora vamos a imprimir los 	productos */
/*Alinear a la izquierda para la cantidad y el nombre*/
$resultado_sql = "SELECT cod_productos, nombre_productos, unidades_vendidas, detalles, precio_venta, vlr_total_venta 
FROM ventas WHERE cod_factura = '$cod_factura'";
$resultado_info_venta = mysql_query($resultado_sql, $conectar);
while ($info_venta = mysql_fetch_assoc($resultado_info_venta)) {

$cod_productos       = $info_venta['cod_productos'];
$nombre_productos    = utf8_encode($info_venta['nombre_productos']);
$unidades_vendidas   = $info_venta['unidades_vendidas'];
$detalles            = $info_venta['detalles'];
$precio_venta        = $info_venta['precio_venta'];
$vlr_total_venta     = $info_venta['vlr_total_venta'];

$total_caracteres = strlen($nombre_productos);
//---------------------------------------------------------------------------------------------------------------------------------//
if ($total_caracteres > 22) {
$nombre_productos1   = substr($nombre_productos, 0, 22);
$nombre_productos2   = substr($nombre_productos, 22, 21);

$printer->setJustification(Printer::JUSTIFY_LEFT);
$printer->text(str_pad($unidades_vendidas, 6));
$printer->text(str_pad($nombre_productos1, 24));
$printer->text(str_pad(number_format($precio_venta, 0, ",", "."), 7,' ',STR_PAD_LEFT));
$printer->text(str_pad(number_format($vlr_total_venta, 0, ",", "."), 10,' ',STR_PAD_LEFT));
$printer->text("\n");
$printer->text(str_pad("", 6));
$printer->text(str_pad($nombre_productos2, 24));
$printer->text(str_pad("", 7,' ',STR_PAD_LEFT));
$printer->text(str_pad("", 10,' ',STR_PAD_LEFT));
$printer->text("\n");
} else {
$nombre_productos1   = substr($nombre_productos, 0, 22);
$nombre_productos2   = "";

$printer->setJustification(Printer::JUSTIFY_LEFT);
$printer->text(str_pad($unidades_vendidas, 6));
$printer->text(str_pad($nombre_productos1, 24));
$printer->text(str_pad(number_format($precio_venta, 0, ",", "."), 7,' ',STR_PAD_LEFT));
$printer->text(str_pad(number_format($vlr_total_venta, 0, ",", "."), 10,' ',STR_PAD_LEFT));
$printer->text("\n");
}
//---------------------------------------------------------------------------------------------------------------------------------//
}
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
/* 	Terminamos de imprimir - los productos, ahora va el total */
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("<<===========================================>>");
$printer->text("\n");
//---------------------------------------------------------------------------------------------------------------------------------//
$printer->setJustification(Printer::JUSTIFY_RIGHT);
$printer->text(str_pad("SUBTOTAL:", 20));
$printer->text(str_pad("$ ".number_format($subtotal_base, 0, ",", "."), 15));
$printer->text("\n");

$printer->text(str_pad("%DESC: ", 20));
$printer->text(str_pad("% ".number_format($descuento, 0, ",", "."), 15));
$printer->text("\n");

$printer->text(str_pad("DESC:", 20));
$printer->text(str_pad("$ ".number_format($total_desc, 0, ",", "."), 15));
$printer->text("\n");

$printer->text(str_pad("IVA:", 20));
$printer->text(str_pad("$ ".number_format($total_iva, 0, ",", "."), 15));
$printer->text("\n");

$printer->setEmphasis(true);
$printer->text(str_pad("TOTAL:", 20));
$printer->text(str_pad("$ ".number_format($total_venta_temp, 0, ",", "."), 15));
$printer->text("\n");
$printer->setEmphasis(false);
/* 	Podemos poner también un pie de página */
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("<<===========================================>>");
$printer->text("\n");
$printer->text("FACTURA POR COMPUTADOR");
$printer->text("\n");
$printer->text("RES DIAN: ".$res_emp." ");
//$printer->text(" DE: ".$fecha_res_emp."\n");
$printer->text("DESDE ".$prefijo_resolucion_emp." ".str_pad($res1_emp, 5, "0", STR_PAD_LEFT). " AL ".$prefijo_resolucion_emp." ".Str_pad($res2_emp, 5, "0", STR_PAD_LEFT)."\n");
//$printer->text("VIGENCIA ".$vigencia_res_emp." MESES"."\n");
$printer->text("REGIMEN ".$regimen_emp);
$printer->text("\n");
$printer->text("********************************************");
$printer->text("\n");
$printer->setEmphasis(true);
$printer->text("Muchas gracias por su compra"."\n");
$printer->setEmphasis(false);
$printer->text("********************************************");
$printer->text("\n");
$printer->text("<-- ".$desarrollador_emp." : ".$pag_desarrollador_emp." -->");
$printer->text("\n");
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$estandar_a = "{A";
$estandar_b = "{B";
$estandar_c = "{C" . chr(01) . chr(23) . chr(23) . chr(39) . chr(29) . chr(82);
$barra      = $estandar_b.str_pad($cod_factura, 5, "0", STR_PAD_LEFT);
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> setBarcodeHeight(80);
//$printer->setBarcodeTextPosition(Printer::BARCODE_TEXT_BELOW);
$printer -> barcode($barra, Printer::BARCODE_CODE128);
$printer->text("<-- ".$fecha.$hora."-".$cod_factura."-".$fecha_venta_ymd.$hora_venta_his." -->");
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
/*Alimentamos el papel 3 veces*/
$printer->feed(3);
/* 	Cortamos el papel. Si nuestra impresora no tiene soporte para ello, no generará ningún error */
$printer->cut();
/* 	Por medio de la impresora mandamos un pulso. Esto es útil cuando la tenemos conectada por ejemplo a un cajón */
$printer->pulse();
/* Para imprimir realmente, tenemos que "cerrar" la conexión con la impresora. Recuerda incluir esto al final de todos los archivos */
$printer->close();
?>