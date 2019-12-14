<?php error_reporting(E_ALL ^ E_NOTICE);
require_once("menu_inventario.php");

if (isset($_GET['campo'])) {
$campo = addslashes($_GET['campo']);
$ord = addslashes($_GET['ord']);
} else {
$campo = 'nombre_productos';
$ord = 'asc';
}

$mostrar_datos_sql = "SELECT * FROM productos ORDER BY $campo $ord";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());

$total_productos_inventario = "SELECT * FROM productos";
$consulta_inventario = mysql_query($total_productos_inventario, $conectar) or die(mysql_error());
$total_productos = mysql_num_rows($consulta_inventario);

$calculos_inventario = "SELECT Sum(precio_costo * unidades_faltantes) As tot_precio_costo, Sum(precio_venta * unidades_faltantes) As tot_precio_venta, 
Sum((unidades_faltantes / unidades) * precio_compra) As tot_precio_compra, Sum((unidades_faltantes / unidades) * vlr_total_venta) As tot_vlr_total_venta FROM productos";
$consulta_calculos_inventario = mysql_query($calculos_inventario, $conectar) or die(mysql_error());
$matriz_inventario = mysql_fetch_assoc($consulta_calculos_inventario);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>  
<td align="center"><font color="yellow" size="+2"><strong>INVENTARIO DE PRODUCTOS</font></td>
<br><br>
<!-- Total mercancia venta y utilidad mes -->
<table width='60%'  border='1'>
<td align="center">TOTAL PRODUCTOS</td>
<td align="center">TOTAL COSTO MET</td>
<td align="center">TOTAL VENTA MET</td>
<td align="center">TOTAL COSTO UND</td>
<td align="center">TOTAL VENTA UND</td>
<tr>
<td align="center"><font color="yellow" size="+1"><strong><?php echo $total_productos; ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($matriz_inventario['tot_precio_costo'], 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($matriz_inventario['tot_precio_venta'], 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($matriz_inventario['tot_precio_compra'], 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($matriz_inventario['tot_vlr_total_venta'], 0, ",", "."); ?></font></td>
</tr>
</table>
<br>
<table width='100%' border='1'>
<tr>
<?php
if ($ord == 'desc') {?>
<td align="center">C&Oacute;DIGO <br><a href="../admin/inventario_productos_no_editable.php?campo=cod_productos_var&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">PRODUCTO <br><a href="../admin/inventario_productos_no_editable.php?campo=nombre_productos&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">PRE <br><a href="../admin/inventario_productos_no_editable.php?campo=unidades&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">T.UN.MET <br><a href="../admin/inventario_productos_no_editable.php?campo=unidades_faltantes&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">MET <br><a href="../admin/inventario_productos_no_editable.php?campo=detalles&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">P.COSTO.MET <br><a href="../admin/inventario_productos_no_editable.php?campo=precio_costo&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">P.VENTA.MET <br><a href="../admin/inventario_productos_no_editable.php?campo=precio_venta&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">P.COSTO.UND <br><a href="../admin/inventario_productos_no_editable.php?campo=precio_compra&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">P.VENTA.UND <br><a href="../admin/inventario_productos_no_editable.php?campo=vlr_total_venta&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<?php
} else {
?>
<td align="center">C&Oacute;DIGO <br><a href="../admin/inventario_productos_no_editable.php?campo=cod_productos_var&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">PRODUCTO <br><a href="../admin/inventario_productos_no_editable.php?campo=nombre_productos&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">PRE <br><a href="../admin/inventario_productos_no_editable.php?campo=unidades&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">T.UN.MET <br><a href="../admin/inventario_productos_no_editable.php?campo=unidades_faltantes&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">MET <br><a href="../admin/inventario_productos_no_editable.php?campo=detalles&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">P.COSTO.MET <br><a href="../admin/inventario_productos_no_editable.php?campo=precio_costo&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">P.VENTA.MET <br><a href="../admin/inventario_productos_no_editable.php?campo=precio_venta&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">P.COSTO.UND <br><a href="../admin/inventario_productos_no_editable.php?campo=precio_compra&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">P.VENTA.UND <br><a href="../admin/inventario_productos_no_editable.php?campo=vlr_total_venta&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<?php }?>
</tr>
<?php 
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_productos = $datos['cod_productos'];
$cod_productos_var = $datos['cod_productos_var'];
$nombre_productos = $datos['nombre_productos'];
$unidades = $datos['unidades'];
$unidades_faltantes = $datos['unidades_faltantes'];
$detalles = $datos['detalles'];
$precio_costo = $datos['precio_costo'];
$precio_venta = $datos['precio_venta'];
$precio_compra = $datos['precio_compra'];
$vlr_total_venta = $datos['vlr_total_venta'];
?>
<tr>
<td align='left'><?php echo $cod_productos_var ?></td>
<td align='left'><?php echo $nombre_productos ?></td>
<td align='center'><?php echo $unidades ?></td>
<td align='center'><?php echo $unidades_faltantes ?></td>
<td align='center'><?php echo $detalles ?></td>
<td align='right'><?php echo $precio_costo ?></td>
<td align='right'><?php echo $precio_venta ?></td>
<td align='right'><?php echo $precio_compra ?></td>
<td align='right'><?php echo $vlr_total_venta ?></td>
</tr>
<?php } ?>
</table>
</form>
</body>
</html>