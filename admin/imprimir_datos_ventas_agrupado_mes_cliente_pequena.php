<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
date_default_timezone_set("America/Bogota");

if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);

$fecha = addslashes($_GET['fecha']);
$campo = addslashes($_GET['campo']);

$obtener_informacion = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consultar_informacion = mysql_query($obtener_informacion, $conectar) or die(mysql_error());
$matriz_informacion = mysql_fetch_assoc($consultar_informacion);

$cabecera_emp = $matriz_informacion['cabecera'];
$localidad_emp = $matriz_informacion['localidad'];
$direccion_emp = $matriz_informacion['direccion'];
$nit_emp = $matriz_informacion['nit'];
$telefono_emp = $matriz_informacion['telefono'];

$propietario_nombres_apellidos_emp  = $matriz_informacion['propietario_nombres_apellidos'];
$iva_global = $matriz_informacion['iva_global'];

if ($fecha <> '') {
$mostrar_datos_sql = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As total_venta_contado, 
clientes.nombres, clientes.apellidos, clientes.cedula, ventas.cod_ventas, ventas.cod_productos, 
ventas.nombre_productos, ventas.tipo_pago, ventas.unidades_vendidas, ventas.precio_venta,
ventas.vlr_total_venta, ventas.iva, ventas.fecha_mes, ventas.fecha_anyo, ventas.fecha_mes, ventas.fecha, ventas.fecha_hora,
ventas.vendedor, ventas.cod_clientes, ventas.cod_factura FROM clientes RIGHT JOIN ventas ON clientes.cod_clientes = ventas.cod_clientes
WHERE ((ventas.fecha_mes)='$fecha') GROUP BY ventas.cod_clientes ORDER BY clientes.nombres ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
//-------------------------------------------- CALCULOS PARA LOS TOTALES DE LAS VENTAS POR CONTADO --------------------------------------------//
$mostrar_datos_sql_venta_contado = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As total_venta_contado_smtr, 
sum(vlr_total_compra) As vlr_total_compra, Sum(((precio_venta/((iva/100)+(100/100)))*(iva/100))*unidades_vendidas) As sum_iva 
FROM clientes RIGHT JOIN ventas ON clientes.cod_clientes = ventas.cod_clientes
WHERE ((ventas.fecha_mes)='$fecha')";
$consulta_venta_contado = mysql_query($mostrar_datos_sql_venta_contado, $conectar) or die(mysql_error());
$matriz_venta_contado = mysql_fetch_assoc($consulta_venta_contado);

$total_venta_contado_smtr = $matriz_venta_contado['total_venta_contado_smtr'];
}
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$codigoHTML='
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>'."Balance: ".$fecha.'</title>
</head>
<body>
<!-- ------------------------------------------------------------------------------------------------------------------ -->
<!-- ------------------------------------------------------------------------------------------------------------------ -->
<table align="center" border="1" width="90%" cellspacing="0" cellpadding="0" style="font-family: Mono; font-size:30pt;">
<tr>
<td align="center"><p style="font-size:40px"><strong>'.$cabecera_emp.' - '.$localidad_emp.'
<p style="font-size:40px">Res: '.$res_emp.', Del '.$res1_emp.' Al '.$res2_emp.' 
<br>
Direccion: '.$direccion_emp.' - Tel: '.$telefono_emp.'
<br>'.$nit_emp.'</td>
</strong>
</tr>
</table>
<!-- ------------------------------------------------------------------------------------------------------------------ -->
<!-- ------------------------------------------------------------------------------------------------------------------ -->
<table align="center" border="0" width="90%" cellspacing="0" cellpadding="0" style="font-family: Mono; font-size:30pt;">
<hr>
<tr>
<td align="center"><p style="font-size:25px"><strong>REPORTE VENTAS POR CLIENTES MENSUAL ['.$fecha.']</strong></td>
</tr>
</table>
<!-- ------------------------------------------------------------------------------------------------------------------ -->
<!-- ------------------------------------------------------------------------------------------------------------------ -->
<table align="center" border="1" width="90%" cellspacing="0" style="font-family: Mono; font-size:25pt;">
<tr>
<td align="center"><strong>NIT</strong></td>
<td align="center"><strong>CLIENTE</strong></td>
<td align="center"><strong>TOTAL</strong></td>
<td align="center"><strong>MES</strong></td>
</tr>';
while ($datos = mysql_fetch_assoc($consulta)) {
$cedula = $datos['cedula'];
$nombres = $datos['nombres'];
$apellidos = $datos['apellidos'];
$fecha_mes = $datos['fecha_mes'];
$total_venta_contado = $datos['total_venta_contado'];

$codigoHTML.='
<tr>
<td align="left"><strong><p style="font-size:25px">'.$cedula.'</p></strong></td>
<td align="left"><strong><p style="font-size:25px">'.$nombres.' '.$apellidos.'</p></strong></td>
<td align="right"><strong><p style="font-size:25px">'.number_format($total_venta_contado, 0, ",", ".").'</p></strong></td>
<td align="right"><strong><p style="font-size:25px">'.$fecha_mes.'</p></strong></td>
</tr>';
} 
$codigoHTML.='
</table>

<br>
<table align="center" border="1" width="90%" cellspacing="0" style="font-family: Mono; font-size:30pt;">
<tr>
<td align="center"><strong><p style="font-size:40px">TOTAL VENTA</strong></td>
<td align="center"><strong><p style="font-size:40px">'.number_format($total_venta_contado_smtr, 0, ",", ".").'</p></strong></td>
</tr>
</table>

<br><br>

<table align="center" border="1" width="90%" cellspacing="0" style="font-family: Mono; font-size:30pt;">
<tr>
<td align="center"><strong><p style="font-size:40px">ABONOS MES: '.$fecha.'</strong></td>
</tr>
</table>

<table align="center" border="1" width="90%" cellspacing="0" style="font-family: Mono; font-size:25pt;">
<tr>
<td align="center"><strong>NIT</strong></td>
<td align="center"><strong>CLIENTE</strong></td>
<td align="center"><strong>ABONO</strong></td>
<td align="center"><strong>FECHA</strong></td>
</tr>';
$mostrar_datos_abonos = "SELECT clientes.cedula, clientes.nombres, clientes.apellidos, 
cuentas_cobrar_abonos.abonado, cuentas_cobrar_abonos.fecha_mes, cuentas_cobrar_abonos.fecha_pago
FROM clientes RIGHT JOIN cuentas_cobrar_abonos ON clientes.cod_clientes = cuentas_cobrar_abonos.cod_clientes
WHERE (cuentas_cobrar_abonos.fecha_mes='$fecha') ORDER BY cuentas_cobrar_abonos.fecha_invert DESC";
$consulta_abonos = mysql_query($mostrar_datos_abonos, $conectar) or die(mysql_error());

while ($datos_abonos = mysql_fetch_assoc($consulta_abonos)) {
$cedula = $datos_abonos['cedula'];
$nombres = $datos_abonos['nombres'];
$apellidos = $datos_abonos['apellidos'];
$fecha_mes = $datos_abonos['fecha_mes'];
$abonado = $datos_abonos['abonado'];
$fecha_pago = $datos_abonos['fecha_pago'];
$total_abono_smtr = $abonado + $total_abono_smtr;

$codigoHTML.='
<tr>
<td align="left"><strong><p style="font-size:25px">'.$cedula.'</p></strong></td>
<td align="left"><strong><p style="font-size:30px">'.$nombres.' '.$apellidos.'</p></strong></td>
<td align="right"><strong><p style="font-size:30px">'.number_format($abonado, 0, ",", ".").'</p></strong></td>
<td align="right"><strong><p style="font-size:30px">'.$fecha_pago.'</p></strong></td>
</tr>';
} 
$codigoHTML.='
</table>

<br>
<table align="center" border="1" width="90%" cellspacing="0" style="font-family: Mono; font-size:30pt;">
<tr>
<td align="center"><strong><p style="font-size:40px">TOTAL ABONO</strong></td>
<td align="center"><strong><p style="font-size:40px">'.number_format($total_abono_smtr, 0, ",", ".").'</p></strong></td>
</tr>
</table>

</body>
</html>';

include_once('mpdf/mpdf.php');
//$mpdf = new mPDF('c', 'A4');
//$mpdf = new mPDF('en-GB-x','A4','','',5,5,5,5,0,0);
//$mpdf = new mPDF('','', 0, '', 15, 15, 16, 16, 9, 9, 'L');
//$mpdf = new mPDF('utf-8', 'A4');
//$mpdf=new mPDF('UTF-8-s','');
$margen_izq = '1';
$margen_der = '1';
$margen_inf_encabezado = '1';
$margen_sup_encabezado = '1';
$posicion_sup_encabezado = '1';
$posicion_inf_encabezado = '1';
$mpdf = new mPDF('en-GB-x','Legal','','',$margen_izq, $margen_der, $margen_inf_encabezado, $margen_sup_encabezado, $posicion_sup_encabezado, $posicion_inf_encabezado);
$mpdf->mirrorMargins = 1;
$mpdf->SetDisplayMode(50);
$mpdf->writeHTML(utf8_encode($codigoHTML));
$nombre_archivo = 'Factura_de_Venta_'.$cod_factura;
$mpdf->output($nombre_archivo, 'I');
?>