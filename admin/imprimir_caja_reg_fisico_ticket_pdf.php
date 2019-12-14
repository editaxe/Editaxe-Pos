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
$cod_caja_registro_fisico            = intval($_GET['cod_caja_registro_fisico']);
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
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$resultado_sql = "SELECT * FROM caja_registro_fisico WHERE cod_caja_registro_fisico = '$cod_caja_registro_fisico'";
$resultado_info_venta = mysql_query($resultado_sql, $conectar);
$info_venta = mysql_fetch_assoc($resultado_info_venta);

$cod_base_caja                       = $info_venta['cod_base_caja'];
$total_ventas_fisico                 = $info_venta['total_ventas_fisico'];
$total_ventas_sistema                = $info_venta['total_ventas_sistema'];
$comentario                          = $info_venta['comentario'];
$fecha                               = $info_venta['fecha'];
$fecha_mes                           = $info_venta['fecha_mes'];
$fecha_anyo                          = $info_venta['fecha_anyo'];
$anyo                                = $info_venta['anyo'];
$fecha_hora                          = $info_venta['fecha_hora'];
$usuario                             = $info_venta['usuario'];
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$cod_caja_registro_fisico_strpad = str_pad($cod_caja_registro_fisico, 5, "0", STR_PAD_LEFT);

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
$palabras_claves_doc_pdf             = $nombre_cli." - ".$cedula_cli.' - '.$cod_caja_registro_fisico;
//$mpdf = new mPDF('c','Legal');
$mpdf = new mPDF('en-GB-x','Legal','','',$margen_izq, $margen_der, $margen_inf_encabezado, $margen_sup_encabezado, $posicion_sup_encabezado, $posicion_inf_encabezado);
$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins

$codigoHTML='
<!DOCTYPE html>
<html lang="es">
<head>
<title></title>
<meta charset="utf-8" />
<title>'."Venta: ".$cod_caja_registro_fisico.'</title>
</head>
<body>
<style> div { width: 800px; margin:0px; text-align:center; font-size:30px; } #barras { padding:0px; } </style>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:40pt;">
<tr>
<td align="center"><p style="font-size:40px"><strong>'.$cabecera_emp.'</strong></p></td>
</tr>
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:40pt;">
<tr>
<td align="center"><p style="font-size:35px">'.$localidad_emp.'</p></td>
</tr>
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:40pt;">
<tr>
<td align="center"><p style="font-size:35px">NIT: '.$nit_emp.'</p></td>
</tr>
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:40pt;">
<tr>
<td align="center"><p style="font-size:35px">DIRECCION: '.$direccion_emp.'</p></td>
</tr>
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:40pt;">
<tr>
<td align="center"><p style="font-size:35px">TELEFONO: '.$telefono_emp.'</p></td>
</tr>
</table>


<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:10pt;">
<tr>
<td align="center">-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
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
<td align="center"><strong><p style="font-size:47px">T.FISICO</p></strong></td>
<td align="center"><strong><p style="font-size:47px">T.SISTEMA</p></strong></td>
<td align="center"><strong><p style="font-size:47px">FECHA</p></strong></td>
<td align="center"><strong><p style="font-size:47px">CAJA</p></strong></td>
</tr>
<tr>
<td align="center" colspan="4">-----------------------------------------------------------------------------------------------------------</td>
</tr>
';
$codigoHTML.='
<tr>
<td align="center"><p style="font-size:47px;"><strong>'.number_format($total_ventas_fisico, 0, ",", ".").'</strong></p></td>
<td align="center"><p style="font-size:47px;"><strong>'.number_format($total_ventas_sistema, 0, ",", ".").'</strong></p></td>
<td align="center"><p style="font-size:47px;"><strong>'.$fecha_anyo.' - '.$fecha_hora.'</strong></p></td>
<td align="center"><p style="font-size:47px;"><strong>'.$usuario.'</strong></p></td>
</tr>
';
$codigoHTML.='
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:10pt;">
<tr>
<td align="center">-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
</tr>
</table>

<table align="center" border="0" width="50%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:35pt;">
  <tr>
    <td align="left"><p style="font-size:40px"><strong>DIFERENCIA</strong></p></td>
    <td align="right"><p style="font-size:45px"><strong>'.number_format($total_ventas_fisico - $total_ventas_sistema, 0, ",", ".").'</strong></p></td>
  </tr>
</table>

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:10pt;">
<tr>
<td align="center">-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
</tr>
</table>

';
$codigoHTML.="

</body>
</html>";


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
$nombre_archivo = 'Factura_de_Venta_'.$cod_caja_registro_fisico;
$mpdf->output($nombre_archivo, 'I');
exit;
?>