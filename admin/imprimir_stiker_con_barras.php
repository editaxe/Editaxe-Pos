<?php
require_once('mpdf/mpdf.php');
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
date_default_timezone_set("America/Bogota");
//-------------------------------------------------------------------------------------------------------------------//
//-------------------------------------------------------------------------------------------------------------------//
$cuenta_actual = addslashes($_SESSION['usuario']);
$cod_factura = intval($_GET['cod_factura']);
$proveedor = addslashes($_GET['proveedor']);

$sql_sum_und = "SELECT SUM(unidades_total) AS sum_unidades_total FROM facturas_cargadas_stiker WHERE cod_factura = '$cod_factura'";
$consulta_sum_und = mysql_query($sql_sum_und, $conectar) or die(mysql_error());
$datos_sum_und = mysql_fetch_assoc($consulta_sum_und);

$sum_unidades_total = $datos_sum_und['sum_unidades_total'];
$smtr = 0;

$mostrar_datos_sql = "SELECT unidades_total, nombre_productos, cod_productos, fecha_anyo, fecha, fecha_mes, cod_interno 
FROM facturas_cargadas_stiker WHERE cod_factura = '$cod_factura' ORDER BY cod_facturas_cargadas_stiker ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total_resultados = mysql_num_rows($consulta);

$codigoHTML='<!DOCTYPE html><html lang="es"><head><title>'."Stiker_Factura_No_".$cod_factura.'_Proveedor_'.$proveedor.'</title><meta charset="utf-8" /></head>
<style>body { font-family: sans-serif; font-size: 9pt; } .barcode { padding: 1.5mm; margin: 0; vertical-align: top; color: #000000; } </style>
</head><body><div style="text-align: center;"><table width="100%"><tr>';

while ($datos = mysql_fetch_assoc($consulta)) {

$unidades_total = $datos['unidades_total'];
$nombre_productos = $datos['nombre_productos'];
$cod_productos = $datos['cod_productos'];
$fecha_anyo = ($datos['fecha_anyo']);
$data_fecha = explode('/', $fecha_anyo);
$dia = $data_fecha[0];
$mes = $data_fecha[1];
$anyo = $data_fecha[2];
$fecha_anyo_ym = $mes.$anyo;
$fecha_anyo_seg = $data_fecha[0];
$fecha_ymd = strtotime($datos['fecha']);
$fecha = date("mY", $fecha_ymd);
$fecha_mes = $datos['fecha_mes'];
$cod_interno = $datos['cod_interno'];

for ($i=0; $i < $unidades_total; $i++) {

$smtr++;

if ($smtr%4 == 0 && $smtr <= $sum_unidades_total) {
$codigoHTML.='
<tr>$smtr</tr>
';
}
$codigoHTML.='<td align="center">'.$nombre_productos.'&nbsp;&nbsp;<br>
<barcode code="'.$cod_productos.'" type="EAN13"  size="1" height="0.6"/><br>
'.$cod_productos.'</td><br><br>';
}
}
$codigoHTML.='</tr></table></div></body></html>';
/*
A4-L = horizontal
A4 = carta
A5 = extralarga
A5-L = horizontal
Letter = oficio
*/
$mpdf=new mPDF('','','','',5,5,5,5,0,0);
$mpdf->mirrorMargins = 1;
$mpdf->SetDisplayMode('fullpage');
$mpdf->writeHTML(utf8_encode($codigoHTML));
$nombre_archivo = 'Stiker_Con_Barras_Factura_No_'.$cod_factura.'_Proveedor_'.$proveedor.'.pdf';
$mpdf->output($nombre_archivo, 'I');
exit;
?>