<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<?php
$pagina = 'cargar_factura_temporal_todo.php';
$buscar = addslashes($_POST['buscar']);

if($buscar <> NULL) {
$mostrar_datos_sql = "SELECT * FROM productos WHERE (cod_productos_var = '$buscar' OR nombre_productos like '$buscar%') order by nombre_productos ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$data = mysql_fetch_assoc($consulta);
$tota_reg = mysql_num_rows($consulta);

 echo "<font color='yellow'><strong>".$tota_reg." Resultados para: ".$buscar."</strong></font><br>";
} else { } ?>
<br>
<center>
<table width='95%'>
<tr>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>P.COMPRA</strong></td>
<td align="center"><strong>P.VENTA</strong></td>
</tr>
<?php do {
$cod_productos = $data['cod_productos'];
$cod_productos_var = $data['cod_productos_var'];
$nombre_productos = $data['nombre_productos'];
$unidades_faltantes = $data['unidades_faltantes'];
$precio_costo = $data['precio_costo'];
$precio_compra = $data['precio_compra'];
$precio_venta = $data['precio_venta'];
?>
<td><?php echo $cod_productos_var; ?></td>
<td align="left" ><a href="../modificar_eliminar/agregar_cargar_factura_temporal.php?cod_productos=<?php echo $cod_productos; ?>&pagina=<?php echo $pagina; ?>"><?php echo utf8_encode($nombre_productos); ?></a></td>
<td align="right"><font color='white' size= "+1"><?php echo $unidades_faltantes; ?></font></td>
<td align="right"><font color='white' size= "+1"><?php echo number_format($precio_compra, 0, ",", "."); ?></font></td>
<td align="right"><font color='white' size= "+2"><?php echo number_format($precio_venta, 0, ",", "."); ?></font></td>
</tr>
<?php 
} 
while ($data = mysql_fetch_assoc($consulta)); ?>
</table>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>