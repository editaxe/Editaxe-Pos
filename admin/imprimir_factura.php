<?php ob_start();?>
<?php
include_once('mpdf/mpdf.php');
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar);
date_default_timezone_set("America/Bogota");

include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
      } else { header("Location:../index.php");
}

$cuenta_actual                       = addslashes($_SESSION['usuario']);
$cod_factura                         = intval($_GET['numero_factura']);
$descuento                           = addslashes($_GET['descuento']);
$tipo_pago                           = intval($_GET['tipo_pago']);
$cod_clientes                        = intval($_GET['cod_clientes']);
$resta                               = 1516399999;
$time_seg                            = time();
$time_date_ymd                       = strtotime(date("Y/m/d"));
$fecha                               = date("Ymd");
$hora                                = date("His");
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$obtener_info_mquina_impr = "SELECT nombre_maquina, nombre_impresora1 FROM administrador WHERE cuenta = '$cuenta_actual'";
$resultado_info_mquina_impr = mysql_query($obtener_info_mquina_impr, $conectar);
$info_mquina_impr = mysql_fetch_assoc($resultado_info_mquina_impr);

$nombre_maquina_usuario               = $info_mquina_impr['nombre_maquina'];
$nombre_impresora1_usuario            = $info_mquina_impr['nombre_impresora1'];
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$obtener_info_fact = "SELECT * FROM info_impuesto_facturas WHERE cod_factura = '$cod_factura'";
$resultado_info_fact = mysql_query($obtener_info_fact, $conectar);
$info_fact = mysql_fetch_assoc($resultado_info_fact);
/*
$cod_info_impuesto_facturas          = $info_fact['cod_info_impuesto_facturas'];
$fecha_anyo                          = $info_fact['fecha_anyo'];
$fecha_hora                          = $info_fact['fecha_hora'];
$vendedor                            = $info_fact['vendedor'];
$descuento                           = $info_fact['descuento'];
$iva                                 = $info_fact['iva'];
$flete                               = $info_fact['flete'];
$cod_clientes                        = $info_fact['cod_clientes'];
$vlr_cancelado                       = $info_fact['vlr_cancelado'];
$estado                              = $info_fact['estado'];
$bolsa                               = $info_fact['bolsa'];

$frag_fecha_venta                    = explode('/', $fecha_anyo);
$frag_hora_venta                     = explode(':', $fecha_hora);
$dia_venta                           = $frag_fecha_venta[0];
$mes_venta                           = $frag_fecha_venta[1];
$anyo_venta                          = $frag_fecha_venta[2];
$fecha_venta_ymd                     = $anyo_venta.$mes_venta.$dia_venta;

$hora_venta                          = $frag_hora_venta[0];
$min_venta                           = $frag_hora_venta[1];
$seg_venta                           = $frag_hora_venta[2];
$hora_venta_his                      = $hora_venta.$min_venta.$seg_venta;
*/
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$obtener_info_fact_venta = "SELECT * FROM ventas WHERE cod_factura = '$cod_factura'";
$resultado_info_fact_venta = mysql_query($obtener_info_fact_venta, $conectar);
$info_fact_venta = mysql_fetch_assoc($resultado_info_fact_venta);

$fecha_anyo                          = $info_fact_venta['fecha_anyo'];
$fecha_hora                          = $info_fact_venta['fecha_hora'];
$vendedor                            = $info_fact_venta['vendedor'];
$cod_clientes                        = $info_fact_venta['cod_clientes'];
$vlr_cancelado                       = $info_fact_venta['nombre_peso'];
$tipo_pago                           = $info_fact_venta['tipo_pago'];

$frag_fecha_venta                    = explode('/', $fecha_anyo);
$frag_hora_venta                     = explode(':', $fecha_hora);
$dia_venta                           = $frag_fecha_venta[0];
$mes_venta                           = $frag_fecha_venta[1];
$anyo_venta                          = $frag_fecha_venta[2];
$fecha_venta_ymd                     = $anyo_venta.$mes_venta.$dia_venta;

$hora_venta                          = $frag_hora_venta[0];
$min_venta                           = $frag_hora_venta[1];
$seg_venta                           = $frag_hora_venta[2];
$hora_venta_his                      = $hora_venta.$min_venta.$seg_venta;

$resta                               = 1516399999;
$time_seg                            = time();
$time_date_ymd                       = strtotime(date("Y/m/d"));
$fecha                               = date("Ymd");
$hora                                = date("His");
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$obtener_informacion = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consultar_informacion = mysql_query($obtener_informacion, $conectar) or die(mysql_error());
$info_emp = mysql_fetch_assoc($consultar_informacion);

$icono_emp                           = $info_emp['icono'];
$cabecera_emp                        = $info_emp['cabecera'];
$localidad_emp                       = $info_emp['localidad'];
$res_emp                             = $info_emp['res'];
$res1_emp                            = $info_emp['res1'];
$res2_emp                            = $info_emp['res2'];
$direccion_emp                       = $info_emp['direccion'];
$telefono_emp                        = $info_emp['telefono'];
$nit_emp                             = $info_emp['nit'];
$regimen_emp                         = $info_emp['regimen'];
$propietario_nombres_apellidos_emp   = $info_emp['propietario_nombres_apellidos'];
$desarrollador_emp                   = $info_emp['desarrollador'];
$correo_desarrolladoremp             = $info_emp['correo_desarrollador'];
$tel_desarrollador_emp               = $info_emp['tel_desarrollador'];
$pag_desarrollador_emp               = $info_emp['pag_desarrollador'];
$prefijo_resolucion_emp              = $info_emp['prefijo_resolucion'];
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$obtener_cliente = "SELECT cod_clientes, nombres, apellidos, direccion, cedula FROM clientes WHERE cod_clientes = '$cod_clientes'";
$resultado_cliente = mysql_query($obtener_cliente, $conectar) or die(mysql_error());
$matriz_cliente = mysql_fetch_assoc($resultado_cliente);

$nombre_cliente                      = $matriz_cliente['nombres'].' '.$matriz_cliente['apellidos'];
$cedula_cli                          = $matriz_cliente['cedula'];
$direccion_cli                       = $matriz_cliente['direccion'];
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$suma_temporal = "SELECT Sum(vlr_total_venta -(vlr_total_venta*(descuento_ptj/100))) As total_venta, 
Sum((vlr_total_venta - ((descuento_ptj/100)*vlr_total_venta))/((iva/100)+(100/100))) As subtotal_base, 
Sum(((vlr_total_venta - ((descuento_ptj/100)*vlr_total_venta))/((iva/100)+(100/100)))*(iva/100)) As total_iva, 
Sum(vlr_total_venta*(descuento_ptj/100)) AS total_desc, Sum(vlr_total_venta) AS total_venta_neta FROM ventas WHERE cod_factura = '$cod_factura'";
$consulta_temporal = mysql_query($suma_temporal, $conectar) or die(mysql_error());
$suma = mysql_fetch_assoc($consulta_temporal);

$total_venta_neta                    = ($suma['total_venta_neta']);
$subtotal_base                       = ($suma['subtotal_base']);
$total_desc                          = ($suma['total_desc']);
$total_iva                           = ($suma['total_iva']);
$total_venta_temp                    = ($suma['total_venta']);
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
//if ($tipo_pago == 1) { $nombre_tipo_pago = 'CONTADO'; $total_cambio = $vlr_cancelado - $total_venta_temp; } elseif ($tipo_pago == 2) { $nombre_tipo_pago = 'CREDITO'; } else { $nombre_tipo_pago = 'CONTADO'; $total_cambio = 0; }
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$cod_factura_strpad                  = str_pad($cod_factura, 6, "0", STR_PAD_LEFT);
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$margen_izq                          = '2';
$margen_der                          = '2';
$margen_inf_encabezado               = '2';
$margen_sup_encabezado               = '2';
$posicion_sup_encabezado             = '1';
$posicion_inf_encabezado             = '2';

$titulo_doc_pdf                      = $nombre_cli;
$autor_doc_pdf                       = $propietario_nombres_apellidos_emp;
$creador_doc_pdf                     = $propietario_nombres_apellidos_emp;
$tema_doc_pdf                        = "Factura de venta";
$palabras_claves_doc_pdf             = $nombre_cli." - ".$cedula_cli.' - '.$cod_factura;
//$mpdf                              = new mPDF('c','Legal');
$mpdf                                = new mPDF('en-GB-x','Legal','','',$margen_izq, $margen_der, $margen_inf_encabezado, $margen_sup_encabezado, $posicion_sup_encabezado, $posicion_inf_encabezado);
$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins

$codigoHTML='
<!DOCTYPE html>
<html lang="es">
<head>
<title></title>
<meta charset="utf-8" />
<title>'."Venta: ".$cod_factura.'</title>
</head>
<body>
<style> div { width: 800px; margin:0px; text-align:center; font-size:30px; } #barras { padding:0px; } </style>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:40pt;">
<tr>
<td align="center"><p style="font-size:50px"><img src="../imagenes/logo_empresa_factura_pos_blanco_negro.jpg"></p></td>
</tr>
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:40pt;">
<tr>
<td align="center"><p style="font-size:40px"><strong>'.$cabecera_emp.'</strong></p></td>
</tr>
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:40pt;">
<tr>
<td align="center"><p style="font-size:30px">'.$localidad_emp.'</p></td>
</tr>
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:40pt;">
<tr>
<td align="center"><p style="font-size:30px">NIT: '.$nit_emp.'</p></td>
</tr>
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:40pt;">
<tr>
<td align="center"><p style="font-size:30px">DIRECCION: '.$direccion_emp.'</p></td>
</tr>
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:40pt;">
<tr>
<td align="center"><p style="font-size:30px">TELEFONO: '.$telefono_emp.'</p></td>
</tr>
</table>


<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:10pt;">
<tr>
<td align="center">-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
</tr>
</table>

<table align="left" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:40pt;">
  <tr>
    <td><p style="font-size:30px"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FECHA: </strong></p></td>
    <td><p style="font-size:30px"><strong>'.$fecha_anyo.'</strong></p></td>
  </tr>
  <tr>
    <td><p style="font-size:30px"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FACTURA DE VENTA: </strong></p></td>
    <td><p style="font-size:30px"><strong>'.$cod_factura.'</strong></p></td>
  </tr>
<!--
  <tr>
    <td><p style="font-size:30px"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TIPO DE PAGO: </strong></p></td>
    <td><p style="font-size:30px"><strong>'.$nombre_tipo_pago.'</strong></p></td>
  </tr>
-->
  <tr>
    <td><p style="font-size:30px"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NIT CLIENTE: </strong></p></td>
    <td><p style="font-size:30px"><strong>'.($cedula_cli).'</strong></p></td>
  </tr>
  <tr>
    <td><p style="font-size:30px"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NOMBRE CLIENTE: </strong></p></td>
    <td><p style="font-size:30px"><strong>'.utf8_decode($nombre_cliente).'</strong></p></td>
  </tr>
  <tr>
    <td><p style="font-size:30px"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;VENDEDOR (A): </strong></p></td>
    <td><p style="font-size:30px"><strong>'.$vendedor.'</strong></p></td>
  </tr>
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:10pt;">
<tr>
<td align="center">-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
</tr>
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:25pt;">
<tr>
<td align="center" colspan="4"></td>
</tr>
<tr>
<td align="center"><strong><p style="font-size:30px">CANT</p></strong></td>
<td align="left"><strong><p style="font-size:30px">ARTICULO</p></strong></td>
<td align="center"><strong><p style="font-size:30px">P.UNIT</p></strong></td>
<td align="center"><strong><p style="font-size:30px">P.TOTAL</p></strong></td>
</tr>
<tr>
<td align="center" colspan="4">------------------------------------------------------------------------</td>
</tr>
';

$resultado_sql = "SELECT cod_productos, nombre_productos, unidades_vendidas, detalles, precio_venta, vlr_total_venta 
FROM ventas WHERE cod_factura = '$cod_factura'";
$resultado_info_venta = mysql_query($resultado_sql, $conectar) or die(mysql_error());
while ($info_venta = mysql_fetch_assoc($resultado_info_venta)) {

$cod_productos               = $info_venta['cod_productos'];
$nombre_productos            = $info_venta['nombre_productos'];
$unidades_vendidas           = $info_venta['unidades_vendidas'];
$detalles                    = $info_venta['detalles'];
$precio_venta                = $info_venta['precio_venta'];
$vlr_total_venta             = $info_venta['vlr_total_venta'];

$codigoHTML.='
<tr>
<td align="center"><p style="font-size:30px;"><strong>'.$unidades_vendidas.'</strong></p></td>
<td align="left" width="400px"><p style="font-size:30px;"><strong>'.$nombre_productos.'</strong></p></td>
<td align="right"><p style="font-size:30px;"><strong>'.number_format($precio_venta, 0, ",", ".").'</strong></p></td>
<td align="right"><p style="font-size:30px;"><strong>'.number_format($vlr_total_venta, 0, ",", ".").'</strong></p></td>
</tr>
';
} 
$codigoHTML.='
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:10pt;">
<tr>
<td align="center">-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
</tr>
</table>

<table align="center" border="0" width="50%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:35pt;">
  <tr>
    <td align="left"><p style="font-size:30px"><strong>SUBTOTAL</strong></p></td>
    <td align="right"><p style="font-size:30px"><strong>'.number_format($subtotal_base, 0, ",", ".").'</strong></p></td>
  </tr>
  <tr>
    <td align="left"><p style="font-size:30px"><strong>%DESC</strong></p></td>
    <td align="right"><p style="font-size:30px"><strong>'.$descuento.'</strong></p></td>
  </tr>
  <tr>
    <td align="left"><p style="font-size:30px"><strong>$DESC</strong></p></td>
    <td align="right"><p style="font-size:30px"><strong>'.number_format($total_desc, 0, ",", ".").'</strong></p></td>
  </tr>
  <tr>
    <td align="left"><p style="font-size:30px"><strong>IVA</strong></p></td>
    <td align="right"><p style="font-size:30px"><strong>'.number_format($total_iva, 0, ",", ".").'</strong></p></td>
  </tr>
  <tr>
    <td align="left"><strong><p style="font-size:35px">TOTAL</p></strong></td>
    <td align="right"><p style="font-size:35px"><strong>'.number_format($total_venta_temp, 0, ",", ".").'</strong></p></td>
  </tr>
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:10pt;">
<tr>
<td align="center">-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
</tr>
</table>


<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:25pt;">
  <tr>
    <td align="center"><p style="font-size:26px"><strong>FACTURA POR COMPUTADOR</strong></p></td>
  </tr>
  <tr>
    <td align="center"><p style="font-size:26px"><strong>RES DIAN: '.$res_emp.' DESDE '.$prefijo_resolucion_emp.' '.str_pad($res1_emp, 6, "0", STR_PAD_LEFT).' AL '.$prefijo_resolucion_emp.' '.$res2_emp.'</strong></p></td>
  </tr>
  <tr>
    <td align="center"><p style="font-size:26px"><strong>REGIMEN '.$regimen_emp.'</strong></p></td>
  </tr>
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:25pt;">
  <tr>
    <td align="center"><p style="font-size:30px">*****************************************************************</p></td>
  </tr>
  <tr>
    <td align="center"><p style="font-size:30px"><strong>Muchas gracias por su compra</strong></p></td>
  </tr>
  <tr>
    <td align="center"><p style="font-size:30px">*****************************************************************</p></td>
  </tr>
</table>
';
$codigoHTML.="
<table align='center' border='0' width='95%' cellspacing='0' cellpadding='0' style='font-family: Courier; font-size:20pt;'>
<tr>
<td align='center'><p style='font-size:30px'><strong>$desarrollador_emp : $pag_desarrollador_emp</strong></p></td>
</tr>
<tr>
<td align='center'><barcode code='$cod_factura_strpad' type='C128A' size='3' height='1' /></td>
</tr>
<tr>
<td align='center'><p style='font-size:27px'><strong>$fecha$hora-$cod_factura-$fecha_venta_ymd$hora_venta_his</strong>_impdf</p></td>
</tr>
</table>

</body>
</html>
";


$margen_izq = '1';
$margen_der = '1';
$margen_inf_encabezado = '1';
$margen_sup_encabezado = '1';
$posicion_sup_encabezado = '1';
$posicion_inf_encabezado = '1';
$mpdf = new mPDF('en-GB-x','Legal','','',$margen_izq, $margen_der, $margen_inf_encabezado, $margen_sup_encabezado, $posicion_sup_encabezado, $posicion_inf_encabezado);
//$mpdf = new mPDF('en-GB-x','A4','','',5,5,5,5,0,0);
//$mpdf = new mPDF('','', 0, '', 15, 15, 16, 16, 9, 9, 'L');
//$mpdf = new mPDF('utf-8', 'A4');
//$mpdf=new mPDF('UTF-8-s',''
//$mpdf->SetDisplayMode('fullpage','continuous');
//$mpdf->SetDisplayMode('fullpage');
$mpdf->mirrorMargins = 1;
$mpdf->SetDisplayMode('fullpage');
$mpdf->writeHTML(utf8_encode($codigoHTML));
$mpdf->SetJS('print();');
$nombre_archivo = 'Factura_de_Venta_'.$cod_factura;
$mpdf->output($nombre_archivo, 'I');
exit;
?>
<?php ob_end_flush(); ?>