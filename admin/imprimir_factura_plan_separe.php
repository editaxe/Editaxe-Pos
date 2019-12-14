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

$cuenta_actual     = addslashes($_SESSION['usuario']);
$cod_plan_separe       = intval($_GET['numero_factura']);
$descuento         = addslashes($_GET['descuento']);
$tipo_pago         = intval($_GET['tipo_pago']);
$cod_clientes      = intval($_GET['cod_clientes']);
$resta             = 1516399999;
$time_seg          = time();
$time_date_ymd     = strtotime(date("Y/m/d"));
$fecha             = date("Ymd");
$hora              = date("His");
//-----------------------------------------------------------------------------------------------------------------//
//-----------------------------------------------------------------------------------------------------------------//
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
//-----------------------------------------------------------------------------------------------------------------//
//-----------------------------------------------------------------------------------------------------------------//
$obtener_info_fact = "SELECT * FROM plan_separe_info_impuesto WHERE cod_plan_separe = '$cod_plan_separe'";
$resultado_info_fact = mysql_query($obtener_info_fact, $conectar) or die(mysql_error());
$info_fact = mysql_fetch_assoc($resultado_info_fact);

$vlr_cancelado                       = $info_fact['vlr_cancelado'];
$fecha_anyo                          = $info_fact['fecha_anyo'];
$fecha_hora                          = substr($info_fact['fecha_hora'], 0, 5);
$vendedor                            = $info_fact['vendedor'];
//-----------------------------------------------------------------------------------------------------------------//
//-----------------------------------------------------------------------------------------------------------------//
$obtener_cliente = "SELECT nombres, apellidos, cedula, direccion FROM clientes WHERE cod_clientes = '$cod_clientes'";
$resultado_cliente = mysql_query($obtener_cliente, $conectar) or die(mysql_error());
$matriz_cliente = mysql_fetch_assoc($resultado_cliente);

$nombre_cliente                     = $matriz_cliente['nombres'].' '.$matriz_cliente['apellidos'];
$cedula_cliente                     = $matriz_cliente['cedula'];
$direccion_cliente                  = $matriz_cliente['direccion'];
//-----------------------------------------------------------------------------------------------------------------//
//-----------------------------------------------------------------------------------------------------------------//
$suma_temporal = "SELECT Sum(vlr_total_venta -(vlr_total_venta*(descuento_ptj/100))) As total_venta, 
Sum((vlr_total_venta - ((descuento_ptj/100)*vlr_total_venta))/((iva/100)+(100/100))) As subtotal_base, 
Sum(((vlr_total_venta - ((descuento_ptj/100)*vlr_total_venta))/((iva/100)+(100/100)))*(iva/100)) As total_iva, 
Sum(vlr_total_venta*(descuento_ptj/100)) AS total_desc, Sum(vlr_total_venta) AS total_venta_neta, tipo_pago FROM plan_separe_producto WHERE cod_plan_separe = '$cod_plan_separe'";
$consulta_temporal = mysql_query($suma_temporal, $conectar) or die(mysql_error());
$suma = mysql_fetch_assoc($consulta_temporal);

$total_venta_neta                   = $suma['total_venta_neta'];
$subtotal_base                      = $suma['subtotal_base'];
$total_desc                         = $suma['total_desc'];
$total_iva                          = $suma['total_iva'];
$tipo_pago                          = $suma['tipo_pago'];
$total_venta_temp                   = $suma['total_venta_neta'];
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
if ($tipo_pago == 1) { $nombre_tipo_pago = 'CONTADO'; $total_cambio = $vlr_cancelado - $total_venta_temp; } elseif ($tipo_pago == 2) { $nombre_tipo_pago = 'CREDITO'; } else { $nombre_tipo_pago = 'CONTADO'; $total_cambio = 0; }
//-----------------------------------------------------------------------------------------------------------------//
//-----------------------------------------------------------------------------------------------------------------//
$codigoHTML='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>'."Factura Plan Separe: ".$cod_plan_separe.'</title>
</head>
<body>
<link href="../imagenes/'.$icono_emp.'" type="image/x-icon" rel="shortcut icon" />

<div align="center">

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:50pt;">
<tr>
<td align="center"><p style="font-size:50px"><strong>'.$cabecera_emp.'</strong>
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

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier;">
<tr>
<td align="center">====================================================================================================</td>
</tr>
</table>

<table align="left" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:40pt;">
  <tr>
    <td><p style="font-size:30px"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FECHA: </strong></p></td>
    <td><p style="font-size:30px">'.$fecha_anyo.' - '.$fecha_hora.'</p></td>
  </tr>
  <tr>
    <td><p style="font-size:30px"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PLAN SEPARE: </strong></p></td>
    <td><p style="font-size:30px">'.$cod_plan_separe.'</p></td>
  </tr>
  <tr>
    <td><p style="font-size:30px"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CLIENTE: </strong></p></td>
    <td><p style="font-size:30px">'.utf8_decode($nombre_cliente).'</strong></p></td>
  </tr>
  <tr>
    <td><p style="font-size:30px"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;VENDEDOR: </strong></p></td>
    <td><p style="font-size:30px">'.$vendedor.'</p></td>
  </tr>
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:30pt;">
<tr>
<td align="center" colspan="4">================================</td>
</tr>
<tr>
<td align="left"><strong><p style="font-size:30px">ARTICULO</p></strong></td>
<td align="center"><strong><p style="font-size:30px">CANT</p></strong></td>
<td align="center"><strong><p style="font-size:30px">P.UNIT</p></strong></td>
<td align="center"><strong><p style="font-size:30px">P.TOTAL</p></strong></td>
</tr>
<tr>
<td align="center" colspan="4">================================</td>
</tr>
';
$resultado_sql = "SELECT cod_productos, nombre_productos, unidades_vendidas, detalles, precio_venta, vlr_total_venta FROM plan_separe_producto WHERE cod_plan_separe = '$cod_plan_separe'";
$resultado_info_venta = mysql_query($resultado_sql, $conectar) or die(mysql_error());
while ($info_venta = mysql_fetch_assoc($resultado_info_venta)) {

$cod_productos        = $info_venta['cod_productos'];
$nombre_productos     = $info_venta['nombre_productos'];
$unidades_vendidas    = $info_venta['unidades_vendidas'];
$detalles             = $info_venta['detalles'];
$precio_venta         = $info_venta['precio_venta'];
$vlr_total_venta      = $info_venta['vlr_total_venta'];
$codigoHTML.='
<tr>
<td align="left"><p style="font-size:30px"><strong>'.$nombre_productos.'</strong></p></td>
<td align="center"><p style="font-size:30px"><strong>'.$unidades_vendidas.'</strong></p> <p style="font-size:15px">'.substr($detalles, 0, 3).'</p></td>
<td align="right"><p style="font-size:30px"><strong>'.number_format($precio_venta, 0, ",", ".").'</strong></p></td>
<td align="right"><p style="font-size:30px"><strong>'.number_format($vlr_total_venta, 0, ",", ".").'</strong></p></td>
</tr>';
} 
$codigoHTML.='
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier;">
<tr>
<td align="center">====================================================================================================</td>
</tr>
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:35pt;">
<tr>
<td align="center"><strong><p style="font-size:35px">SUBTOTAL</strong></td>
<td align="center"><strong><p style="font-size:35px">'.number_format($subtotal_base, 0, ",", ".").'</p></strong></td>
</tr>
<tr>
<td align="center"><strong><p style="font-size:35px">%DESC</strong></td>
<td align="center"><strong><p style="font-size:35px">'.$descuento.'%'.'</p></strong></td>
</tr>
<tr>
<td align="center"><strong><p style="font-size:35px">$DESC</strong></td>
<td align="center"><strong><p style="font-size:35px">'.number_format($total_desc, 0, ",", ".").'</p></strong></td>
</tr>
<tr>
<td align="center"><strong><p style="font-size:35px">IVA</strong></td>
<td align="center"><strong><p style="font-size:35px">'.number_format($total_iva, 0, ",", ".").'</p></strong></td>
</tr>
<tr>
<td align="center"><strong><p style="font-size:40px">TOTAL</strong></td>
<td align="center"><strong><p style="font-size:40px">'.number_format($total_venta_temp, 0, ",", ".").'</p></strong></td>
</tr>
<tr>
<td align="center"><strong><p style="font-size:40px">ABONO</strong></td>
<td align="center"><strong><p style="font-size:40px">'.number_format($vlr_cancelado, 0, ",", ".").'</p></strong></td>
</tr>
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier;">
<tr>
<td align="center">====================================================================================================</td>
</tr>
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:40pt;">
  <tr>
    <td align="center"><p style="font-size:30px">FACTURA POR COMPUTADOR</p></td>
  </tr>
  <tr>
    <td align="center"><p style="font-size:30px">RES DIAN: '.$res_emp.' DESDE '.$res1_emp.' AL '.$res2_emp.'</p></td>
  </tr>
  <tr>
    <td align="center"><p style="font-size:30px">REGIMEN '.$regimen_emp.'</p></td>
  </tr>
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:30pt;">
<tr>
<td align="center">************************************************</td>
</tr>
<tr>
<td align="center">Muchas gracias por su compra.</td>
</tr>
<tr>
<td align="center">************************************************</td>
</tr>
</table>
';
$codigoHTML.="
<table align='center' border='0' width='95%' cellspacing='0' cellpadding='0' style='font-family: Courier; font-size:30pt;'>
<tr>
<td align='center'>$desarrollador_emp : $pag_desarrollador_emp</td>
</tr>
<tr>
<td align='center'><barcode code='$cod_plan_separe' type='C128A' size='3' height='1' /></td>
</tr>
<tr>
<td align='center'>$fecha-$cod_plan_separe-$hora</td>
</tr>
</table>

</div>
</body>
</html>";
/*
<tr></tr>
<td align="left"><p style="font-size:40px"><strong>'.convertir_numeros_a_letras($total_venta_temp).'</strong></td>
*/
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
//$mpdf=new mPDF('UTF-8-s','');
$mpdf->mirrorMargins = 1;
$mpdf->SetDisplayMode('fullpage');
$mpdf->writeHTML(utf8_encode($codigoHTML));
$mpdf->SetJS('print();');
$nombre_archivo = 'Factura_Plan_Separe_'.$cod_plan_separe;
$mpdf->output($nombre_archivo, 'I');
?>