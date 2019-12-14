<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar);
$cuenta_actual = addslashes($_SESSION['usuario']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<?php
$buscar = addslashes($_POST['buscar']);

if($buscar <> NULL) {

$mostrar_datos_sql = "SELECT * FROM productos WHERE (cod_productos_var = '$buscar' OR nombre_productos like '%$buscar%') order by nombre_productos ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total_resultados = mysql_num_rows($consulta);
$matriz_consulta = mysql_fetch_assoc($consulta);

 echo "<font color='yellow'><strong>".$total_resultados." Resultados para: ".$buscar."</strong></font><br>";
} else {

}
$datos_info = "SELECT * FROM plan_separe_info_impuesto WHERE estado = 'abierto' AND vendedor = '$cuenta_actual'";
$consulta_info = mysql_query($datos_info, $conectar) or die(mysql_error());
$info = mysql_fetch_assoc($consulta_info);
$cantidad_resultado = mysql_num_rows($consulta_info);

$maximo_valor = "SELECT Max(cod_plan_separe) AS cod_plan_separe FROM plan_separe_info_impuesto";
$consulta_maximo = mysql_query($maximo_valor, $conectar) or die(mysql_error());
$maximo = mysql_fetch_assoc($consulta_maximo);

if ($total_resultados <> 0) {
$pagina = 'plan_separe_temporal_admin.php';
?>
<br>
<center>
<table width="100%">
<tr>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>MED</strong></td>
<td align="center"><strong>P.VENTA</strong></td>
</tr>
<?php do { 
$cod_productos_var = $matriz_consulta['cod_productos_var'];
$nombre_productos = $matriz_consulta['nombre_productos'];
$unidades_faltantes = $matriz_consulta['unidades_faltantes'];
$detalles = $matriz_consulta['detalles'];
$precio_venta = $matriz_consulta['precio_venta'];
?>
<td><?php echo $cod_productos_var; ?></td>
<td ><a href="../admin/agregar_lista_plan_separe.php?cod_productos=<?php echo $cod_productos_var?>&pagina=<?php echo $pagina?>"><?php echo utf8_encode($nombre_productos)?></a></td>
<td align="center"><font size= "+2"><?php echo $unidades_faltantes; ?></font></td>
<td><font size= "+2"><?php echo $detalles; ?></font></td>
<td align="right"><font size= "+2"><?php echo number_format($precio_venta, 0, ",", "."); ?></font></td>
</tr>
<?php 
}
while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
<?php } else { } ?>