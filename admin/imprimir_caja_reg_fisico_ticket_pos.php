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
$cod_caja_registro_fisico    = intval($_POST['cod_caja_registro_fisico']);
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
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
require __DIR__ . '/ticket/autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
//use Mike42\Escpos\CapabilityProfiles\EposTepCapabilityProfile;
/* 	Este ejemplo imprime un Ticket de venta desde una impresora térmica */
/* Aquí, en lugar de "POS" (que es el nombre de mi impresora) 	escribe el nombre de la tuya. Recuerda que debes compartirla 	desde el panel de control */
$connector = new WindowsPrintConnector($nombre_impresora1_emp);
//$printer = new Printer($connector, $profile);
$printer = new Printer($connector);
#Mando un numero de respuesta para saber que se conecto correctamente.
echo 1;
/* Vamos a imprimir un logotipo - opcional. Recuerda que esto -	no funcionará en todas las -Impresoras
Pequeña nota: Es recomendable que la imagen no sea - transparente (aunque sea png hay que quitar el canal alfa) - y que tenga una resolución baja. En mi caso la imagen que uso es de 250 x 250 */
# Vamos a alinear al centro lo próximo que imprimamos
$printer->setJustification(Printer::JUSTIFY_CENTER);
/* 	Intentaremos cargar e imprimir 	el logo */
/*
try{
	$logo = EscposImage::load("../imagenes/logo_empresa_factura_pos.png", false);
    $printer->bitImage($logo);
}catch(Exception $e){ }
$printer->text("\n");
*/
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
$printer->text("<<=========================================>>");
$printer->text("\n");
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$printer->setJustification(Printer::JUSTIFY_LEFT);
$printer->setEmphasis(true);
$printer->text(str_pad("T.FISICO", 8, ' ', STR_PAD_BOTH));
$printer->text(str_pad("T.SISTEMA", 13, ' ', STR_PAD_BOTH));
$printer->text(str_pad("FECHA", 10, ' ', STR_PAD_BOTH));
$printer->text(str_pad("CAJA", 7, ' ', STR_PAD_BOTH));
$printer->setEmphasis(false);
//---------------------------------------------------------------------------------------------------------------------------------//
$resultado_sql = "SELECT * FROM caja_registro_fisico WHERE cod_caja_registro_fisico = '$cod_caja_registro_fisico'";
$resultado_info_venta = mysql_query($resultado_sql, $conectar);
$info_venta = mysql_fetch_assoc($resultado_info_venta);

$cod_base_caja         = $info_venta['cod_base_caja'];
$total_ventas_fisico   = $info_venta['total_ventas_fisico'];
$total_ventas_sistema  = $info_venta['total_ventas_sistema'];
$comentario            = $info_venta['comentario'];
$fecha                 = $info_venta['fecha'];
$fecha_mes             = $info_venta['fecha_mes'];
$fecha_anyo            = $info_venta['fecha_anyo'];
$anyo                  = $info_venta['anyo'];
$fecha_hora            = $info_venta['fecha_hora'];
$usuario               = $info_venta['usuario'];
//---------------------------------------------------------------------------------------------------------------------------------//
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("\n");
$printer->text(str_pad(number_format($total_ventas_fisico, 0, ",", "."), 8, ' ', STR_PAD_BOTH));
$printer->text(str_pad(number_format($total_ventas_sistema, 0, ",", "."), 12, ' ', STR_PAD_BOTH));
$printer->text(str_pad($fecha_anyo, 12, ' ', STR_PAD_BOTH));
$printer->text(str_pad($usuario, 7, ' ', STR_PAD_BOTH));
$printer->text("\n");
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("<<=========================================>>");
$printer->text("\n");
$printer->text(str_pad("DIFERENCIA: ", 10, ' ', STR_PAD_BOTH));
$printer->text(str_pad(number_format($total_ventas_fisico - $total_ventas_sistema, 0, ",", "."), 7, ' ', STR_PAD_BOTH));
//---------------------------------------------------------------------------------------------------------------------------------//
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("\n");
$printer->text("<<---------------------------------------->>");
/*Alimentamos el papel 3 veces*/
$printer->feed(3);
/* 	Cortamos el papel. Si nuestra impresora no tiene soporte para ello, no generará ningún error */
$printer->cut();
/* 	Por medio de la impresora mandamos un pulso. Esto es útil cuando la tenemos conectada por ejemplo a un cajón */
$printer->pulse();
/* Para imprimir realmente, tenemos que "cerrar" la conexión con la impresora. Recuerda incluir esto al final de todos los archivos */
$printer->close();
?>