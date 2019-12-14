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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$hora = date("H:i:s");
//--------------------------------------------CUANDO LLEGUE EL DATO DE LA FECHA--------------------------------------------//
//--------------------------------------------CUANDO LLEGUE EL DATO DE LA FECHA--------------------------------------------//
if (isset($_POST['fecha_ini']) <> NULL) {

$fecha_ini          = intval($_POST['fecha_ini']);
$fecha_fin          = intval($_POST['fecha_fin']);

$fecha_inicial      = date("d/m/Y", $fecha_ini);
$fecha_final        = date("d/m/Y", $fecha_fin);
//-------------------------------------------- FITRO PARA LOS DATOS DE LAS VENTAS --------------------------------------------//
$mostrar_datos_sql = "SELECT Sum(facturas_cargadas_inv.precio_compra_con_descuento) As total_venta_contado, 
proveedores.nombre_proveedores, proveedores.identificacion_proveedores, facturas_cargadas_inv.fecha, 
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
//-------------------------------------------- CALCULO PARA LA CAJA --------------------------------------------//
$campo = 'fecha';
//-------------------------------------------- FIN DEL ISSET FECHA --------------------------------------------//
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<center>
<br>
<table align="center">
<td nowrap align="right"><font color='yellow' size='+3'>REPORTE COMPRAS DIA</font><br></td>
</table>

<br>
<?php require_once('../admin/menu_reporte_compra.php'); ?>
<br>

<form method="post" name="formulario" action="">
<table align="center">
<tr>
<td>FECHA INI: <select name="fecha_ini" id="fecha_ini" required>
<?php if (isset($fecha_ini)) { echo "<option value='' ></option>";
} else { echo  "<option value='' selected></option>"; }
$consulta2_sql = "SELECT fecha, fecha_anyo FROM facturas_cargadas_inv GROUP BY fecha ORDER BY fecha DESC";
$consulta2 = mysql_query($consulta2_sql, $conectar) or die(mysql_error());
while ($datos2 = mysql_fetch_assoc($consulta2)) {
if(isset($fecha_ini) and $fecha_ini == $datos2['fecha']) {
$seleccionado = "selected"; } else { $seleccionado = ""; }
$codigo = $datos2['fecha'];
$nombre = $datos2['fecha_anyo'];
echo "<option value='".$codigo."' $seleccionado >".$nombre."</option>"; } ?></select></td>

<td>FECHA FIN: <select name="fecha_fin" id="fecha_fin" required>
<?php if (isset($fecha_fin)) { echo "<option value='' ></option>";
} else { echo  "<option value='' selected></option>"; }
$consulta2_sql = "SELECT fecha, fecha_anyo FROM facturas_cargadas_inv GROUP BY fecha ORDER BY fecha DESC";
$consulta2 = mysql_query($consulta2_sql, $conectar) or die(mysql_error());
while ($datos2 = mysql_fetch_assoc($consulta2)) {
if(isset($fecha_fin) and $fecha_fin == $datos2['fecha']) {
$seleccionado = "selected"; } else { $seleccionado = ""; }
$codigo = $datos2['fecha'];
$nombre = $datos2['fecha_anyo'];
echo "<option value='".$codigo."' $seleccionado >".$nombre."</option>"; } ?></select></td>

<td bordercolor="1"><br><input type="submit" id="boton1" value="Consultar"></td>
</tr>
</table>
</form>
</center>

<?php if (isset($_POST['fecha_ini']) <> NULL) { ?>
<center>
<fieldset><legend><font color='yellow' size='+3'>TOTAL COMPRAS DEL <?php echo $fecha_inicial ?> AL <?php echo $fecha_final ?></font></legend>
<table width='100%'>
<tr><td align="center"><strong>TOTAL COMPRAS</td></tr>
<tr><td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($total_venta_contado_smtr, 0, ",", "."); ?></font></td></tr>
<table>
<center>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/reporte_compra_proveedor_fechas_dia_xlsx.php?fecha_ini=<?php echo $fecha_ini?>&fecha_fin=<?php echo $fecha_fin?>" target="_blank"><img src=../imagenes/excel.png alt="imprimir"></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/imprimir_datos_compras_agrupado_fechas_dias_proveedor_pequena.php?fecha_ini=<?php echo $fecha_ini?>&fecha_fin=<?php echo $fecha_fin?>&campo=<?php echo $campo?>" target="_blank"><img src=../imagenes/imprimir_1.png alt="imprimir"></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</table>
</fieldset>
<br>
<fieldset><legend><font color='yellow' size='+3'>TOTAL COMPRAS DEL <?php echo $fecha_inicial ?> AL <?php echo $fecha_final ?></font></legend>
<table align="center" width='50%'>
<tr>
<td align="center"><strong>NIT</strong></td>
<td align="center"><strong>PROVEEDOR</strong></td>
<td align="center"><strong>TOTAL COMPRA</strong></td>
<td align="center"><strong>TOTAL IVA</strong></td>
</tr>
<?php 
while ($datos = mysql_fetch_assoc($consulta)) {
$identificacion_proveedores = $datos['identificacion_proveedores'];
$nombre_proveedores = $datos['nombre_proveedores'];
$fecha_mes = $datos['fecha_mes'];
$total_venta_contado = $datos['total_venta_contado'];
$sum_iva = $datos['sum_iva'];
?>
<tr>
<td ><?php echo $identificacion_proveedores; ?></td>
<td ><?php echo $nombre_proveedores; ?></td>
<td align="right"><?php echo number_format($total_venta_contado, 0, ",", "."); ?></td>
<td align="right"><?php echo number_format($sum_iva, 0, ",", "."); ?></td>
<td align="center"><?php echo $fecha_mes; ?></td>
</tr>
<?php } ?>
</table>
</fieldset>
<?php } else { } ?>
</center>
</body>
</html>