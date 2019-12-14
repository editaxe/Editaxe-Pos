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

$sql_sum_und = "SELECT SUM(unidades_total) AS sum_unidades_total FROM stiker_productos_estante WHERE cod_factura = '$cod_factura'";
$consulta_sum_und = mysql_query($sql_sum_und, $conectar) or die(mysql_error());
$datos_sum_und = mysql_fetch_assoc($consulta_sum_und);

$sum_unidades_total = $datos_sum_und['sum_unidades_total'];
$smtr = 0;

$mostrar_datos_sql = "SELECT * FROM stiker_productos_estante WHERE cod_factura = '$cod_factura' ORDER BY cod_stiker_productos_estante ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total_resultados = mysql_num_rows($consulta);

$codigoHTML='<!DOCTYPE html><html lang="es"><head><title>'."Stiker_Factura_No_".$cod_factura.'</title><meta charset="utf-8" /></head>
<style>body { font-family: arial; font-size: 14pt; } .barcode { padding: 1.5mm; margin: 0; vertical-align: top; color: #000000; } </style>
</head><body>
<table border="1" width="100%"><tr>';

while ($datos = mysql_fetch_assoc($consulta)) {

$unidades_total = $datos['unidades_total'];
$nombre_productos = $datos['nombre_productos'];
$cod_productos = $datos['cod_productos'];
$precio_venta = $datos['precio_venta'];
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

if ($smtr%2 == 0 && $smtr <= $sum_unidades_total) {
$codigoHTML.='
<tr>$smtr</tr>
';
}

if (strlen($cod_productos) >= 6) { 
$estandar_barras = "EAN13"; 

$codigoHTML.='
<td align="center" width="200px" height="150px" cellspacing="1" cellpadding="1"><font size="6" color="orange"><strong>'.$nombre_productos.'</strong></font><br>
<font size="5" color="orange"><strong>COD:'.$cod_productos.'</strong></font><br><br>
<font size="2" color="orange">PRECIO VENTA: </font> <font size="7" color="orange"><strong><u>'.number_format($precio_venta, 0, ",", ".").'</u></strong></font></td>
';
} else { 
$estandar_barras = "C39"; 

$codigoHTML.='
<td align="center" width="200px" height="150px" cellspacing="1" cellpadding="1"><font size="6" color="orange"><strong>'.$nombre_productos.'</strong></font><br>
<font size="5" color="orange"><strong>COD:'.$cod_productos.'</strong></font><br><br>
<font size="2" color="orange">PRECIO VENTA: </font> <font size="7" color="orange"><strong><u>'.number_format($precio_venta, 0, ",", ".").'</u></strong></font></td>
';
}



}
}
$codigoHTML.='</tr></table></body></html>';
/*
A4-L = horizontal
A4 = carta
A5 = extralarga
A5-L = horizontal
Letter = oficio
*/
$margen_izq = '4';
$margen_der = '4';
$margen_inf_encabezado = '4';
$margen_sup_encabezado = '4';
$posicion_sup_encabezado = '4';
$posicion_inf_encabezado = '4';
$mpdf = new mPDF('en-GB-x','legal','','',$margen_izq, $margen_der, $margen_inf_encabezado, $margen_sup_encabezado, $posicion_sup_encabezado, $posicion_inf_encabezado);
$mpdf->mirrorMargins = 5;
$mpdf->SetDisplayMode('fullpage');
$mpdf->writeHTML(utf8_encode($codigoHTML));
$nombre_archivo = 'Stiker_Sin_Barras_Factura_No_'.$cod_factura.'.pdf';
$mpdf->output($nombre_archivo, 'I');
exit;
?>