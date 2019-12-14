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
include ("../seguridad/seguridad_diseno_plantillas.php");
date_default_timezone_set("America/Bogota");
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$cod_factura                          = intval($_GET['cod_factura']);
$pagina                               = addslashes($_GET['pagina']);
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
/*
$obtener_info_mquina_impr = "SELECT nombre_maquina, nombre_impresora1 FROM administrador WHERE cuenta = '$cuenta_actual'";
$resultado_info_mquina_impr = mysql_query($obtener_info_mquina_impr, $conectar);
$info_mquina_impr = mysql_fetch_assoc($resultado_info_mquina_impr);

$nombre_maquina_usuario               = $info_mquina_impr['nombre_maquina'];
$nombre_impresora1_usuario            = $info_mquina_impr['nombre_impresora1'];
*/
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
/*
$obtener_info_fact = "SELECT * FROM info_impuesto_facturas WHERE cod_factura = '$cod_factura'";
$resultado_info_fact = mysql_query($obtener_info_fact, $conectar);
$info_fact = mysql_fetch_assoc($resultado_info_fact);

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
$obtener_info_fact_venta = "SELECT fecha_anyo, fecha_hora, vendedor, cod_clientes, nombre_peso, tipo_pago FROM ventas WHERE cod_factura = '$cod_factura'";
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
Sum(vlr_total_venta*(descuento_ptj/100)) AS total_desc FROM ventas WHERE cod_factura = '$cod_factura'";
$consulta_temporal = mysql_query($suma_temporal, $conectar) or die(mysql_error());
$suma = mysql_fetch_assoc($consulta_temporal);

$subtotal_base                       = ($suma['subtotal_base']);
$total_desc                          = ($suma['total_desc']);
$total_iva                           = ($suma['total_iva']);
$total_venta_temp                    = ($suma['total_venta']);
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
//if ($tipo_pago == 1) { $nombre_tipo_pago = 'CONTADO'; $total_cambio = $vlr_cancelado - $total_venta_temp; } elseif ($tipo_pago == 2) { $nombre_tipo_pago = 'CREDITO'; } else { $nombre_tipo_pago = 'CONTADO'; $total_cambio = 0; }
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$cod_factura_strpad = str_pad($cod_factura, 6, "0", STR_PAD_LEFT);
?>
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-barcode.js"></script>
<link rel="stylesheet" type="text/css" href="../estilo_css/area_imprimible_invisible.css">

<script>
function printPageArea(areaID){

var cod_factura = "<?php echo $cod_factura_strpad ?>";
var estandar_barras = "code128";
var renderer = "css";

var settings = { output:renderer, bgColor: "#FFFFFF", color: "#000000", barWidth: 2, barHeight: 40, moduleSize: 5, posX: 10, posY: 20, addQuietZone: 1 };
$("#barcodeTarget").html("").show().barcode(cod_factura, estandar_barras, settings);

var printContent = document.getElementById(areaID);
document.getElementById("listo").focus();
var WinPrint = window.open('', '', 'width=400,height=1000');
WinPrint.document.write(printContent.innerHTML);
WinPrint.document.close();
WinPrint.focus();
WinPrint.print();
WinPrint.close();
}
</script>
    </head>
<body>

<center>
<table>
<td><font color='yellow' size= "+3">FACTURA NO:</font></td><td align='right'><font color='yellow' size= "+3"><?php echo $cod_factura; ?></td>
<tr></tr>
<td><font color='yellow' size= "+3">CLIENTE:</font></td><td><font color='yellow' size= "+3"><?php echo $nombre_cliente; ?></td>
<tr></tr>
<td><font color='yellow' size= "+2">SUBTOTAL:</font></td><td align='right'><font color='yellow' size= "+3"><?php echo number_format($subtotal_base, 0, ",", "."); ?></td>
<tr></tr>
<td><font color='green' size= "+2">%DESCUENTO:</font></td><td align='right'><font color='green' size= "+2"><?php echo $descuento.'%'; ?></td>
<tr></tr>
<td><font color='green' size= "+2">$DESCUENTO:</font></td><td align='right'><font color='green' size= "+2"><?php echo number_format($total_desc, 0, ",", "."); ?></td>
<tr></tr>
<td><font color='green' size= "+2">IVA:</font></td><td align='right'><font color='green' size= "+2"><?php echo number_format($total_iva, 0, ",", "."); ?></td>
<tr></tr>
<td><font color='yellow' size= "+3">TOTAL VENTA:</font></td><td align='right'><font color='yellow' size= "+3"><?php echo number_format($total_venta_temp, 0, ",", "."); ?></td>
<tr></tr>
<td><font color='yellow' size= "+3">RECIBIDO:</font></td><td align='right'><font color='yellow' size= "+3"><?php echo number_format($vlr_cancelado, 0, ",", "."); ?></td>
<tr></tr>
<td><font color='yellow' size= "+4">CAMBIO:</font></td><td align='right'><font color='yellow' size= "+4"><?php echo number_format($vlr_cancelado - $total_venta_temp, 0, ",", "."); ?></td>
<tr></tr>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../admin/<?php echo $pagina?>" id="listo"><img src="../imagenes/listo.png" alt="listo"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

<div style="width: 200px;float: center;">
<td><a href="javascript:void(0);" id="foco_btn_imprimir" onclick="printPageArea('area_imprimible_invisible')"><img src="../imagenes/imprimir_directa_pos.png" alt="imprimir"></a></td>
</div>
</table>
</center>

<!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<div id="wrapper" style="width: 98%;">

<div id="area_imprimible_invisible" style="width: 98%;text-align: center;"><div>

<table border="0" width="98%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:12pt;">
<tr>
<td style="text-align: center; width: 98%; font-family: Courier; font-size:12pt;"><img src="../imagenes/logo_empresa_factura_pos_blanco_negro.jpg" width="100px"></td>
</tr>
</table>

<table border="0" width="98%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:15pt;">
<tr>
<td style="text-align: center; width: 98%; font-family: Courier; font-size:13pt;"><strong><?php echo $cabecera_emp; ?></strong></td>
</tr>
</table>

<table border="0" width="98%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:12pt;">
<tr>
<td style="text-align: center; width: 98%; font-family: Courier; font-size:12pt;"><strong><?php echo $localidad_emp; ?></strong></td>
</tr>
</table>

<table border="0" width="98%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:12pt;">
<tr>
<td style="text-align: center; width: 98%; font-family: Courier; font-size:12pt;"><strong>NIT: <?php echo $nit_emp; ?></strong></td>
</tr>
</table>

<table border="0" width="98%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:12pt;">
<tr>
<td style="text-align: center; width: 98%; font-family: Courier; font-size:12pt;"><strong>DIRECCION: <?php echo $direccion_emp; ?></strong></td>
</tr>
</table>

<table border="0" width="98%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:12pt;">
<tr>
<td style="text-align: center; width: 98%; font-family: Courier; font-size:12pt;"><strong>TELEFONO: <?php echo $telefono_emp; ?></strong></td>
</tr>
</table>


<table border="0" width="98%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:12pt;">
<tr>
<td align="center">------------------------------------</td>
</tr>
</table>


<table border="0" width="98%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:12pt;">
  <tr>
    <td style="text-align: left; width: 98%; font-family: Courier; font-size:12pt;"><strong>FECHA: </strong></td>
    <td style="text-align: left; width: 98%; font-family: Courier; font-size:12pt;"><strong><?php echo $fecha_anyo; ?></strong></td>
  </tr>
  <tr>
    <td style="text-align: left; width: 98%; font-family: Courier; font-size:12pt;"><strong>FACTURA DE VENTA: </strong></td>
    <td style="text-align: left; width: 98%; font-family: Courier; font-size:12pt;"><strong><?php echo $cod_factura; ?></strong></td>
  </tr>
<!--
  <tr>
    <td style="text-align: left; width: 98%; font-family: Courier; font-size:12pt;"><strong>IPO DE PAGO: </strong></td>
    <td style="text-align: left; width: 98%; font-family: Courier; font-size:12pt;"><strong><?php echo $nombre_tipo_pago; ?></strong></td>
  </tr>
-->
  <tr>
    <td style="text-align: left; width: 98%; font-family: Courier; font-size:12pt;"><strong>NIT CLIENTE: </strong></td>
    <td style="text-align: left; width: 98%; font-family: Courier; font-size:12pt;"><strong><?php echo ($cedula_cli); ?></strong></td>
  </tr>
  <tr>
    <td style="text-align: left; width: 98%; font-family: Courier; font-size:12pt;"><strong>NOMBRE CLIENTE: </strong></td>
    <td style="text-align: left; width: 98%; font-family: Courier; font-size:12pt;"><strong><?php echo utf8_decode($nombre_cliente); ?></strong></td>
  </tr>
  <tr>
    <td style="text-align: left; width: 98%; font-family: Courier; font-size:12pt;"><strong>VENDEDOR (A): </strong></td>
    <td style="text-align: left; width: 98%; font-family: Courier; font-size:12pt;"><strong><?php echo $vendedor; ?></strong></td>
  </tr>
</table>

<table border="0" width="98%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:12pt;">
<tr>
<td align="center">------------------------------------</td>
</tr>
</table>

<table border="0" width="298px" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:12pt;">
<tr>
<td align="center" width="298px" colspan="4"></td>
</tr>
<tr>
<td style="text-align: left; width: 5px; font-family: Courier; font-size:12pt;"><strong>CANT</strong></td>
<td style="text-align: left; width: 30px; font-family: Courier; font-size:12pt;"><strong>ARTICULO</strong></td>
<td style="text-align: left; width: 2px; font-family: Courier; font-size:12pt;"><strong>P.UNIT</strong></td>
<td style="text-align: left; width: 2px; font-family: Courier; font-size:12pt;"><strong>P.TOTAL</strong></td>
</tr>
<tr>
<td align="center" width="298px" colspan="4">------------------------------------</td>
</tr>
<?php
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
?>
<tr>
<td style="text-align: center; width: 5px; font-family: Courier; font-size:12pt;"><strong><?php echo $unidades_vendidas ?></strong></td>
<td style="text-align: left; width: 30px; font-family: Courier; font-size:12pt;"><strong><?php echo $nombre_productos ?></strong></td>
<td style="text-align: right; width: 2px; font-family: Courier; font-size:12pt;"><strong><?php echo number_format($precio_venta, 0, ",", ".") ?></strong></td>
<td style="text-align: right; width: 2px; font-family: Courier; font-size:12pt;"><strong><?php echo number_format($vlr_total_venta, 0, ",", ".") ?></strong></td>
</tr>
<?php } ?>
</table>

<table border="0" width="98%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:12pt;">
<tr>
<td align="center">------------------------------------</td>
</tr>
</table>

<table border="0" width="98%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:11pt;">
  <tr>
    <td style="text-align: center; width: 98%; font-family: Courier; font-size:11pt;"><strong>SUBTOTAL</strong></p></td>
    <td style="text-align: right; width: 98%; font-family: Courier; font-size:11pt;"><strong><?php echo number_format($subtotal_base, 0, ",", ".") ?></strong></td>
  </tr>
  <tr>
    <td style="text-align: center; width: 98%; font-family: Courier; font-size:11pt;"><strong>%DESC</strong></p></td>
    <td style="text-align: right; width: 98%; font-family: Courier; font-size:11pt;"><strong><?php echo number_format($descuento, 0, ",", ".") ?></strong></td>
  </tr>
  <tr>
    <td style="text-align: center; width: 98%; font-family: Courier; font-size:11pt;"><strong>$DESC</strong></p></td>
    <td style="text-align: right; width: 98%; font-family: Courier; font-size:11pt;"><strong><?php echo number_format($total_desc, 0, ",", ".") ?></strong></td>
  </tr>
  <tr>
    <td style="text-align: center; width: 98%; font-family: Courier; font-size:11pt;"><strong>IVA</strong></td>
    <td style="text-align: right; width: 98%; font-family: Courier; font-size:11pt;"><strong><?php echo number_format($total_iva, 0, ",", ".") ?></strong></td>
  </tr>
  <tr>
    <td style="text-align: center; width: 98%; font-family: Courier; font-size:12pt;"><strong>TOTAL</strong></td>
    <td style="text-align: right; width: 98%; font-family: Courier; font-size:12pt;"><strong><?php echo number_format($total_venta_temp, 0, ",", ".") ?></strong></td>
  </tr>
</table>

<table border="0" width="98%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:12pt;">
<tr>
<td align="center">------------------------------------</td>
</tr>
</table>

<table border="0" width="98%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:12pt;">
  <tr>
    <td style="text-align: center; width: 98%; font-family: Courier; font-size:12pt;"><strong>FACTURA POR COMPUTADOR</strong></td>
  </tr>
  <tr>
    <td style="text-align: center; width: 98%; font-family: Courier; font-size:12pt;"><strong>RES DIAN: <?php echo $res_emp ?> DESDE <?php echo $prefijo_resolucion_emp ?> <?php echo str_pad($res1_emp, 6, "0", STR_PAD_LEFT) ?> AL <?php echo $prefijo_resolucion_emp ?> <?php echo $res2_emp ?></strong></td>
  </tr>
  <tr>
    <td style="text-align: center; width: 98%; font-family: Courier; font-size:12pt;"><strong>REGIMEN <?php echo $regimen_emp ?></strong></td>
  </tr>
</table>

<table border="0" width="98%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:12pt;">
  <tr>
    <td style="text-align: center; width: 98%; font-family: Courier; font-size:12pt;">**************************************</td>
  </tr>
  <tr>
    <td style="text-align: center; width: 98%; font-family: Courier; font-size:12pt;"><strong>Muchas gracias por su compra</strong></td>
  </tr>
  <tr>
    <td style="text-align: center; width: 98%; font-family: Courier; font-size:12pt;">**************************************</td>
  </tr>
</table>

<table border="0" width="98%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:8pt;">
  <tr>
    <td style="text-align: center; width: 98%; font-family: Courier; font-size:8pt;"><strong><?php echo $desarrollador_emp ?> : <?php echo $pag_desarrollador_emp ?></strong></td>
  </tr>
  <tr>
    <td style="text-align: center; width: 98%;" id="barcodeTarget" class="barcodeTarget"></td>
    <!--<td style="text-align: center; width: 98%;" id="barcodeTarget" class="barcodeTarget"><div id="barcodeTarget" class="barcodeTarget"></div></td>-->
  </tr>
  <tr>
    <td style="text-align: center; width: 98%; font-family: Courier; font-size:8pt;"><strong><?php echo $fecha.$hora.'-'.$cod_factura.'-'.$fecha_venta_ymd.$hora_venta_his ?></strong>_imp_nrm</td>
  </tr>
</table>

		</div>
	</div>
</div>
<!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<script>
window.onload = function() {
document.getElementById("foco_btn_imprimir").focus();
}
</script>
</body>
</html>