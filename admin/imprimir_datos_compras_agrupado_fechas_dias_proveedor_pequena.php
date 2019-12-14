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

$fecha_ini          = intval($_GET['fecha_ini']);
$fecha_fin          = intval($_GET['fecha_fin']);

$fecha_inicial      = date("d/m/Y", $fecha_ini);
$fecha_final        = date("d/m/Y", $fecha_fin);

$campo              = addslashes($_GET['campo']);

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

if ($fecha_ini <> '') {
$mostrar_datos_sql = "SELECT Sum(facturas_cargadas_inv.precio_compra_con_descuento) As total_venta_contado, 
proveedores.nombre_proveedores, proveedores.identificacion_proveedores, facturas_cargadas_inv.fecha, facturas_cargadas_inv.fecha_anyo, 
Sum(((facturas_cargadas_inv.precio_compra_con_descuento/((facturas_cargadas_inv.iva/100)+(100/100)))*(facturas_cargadas_inv.iva/100))) As sum_iva 
FROM proveedores RIGHT JOIN facturas_cargadas_inv ON proveedores.cod_proveedores = facturas_cargadas_inv.cod_proveedores
WHERE (facturas_cargadas_inv.fecha BETWEEN '$fecha_ini' AND '$fecha_fin') GROUP BY facturas_cargadas_inv.cod_proveedores ORDER BY proveedores.nombre_proveedores ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
//-------------------------------------------- CALCULOS PARA LOS TOTALES DE LAS VENTAS POR CONTADO --------------------------------------------//
$mostrar_datos_sql_venta_contado = "SELECT Sum(facturas_cargadas_inv.precio_compra_con_descuento) As total_venta_contado_smtr 
FROM proveedores RIGHT JOIN facturas_cargadas_inv ON proveedores.cod_proveedores = facturas_cargadas_inv.cod_proveedores
WHERE (facturas_cargadas_inv.fecha BETWEEN '$fecha_ini' AND '$fecha_fin')";
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
<title>'."Balance: ".$fecha_inicial.'</title>
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
<td align="center"><p style="font-size:25px"><strong>REPORTE COMPRAS POR PROVEEDOR DE ['.$fecha_inicial.'] A ['.$fecha_final.']</strong></td>
</tr>
</table>
<!-- ------------------------------------------------------------------------------------------------------------------ -->
<!-- ------------------------------------------------------------------------------------------------------------------ -->
<table align="center" border="1" width="90%" cellspacing="0" style="font-family: Mono; font-size:25pt;">
<tr>
<td align="center"><strong>NIT</strong></td>
<td align="center"><strong>PROVEEDOR</strong></td>
<td align="center"><strong>TOTAL</strong></td>
<td align="center"><strong>IVA</strong></td>
</tr>';
//-----------------------------------------------------------------------------------------------------------------//
//-----------------------------------------------------------------------------------------------------------------//
while ($datos = mysql_fetch_assoc($consulta)) {
$identificacion_proveedores = $datos['identificacion_proveedores'];
$nombre_proveedores = $datos['nombre_proveedores'];
$fecha_anyo = $datos['fecha_anyo'];
$total_venta_contado = $datos['total_venta_contado'];
$sum_iva = $datos['sum_iva'];

$codigoHTML.='
<tr>
<td align="left"><strong><p style="font-size:25px">'.$identificacion_proveedores.'</p></strong></td>
<td align="left"><strong><p style="font-size:25px">'.$nombre_proveedores.'</p></strong></td>
<td align="right"><strong><p style="font-size:25px">'.number_format($total_venta_contado, 0, ",", ".").'</p></strong></td>
<td align="right"><strong><p style="font-size:25px">'.number_format($sum_iva, 0, ",", ".").'</p></strong></td>
</tr>';
} 
$codigoHTML.='
</table>

</table>
<br>
<table align="center" border="1" width="90%" cellspacing="0" style="font-family: Mono; font-size:30pt;">
<tr>
<td align="center"><strong><p style="font-size:40px">TOTAL COMPRA</strong></td>
<td align="center"><strong><p style="font-size:40px">'.number_format($total_venta_contado_smtr, 0, ",", ".").'</p></strong></td>
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