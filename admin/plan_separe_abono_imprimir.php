<?php
include_once('mpdf/mpdf.php');
include_once('../conexiones/conexione.php'); 
include_once('../evitar_mensaje_error/error.php'); 
include ("../session/funciones_admin.php");
//include_once('../admin/numeros_a_letras_funcion.php');
date_default_timezone_set("America/Bogota");

$cuenta_actual = addslashes($_SESSION['usuario']);
$cod_plan_separe_abono = intval($_GET['cod_plan_separe_abono']);
$cod_plan_separe = intval($_GET['cod_plan_separe']);
$cod_clientes = intval($_GET['cod_clientes']);
//-----------------------------------------------------------------------------------------------------------------//
//-----------------------------------------------------------------------------------------------------------------//
$obtener_informacion = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consultar_informacion = mysql_query($obtener_informacion, $conectar);
$dat = mysql_fetch_assoc($consultar_informacion);

$icono_emp                    = $dat['icono'];
$cabecera_emp                 = $dat['cabecera'];
$localidad_emp                = $dat['localidad'];
$res_emp                      = $dat['res'];
$res1_emp                     = $dat['res1'];
$res2_emp                     = $dat['res2'];
$direccion_emp                = $dat['direccion'];
$telefono_emp                 = $dat['telefono'];
$nit_emp                      = $dat['nit'];
      
$desarrollador_emp            = $dat['desarrollador'];
$correo_desarrolladoremp      = $dat['correo_desarrollador'];
$tel_desarrollador_emp        = $dat['tel_desarrollador'];
$pag_desarrollador_emp        = $dat['pag_desarrollador'];
//-----------------------------------------------------------------------------------------------------------------//
//-----------------------------------------------------------------------------------------------------------------//
$sql_consulta_cliente = "SELECT cedula, nombres, apellidos FROM clientes WHERE cod_clientes = '$cod_clientes'";
$consulta_cliente = mysql_query($sql_consulta_cliente, $conectar) or die(mysql_error());
$total_cliente = mysql_fetch_assoc($consulta_cliente);

$cedula                       = $total_cliente['cedula'];
$nombres                      = $total_cliente['nombres'];
$apellidos                    = $total_cliente['apellidos'];

$sql_sum_abonos = "SELECT Sum(abono_plan_separe) As abono_plan_separe FROM plan_separe_abono WHERE cod_plan_separe = '$cod_plan_separe'";
$consulta_sum_abonos  = mysql_query($sql_sum_abonos, $conectar) or die(mysql_error());
$sum_abonos = mysql_fetch_assoc($consulta_sum_abonos);

$sql_monto_deuda = "SELECT total_plan_separe FROM plan_separe WHERE cod_plan_separe = '$cod_plan_separe'";
$consulta_monto_deuda  = mysql_query($sql_monto_deuda, $conectar) or die(mysql_error());
$sum_monto_deuda = mysql_fetch_assoc($consulta_monto_deuda);

$total_plan_separe            = $sum_monto_deuda['total_plan_separe'];
$abono_plan_separe            = $sum_abonos['abono_plan_separe'];
$total_saldo_plan_separe      = $total_plan_separe - $abono_plan_separe;
//-----------------------------------------------------------------------------------------------------------------//
//-----------------------------------------------------------------------------------------------------------------//
$resta                        = 1516399999;
$time_seg                     = time();
$time_date_ymd                = strtotime(date("Y/m/d"));
$fecha                        = date("Ymd");
$hora                         = date("His");
//-----------------------------------------------------------------------------------------------------------------//
//-----------------------------------------------------------------------------------------------------------------//
$codigoHTML='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>'."Abonos Plan Separe: ".$cod_plan_separe.'</title>
</head>
<body>
<!--<link rel="stylesheet" type="text/css" href="../estilo_css/por_defecto.css">-->
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
<td align="center"><strong><p style="font-size:30px">ABONOS PLAN SEPARE</p></strong></td>
</tr>
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier;">
<tr>
<td align="center">====================================================================================================</td>
</tr>
</table>

<table align="left" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:40pt;">
  <tr>
    <td><p style="font-size:30px"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NOMBRE CLIENTE: </strong></p></td>
    <td><p style="font-size:30px">'.$nombres.' '.$apellidos.'</p></td>
  </tr>
  <tr>
    <td><p style="font-size:30px"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NIT CLIENTE: </strong></p></td>
    <td><p style="font-size:30px">'.$cedula.'</p></td>
  </tr>
  <tr>
    <td><p style="font-size:30px"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FACTURA: </strong></p></td>
    <td><p style="font-size:30px">'.$cod_plan_separe.'</p></td>
  </tr>
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:40pt;">
<tr>
<td align="center" colspan="5">================================</td>
</tr>
<tr>
<td align="center"><strong><p style="font-size:40px">ABONO</p></strong></td>
<td align="center"><strong><p style="font-size:40px">FECHA</p></strong></td>
<td align="center"><strong><p style="font-size:40px">PAGO A</p></strong></td>
</tr>
<tr>
<td align="center" colspan="5">================================</td>
</tr>
';
$sql = "SELECT * FROM plan_separe_abono WHERE cod_plan_separe_abono = '$cod_plan_separe_abono' ORDER BY fecha_invert DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta);
while ($datos = mysql_fetch_assoc($consulta)) {

$cod_plan_separe_abono        = $datos['cod_plan_separe_abono'];
$abono_plan_separe            = $datos['abono_plan_separe'];
$cuenta                       = $datos['cuenta'];
$mensaje                      = $datos['mensaje'];
$fecha_anyo                   = $datos['fecha_anyo'];
$hora                         = $datos['hora'];
$codigoHTML.='
<tr>
<td align="center"><p style="font-size:50px"><strong>'.number_format($abono_plan_separe, 0, ",", ".").'</strong></p></td>
<td align="center"><p style="font-size:45px"><strong>'.$fecha_anyo.'</strong></p></td>
<td align="center"><p style="font-size:40px"><strong>'.$cuenta.'</strong></p></td>
</tr>';
} 
$codigoHTML.='
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier;">
<tr>
<td align="center">====================================================================================================</td>
</tr>
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:45pt;">
<tr>
<td align="center"><strong><p style="font-size:40px">TOTAL PLAN SEPARE</strong></td>
<td align="center"><strong><p style="font-size:40px">'.number_format($total_plan_separe, 0, ",", ".").'</p></strong></td>
</tr>
<!--
<tr>
<td align="center"><strong><p style="font-size:40px">TOTAL ABONADO</strong></td>
<td align="center"><strong><p style="font-size:40px">'.number_format($abono_plan_separe, 0, ",", ".").'</p></strong></td>
</tr>
<tr>
<td align="center"><strong><p style="font-size:40px">SALDO PENDIENTE</strong></td>
<td align="center"><strong><p style="font-size:40px">'.number_format($total_saldo_plan_separe, 0, ",", ".").'</p></strong></td>
</tr>
-->
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier;">
<tr>
<td align="center">====================================================================================================</td>
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
$nombre_archivo = 'Abonos_plan_separe_'.$cod_clientes;
$mpdf->output($nombre_archivo, 'I');
?>